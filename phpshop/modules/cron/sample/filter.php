<?php
/**
 * Кеширование значений фильтра, под которые нет товаров.
 */

// Включение для SSH Cron
$enabled = true;

$_classPath="../../../";
include_once($_classPath . "class/obj.class.php");
PHPShopObj::loadClass(["base", "system", "orm"]);
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini",true,true);

// Авторизация
if($_GET['s'] == md5($PHPShopBase->SysValue['connect']['host'].$PHPShopBase->SysValue['connect']['dbase'].$PHPShopBase->SysValue['connect']['user_db'].$PHPShopBase->SysValue['connect']['pass_db']))
    $enabled = true;

if (empty($enabled))
    exit("Ошибка авторизации!");

class CacheFilter
{
    public function __construct()
    {
       $this->createCache($this->getCategories());
    }

    private function getCategories()
    {
        $orm = new PHPShopOrm('phpshop_categories');

        return $orm->getList(['id', 'sort'],  ['skin_enabled' => "!='1'"]);
    }

    private function createCache($categories)
    {
        foreach ($categories as $category) {
            $sorts = unserialize($category['sort']);

            if(is_array($sorts) && count($sorts) > 0) {
                $cache = [];
                foreach ($sorts as $sort) {
                    $values = $this->getSortValues((int) $sort);
                    foreach ($values as $value) {
                        $count = $this->countProducts((int) $value, (int) $sort, (int) $category['id']);
                        if($count === 0) {
                            $cache['filter_cache'][(int) $sort][] = (int) $value;
                        } else {
                            $cache['products'][(int) $sort][(int) $value] = $count;
                        }
                    }
                }

                $orm = new PHPShopOrm('phpshop_categories');
                $orm->update(['sort_cache_new' => serialize($cache), 'sort_cache_created_at_new' => time()], ['id=' => (int) $category['id']]);
            }
        }
    }

    private function getSortValues($sortId)
    {
        $orm = new PHPShopOrm('phpshop_sort');

        return array_column($orm->getList(['id'], ['category' => sprintf('="%s"', $sortId)]), 'id', 'id');
    }

    private function countProducts($valueId, $sortId, $categoryId)
    {
        $orm = new PHPShopOrm();

        $result = $orm->query(sprintf('select COUNT("id") as count from `phpshop_products` where `category`="%s" and vendor REGEXP "i%s-%si" and enabled="1" and parent_enabled="0"', $categoryId, $sortId, $valueId));

        $row = mysqli_fetch_assoc($result);

        return (int) $row['count'];
    }
}

new CacheFilter();

?>
