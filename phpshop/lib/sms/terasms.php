<?php

/**
 * Отправка SMS через terasms.ru (бывший smsmm.ru)
 * @author terasms.ru
 * @version 2.0
 * @package PHPShopLib
 */
function SendSMS($msg, $phone) {
    global $SysValue;

    $query_array = array(
        'login' => $SysValue['sms']['login'],
        'password' => $SysValue['sms']['pass'],
        'target' => $phone,
        'message' => PHPShopString::win_utf8($msg),
        'sender' => $SysValue['sms']['name']
    );

    $get_string = http_build_query($query_array);

    $fp = fsockopen("auth.terasms.ru", 80, $errno, $errstr, 30);
    if (!$fp) {
        $api_uri = 'http://auth.terasms.ru/outbox/send/';
        $get_string = http_build_query($query_array);
        $res = file_get_contents($api_uri . '?' . $get_string);
    } else {
        
        $out = "GET /outbox/send/send/?$get_string    HTTP/1.1\r\n";
        $out .= "Host: auth.terasms.ru\r\n";
        $out .= "Connection: Close\r\n\r\n";
  
        fwrite($fp, $out);
        $res = null;
        while (!feof($fp)) {
            $res.=fgets($fp, 128);
        }
        fclose($fp);
    }
}

?>