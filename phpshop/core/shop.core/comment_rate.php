<?php

/**
 * Вывод средней оценки к товару из отзывов пользователей
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCoreFunction
 * @param obj $obj объект класса
 * @param args $args масив данных и параметров.
 * @return mixed
 */
function comment_rate($obj, $args) {
    $row = $args['row'];
    $type = $args['type'];
    $rate = new rateForComment($row['rate'], $row['rate_count']);
    if ($type)
        $obj->set("rateCid", $rate->parseCID());
    else
        $obj->set("rateUid", $rate->parseUid());
}

/**
 * класс вывода рейтингов для подробного описания и для списка товаров.
 * @package PHPShopElementsDepricated
 */
class rateForComment {

    function rateForComment($rate, $num) {
        $oneSpaceWidth=1;
        if ($num) {
            $rate = round($rate, 1);
            $GLOBALS['SysValue']['other']['avgRateWidth'] = 16 * $rate + $oneSpaceWidth * ceil($rate);
            $GLOBALS['SysValue']['other']['avgRateNum'] = $num;
            $GLOBALS['SysValue']['other']['avgRate'] = $rate;
        } else {
            $GLOBALS['SysValue']['other']['avgRateWidth'] = 0;
            $GLOBALS['SysValue']['other']['avgRateNum'] = 0;
            $GLOBALS['SysValue']['other']['avgRate'] = 0;
        }
    }

    function parseCid() {
        global $SysValue;
        // Подключаем шаблон
        $path = './' . $SysValue['dir']['templates'] . chr(47) . $_SESSION['skin'] . "/comment/avg_rate_cid.tpl";
        if (PHPShopParser::checkFile($path, true))
            return PHPShopParser::file ($path, true);
        return null;
    }

    function parseUid() {
        global $SysValue;
        // Подключаем шаблон
        $path = './' . $SysValue['dir']['templates'] . chr(47) . $_SESSION['skin'] . "/comment/avg_rate_uid.tpl";
        if (PHPShopParser::checkFile($path, true))
            return PHPShopParser::file ($path, true);
        return null;
    }

}

?>