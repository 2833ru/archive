<?php
/**
 * Файл выгрузки для Яндекс Маркет
 * @package PHPShopCore
 */

$_classPath="../phpshop/";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("security");


class PHPShopYml {
    var $xml='';

    function PHPShopYml() {
        $this->PHPShopSystem = new PHPShopSystem();
        $PHPShopValuta = new PHPShopValutaArray();
        $this->PHPShopValuta=$PHPShopValuta->getArray();

        // Процент накрутки
        $this->percent=$this->PHPShopSystem->getValue('percent');

        // Валюта по умочанию
        $this->defvaluta=$this->PHPShopSystem->getValue('dengi');
        $this->defvalutaiso=$this->PHPShopValuta[$this->defvaluta]['iso'];

        // Кол-во знаков после запятой в цене
        $this->format=$this->PHPShopSystem->getSerilizeParam('admoption.price_znak');
    }

    // Вывод каталогов
    function  category() {
        $Catalog=array();
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name']);
        $data = $PHPShopOrm->select(array('id,name,parent_to'),false,false,array('limit'=>1000));

        if(is_array($data))
            foreach($data as $row) {
                $Catalog[$row['id']]['id']=$row['id'];
                $Catalog[$row['id']]['name']=$row['name'];
                $Catalog[$row['id']]['parent_to']=$row['parent_to'];
            }

        return $Catalog;
    }

    // Вывод продуктов
    function  product() {
        $Products=array();

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
        $data = $PHPShopOrm->select(array('*'),array('yml'=>"='1'",'enabled'=>"='1'"),false,array('limit'=>1000000));
        if(is_array($data))
            foreach($data as $row) {
                $id=$row['id'];
                $name=htmlspecialchars($row['name']);
                $category=$row['category'];
                $uid=$row['uid'];
                $price=$row['price'];

                if($row['p_enabled'] == 1) $p_enabled="true";
                else $p_enabled="false";

                $description=PHPShopString::mySubstr($row['description'],300);
                $baseinputvaluta=$row['baseinputvaluta'];

                if ($baseinputvaluta) {
                    if ($baseinputvaluta!==$this->defvaluta) {//Если валюта отличается от базовой

                        $vkurs = $this->PHPShopValuta[$baseinputvaluta]['kurs'];

                        // Приводим цену в базовую валюту
                        $price=$price/$vkurs;
                    }
                }

                $price=($price+(($price*$percent)/100));
                $price=round($price,$this->format);

                $array=array(
                        "id"=>$id,
                        "category"=>$category,
                        "name"=>$name,
                        "picture"=>$row['pic_small'],
                        "price"=>$price,
                        "p_enabled"=>$p_enabled,
                        "yml_bid_array"=>unserialize($row['yml_bid_array']),
                        "uid"=>$uid,
                        "description"=>$description);

                $Products[$id]=$array;
            }
        return $Products;
    }

    function setHeader() {
        $this->xml.='<?xml version="1.0" encoding="windows-1251"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="'.date('Y-m-d H:m').'">

<shop>
<name>'.$this->PHPShopSystem->getName().'</name>
<company>'.$this->PHPShopSystem->getValue('company').'</company>
<url>http://'.$_SERVER['SERVER_NAME'].'</url>';
    }

    function setCurrencies() {
        $this->xml.='<currencies>';
        $this->xml.='<currency id="'.$this->PHPShopValuta[$this->PHPShopSystem->getValue('dengi')]['iso'].'" rate="1"/>';
        $this->xml.='</currencies>';
    }

    function setCategories() {
        $this->xml.='<categories>';
        $category = $this->category();
        foreach($category as $val) {
            if(empty($val['parent_to']))
                $this->xml.='<category id="'.$val['id'].'">'.$val['name'].'</category>';
            else $this->xml.='<category id="'.$val['id'].'" parentId="'.$val['parent_to'].'">'.$val['name'].'</category>';
        }

        $this->xml.='</categories>';
    }

    function setDelivery() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name30']);
        $data = $PHPShopOrm->select(array('price'),array('flag'=>"='1'"),false,array('limit'=>1));
        if(is_array($data))
            $this->xml.='<local_delivery_cost>'.$data['price'].'</local_delivery_cost>';
    }

    function setProducts() {
        $this->xml.='<offers>';
        $product=$this->product();
        foreach($product as $val) {

            $bid_str=null;
            $bid_str=null;

            // Если есть bid
            if(!empty($val['yml_bid_array']['bid_enabled'])) $bid_str='  bid="'.$val['yml_bid_array']['bid'].'" ';

            // Если есть cbid
            if(!empty($val['yml_bid_array']['cbid_enabled'])) $bid_str.='  cbid="'.$val['yml_bid_array']['cbid'].'" ';

            $this->xml.='<offer id="'.$val['id'].'" available="'.$val['p_enabled'].'" '.$bid_str.'>
 <url>http://'.$_SERVER['SERVER_NAME'].'/shop/UID_'.$Pval['id'].'.html?from=yml</url>
      <price>'.$val['price'].'</price>
      <currencyId>'.$this->defvalutaiso.'</currencyId>
      <categoryId>'.$val['category'].'</categoryId>
      <picture>http://'.$_SERVER['SERVER_NAME'].$val['picture'].'</picture>
      <name>'.$val['name'].'</name>
      <description>'.$val['description'].'</description>
    </offer>
';
        }
        $this->xml.='</offers>';
    }

    function serFooter() {
        $this->xml.='</shop></yml_catalog>';
    }

    function compile() {
        $this->setHeader();
        $this->setCurrencies();
        $this->setCategories();
        $this->setDelivery();
        $this->setProducts();
        $this->serFooter();
        echo $this->xml;
    }
}

$PHPShopYml = new PHPShopYml();
$PHPShopYml->compile();

?>