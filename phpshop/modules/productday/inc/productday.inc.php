<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

class ProductDay extends PHPShopProductElements {

    var $debug = false;
    
    function productdayview() {
        
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
        $PHPShopOrm->debug = false;
        $productday = $PHPShopOrm->select(array('*'), array('productday' => " ='1'"), array('order' => 'datas desc'), array('limit' => 1));

        $hour = date("H");
        $minute = date("i");
        $second = date("s");
        $hour_good = (18 - $hour);
        $minute_good = (60 - $minute);
        $second_good = (60 - $second);
        if ($hour_good < 0) {
            $hour_good = 0;
        }


        if (is_array($productday)) {
            PHPShopParser::set('productDayId', $productday['id']);
            PHPShopParser::set('productDayName', $productday['name']);
            PHPShopParser::set('productDayDescription', $productday['description']);
            PHPShopParser::set('productDayPrice', $productday['price']);
            PHPShopParser::set('productDayPriceN', $productday['price_n']);
            PHPShopParser::set('productDayPicBig', $productday['pic_big']);
            PHPShopParser::set('productDayPicSmall', $productday['pic_small']);
            PHPShopParser::set('productDayHourGood', $hour_good);
            PHPShopParser::set('productDayMinuteGood', $minute_good);
            PHPShopParser::set('productDaySecondGood', $second_good);
            PHPShopParser::set('productDayCurrency', $this->currency);
            

            PHPShopParser::set('productDay', PHPShopParser::file($GLOBALS['SysValue']['templates']['productday']['product_day'], true, false, true));
        }
    }

}

// Добавляем в шаблон элемент
$GLOBALS['ProductDay'] = new ProductDay();
$GLOBALS['ProductDay']->productdayview();
?>