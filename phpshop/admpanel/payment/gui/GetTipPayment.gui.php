<?php
// Типы оплат
function TipPayment($payment) {
    $TIP = array(
        "message" => "Сообщение",
        "bank" => "Счет в банк",
        "sberbank" => "Сбербанк",
        "robox" => "Обменная касса Robox",
        "webmoney" => "Webmoney",
        "interkassa" => "Обменная касса Interkassa",
        "rbs" => "Visa, Mastercard (RBS)",
        "z-payment" => "Обменная касса Z-payment",
        "payonlinesystem" => "Visa, Mastercard (PayOnlineSystem)"
    );

    foreach ($TIP as $k => $v)
        if ($k == $payment)
            return $v;
    return "Оплата " . $payment;
}

// Выбор файла
function GetTipPayment($dir) {

    $path = "../../payment/";
    $arr=null;

    if ($dh = @opendir($path)) {

        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != "..") {
                if (is_dir($path . $file)) {
                    if ($dir == $file)
                        $s = "selected";
                    else
                        $s = "";
                    
                    if($file == 'modules')
                        $comment='data-subtext="модуль оплаты"';
                    else $comment=null;
                    
                    $arr[] = array(TipPayment($file), $file, $s,$comment);
                }
            }
        }
        closedir($dh);
    }
    if (is_array($arr))
        return $arr;
    else
        return null;
}

?>