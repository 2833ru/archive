<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("delivery");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();



if (!empty($_GET['id'])) {
    $PHPShopOrder = new PHPShopOrderFunction($_GET['orderID']);

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
    $row = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])), false, array('limit' => 1));

    if (is_array($row)) {

        $num = 0;
        $csv1 = "Начало личных данных;;;;;;;;;;\n";
        $csv2 = "Начало заказанных товаров;;;;;;;;;;\n";
        $csv3 = "Начало данных доставки;;;;;;;;;;\n";

        $adr_info = null;
        $id = $row['id'];
        $datas = $row['datas'];
        $uid = $row['uid'];
        $order = unserialize($row['orders']);
        $mail = $order['Person']['mail'];
        $user = $row['user'];
        $name = $order['Person']['name_person'] . $row['fio'];
        $company = str_replace("&quot;", '"', $order['Person']['org_name'] . $row['org_name']);
        $inn = $order['Person']['org_inn'] . $row['org_inn'];
        $kpp = $order['Person']['org_kpp'] . $row['org_kpp'];
        $tel = $order['Person']['tel_code'] . " " . $order['Person']['tel_name'] . $row['tel'];

        // формируем адрес для новой структуры таблицы заказов
        if ($row['country'])
            $adr_info .= ", страна: " . $row['country'];
        if ($row['state'])
            $adr_info .= ", регион/штат: " . $row['state'];
        if ($row['city'])
            $adr_info .= ", город: " . $row['city'];
        if ($row['index'])
            $adr_info .= ", индекс: " . $row['index'];
        if ($row['street'])
            $adr_info .= ", улица: " . $row['street'];
        if ($row['house'])
            $adr_info .= ", дом: " . $row['house'];
        if ($row['porch'])
            $adr_info .= ", подъезд: " . $row['porch'];
        if ($row['door_phone'])
            $adr_info .= ", код домофона: " . $row['door_phone'];
        if ($row['flat'])
            $adr_info .= ", квартира: " . $row['flat'];
        if ($row['delivtime'])
            $adr_info .= ", время доставки: " . $row['delivtime'];
        if ($row['dop_info'])
            $adr_info .= ", дополнительная информация: " . $row['dop_info'];

        $adres = str_replace("&quot;", '"', $adr_info . $order['Person']['adr_name']);
        $adres = PHPShopSecurity::CleanOut($adres);
        $oplata = $PHPShopOrder->getOplataMetodName();
        $sum = $PHPShopOrder->returnSumma($order['Cart']['sum'], $order['Person']['discount']);
        $discount = $order['Person']['discount'];
        $weight = $order['Cart']['weight'];

        $csv1.="$uid;$datas;$mail;$name;$company;$tel;$oplata;$sum;$discount;$inn;$adres;$kpp;$user;\n";

        if (is_array($order['Cart']['cart']))
            foreach ($order['Cart']['cart'] as $val) {
                $id = $val['id'];
                $uid = $val['uid'];
                $num = $val['num'];
                $sum = $PHPShopOrder->returnSumma($val['price'] * $num, $order['Person']['discount']);

                // Нахождение кода валюты
                $valuta = $PHPShopOrder->getValutaIso($id);
                $csv2.="$id;$uid;$num;$sum;$valuta;;;;;;\n";
            }

        // Доставка

        $PHPShopDelivery = new PHPShopDelivery($order['Person']['dostavka_metod']);
        $csv3.=$PHPShopDelivery->getCity() . ";" . $PHPShopDelivery->getPrice($sum, $weight) . ";" . $valuta . "\n";

        $csv = $csv1 . $csv2 . $csv3;

        $file = $row['uid'] . ".csv";
        @$fp = fopen("../../csv/" . $file, "w+");
        if ($fp) {
            fputs($fp, $csv);
            fclose($fp);
        }
        header("Location: ../../csv/" . $file);
    } else die('Заказа с таким номером не существует');
}
?>