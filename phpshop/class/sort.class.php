<?php

if (!defined("OBJENABLED")) {
    require_once(dirname(__FILE__)."/array.class.php");
    require_once(dirname(__FILE__)."/category.class.php");
}

/**
 * Сортировки и фильтры товаров
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopClass
 */
class PHPShopSort {
    /**
     * Отладка
     * @var bool
     */
    var $debug=false;
    var $disp;

    /**
     * Вывод фильтра характеристик
     * @param int $category ИД категории характеристики
     * @param string $sort сериализованный массив характеристик
     */
    function PHPShopSort($category=null,$sort=null) {

        // Направление сортировки
        $this->direct();

        $vendSort=null;
        if(!empty($sort))
            $sort = unserialize($sort);
        elseif(!empty($category)) {
            $PHPShopCategory = new PHPShopCategory($category);
            $sort=$PHPShopCategory->unserializeParam('sort');
        }

        // Список для выборки
        if(is_array($sort)) {
            foreach($sort as $value) {
                $sortList.=' id='.trim($value).' OR';
            }
            $sortList = substr($sortList, 0, strlen($sortList)-2);

            $PHPShopOrm = new PHPShopOrm();
            $PHPShopOrm->debug=$this->debug;
            $PHPShopOrm->comment=__CLASS__.'.'.__FUNCTION__;
            $result = $PHPShopOrm->query("select * from ".$GLOBALS['SysValue']['base']['sort_categories']." where ($sortList) and filtr='1' and goodoption!='1' order by num");
            while (@$row = mysql_fetch_array($result)) {
                $id = $row['id'];
                $name = $row['name'];
                $this->disp.=$this->value($id, $name, true);
            }
        }
    }


    /**
     * Направление сортировки товара в каталоге
     */
    function direct() {
        global $SysValue;

        // Направление сортировки пользователем
        switch($_GET['f']) {
            case(1):
                $SysValue['other']['productSortNext']=2;
                $SysValue['other']['productSortImg']=1;
                $SysValue['other']['productSortTo']=1;
                break;
            case(2):
                $SysValue['other']['productSortNext']=1;
                $SysValue['other']['productSortImg']=2;
                $SysValue['other']['productSortTo']=2;
                break;
            default:
               $SysValue['other']['productSortNext']=2;
                //$SysValue['other']['productSortImg']=1;
               $SysValue['other']['productSortTo']=1;
        }

        // Сортировка пользователем
        switch($_GET['s']) {
            case(1):
                $SysValue['other']['productSortA']="sortActiv";
                $SysValue['other']['productSort']=1;
                break;
            case(2):
                $SysValue['other']['productSortB']="sortActiv";
                $SysValue['other']['productSort']=2;
                break;
            case(3):
                $SysValue['other']['productSortC']="sortActiv";
                $SysValue['other']['productSort']=3;
                break;
            case(4):
                $SysValue['other']['productSortD']="sortActiv";
                $SysValue['other']['productSort']=4;
                break;
            default:
                $SysValue['other']['productSort']=1;
        }


        if(empty($_GET['v'])) {
            $SysValue['other']['productVendor']= "";
        }
        else {
            $productVendor=null;
            if(is_array($_GET['v'])) {
                foreach($_GET['v'] as $k=>$v)
                    $productVendor.='v['.intval($k).']='.intval($v).'&';
                $productVendor=substr($productVendor, 0, strlen($productVendor)-1);
            }
            $SysValue['other']['productVendor']= $productVendor;
        }

        // Сортировка по цене
        $SysValue['other']['productRriceOT']=PHPShopSecurity::TotalClean($_POST['priceOT'],1);
        $SysValue['other']['productRriceDO']=PHPShopSecurity::TotalClean($_POST['priceDO'],1);
    }

    /**
     * Вывод значений характеристик
     * @param int $n ИД характеристики
     * @param string $title Название
     * @param bool $all Показывать опцию выбрать все
     * @return string
     */
    function value($n, $title, $all=false) {
        global $SysValue;

        $dis = null;
        $i=1;
        if (empty($_POST['v']))
            $vendor = $SysValue['nav']['query']['v'];
        else
            $vendor = $_POST['v'];

        $all_sel='selected';
        $PHPShopOrm = new PHPShopOrm();
        $PHPShopOrm->debug=$this->debug;
        $PHPShopOrm->comment=__CLASS__.'.'.__FUNCTION__;
        $result = $PHPShopOrm->query("select * from ".$SysValue['base']['sort']." where category=$n order by num");
        while ($row = mysql_fetch_array($result)) {
            $id = $row['id'];
            $name = substr($row['name'], 0, 35);
            $sel = null;
            if (is_array($vendor))
                foreach ($vendor as $k => $v) {
                    if ($id == $v) {
                        $sel = "selected";
                        $all_sel=null;
                    }
                }

            $value[$i]=array($name,$id,$sel);
            $i++;

        }

        $SysValue['sort'][] = $n;

        // Показать выбрать все
        if(!empty($all)) {
            $value[]=array('-- все '.$title.' --','',$all_sel);
        }

        $size=(strlen($title)+10)*7;
        $disp=PHPShopText::select('v['.$n.']',$value,$size,false,false,false,false,false,$n);

        // Массив характеристик для использования в модулях
        $this->value_array=$value;

        return $disp;
    }

    function display() {
        global $SysValue;

        $v_ids=null;
        if(!empty($this->disp)) {
            if(is_array($SysValue['sort']))
                foreach($SysValue['sort'] as $value)
                    $v_ids.=$value.",";
            $len=strlen($v_ids);
            $v_ids=substr($v_ids,0,$len-1);

            // Кнопка применить и сбросить фильтра
            $SysValue['other']['vendorSelectDisp']=PHPShopText::button($SysValue['lang']['sort_apply'],$onclick='GetSortAll('.$SysValue['nav']['id'].','.$v_ids.')');
            $SysValue['other']['vendorSelectDisp'].=PHPShopText::button($SysValue['lang']['sort_reset'],$onclick='window.location.replace(\'?\')');
            $SysValue['other']['vendorDispTitle']=PHPShopText::div(PHPShopText::b($SysValue['lang']['sort_title']));
        }

        return PHPShopText::td($this->disp);
    }

}


/**
 * Массив с характеристиками товаров
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopArray
 */
class PHPShopSortArray extends PHPShopArray {

    function PHPShopSortArray() {
        $this->objBase=$GLOBALS['SysValue']['base']['table_name21'];
        parent::PHPShopArray('id','name','page');
    }
}

/**
 * Массив с характеристиками категорий товаров
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopArray
 */
class PHPShopSortCategoryArray extends PHPShopArray {

    function PHPShopSortCategoryArray() {
        $this->objBase=$GLOBALS['SysValue']['base']['table_name20'];
        parent::PHPShopArray('id','name','category','filtr','page','flag','goodoption');
    }
}
?>