<?php

function sms_mod_smsfly_hook($obj) {

    // Настройки модуля
    include_once(dirname(__FILE__) . '/mod_option.hook.php');
    $Array = new PHPShopSmsflyArray();
    $option = $Array->getArray();

    $msg = $obj->lang('mail_title_adm') . $obj->ouid . " - " . $obj->sum . $obj->currency;
    $text = iconv('windows-1251', 'utf-8', htmlspecialchars($msg));
    $description = iconv('windows-1251', 'utf-8', htmlspecialchars($msg));
    $start_time = date("Y-m-d H:i:s");
    $end_time = date("Y-m-d H:i:s", time() + 10800); // плюс 3 часа
    $rate = 120;
    $livetime = 4;
    $source = $_SERVER['SERVER_NAME']; // Alfaname
    $recipient = $option['phone'];
    $user = $option['merchant_user'];
    $password = $option['merchant_pwd'];

    $myXML = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    $myXML .= "<request>";
    $myXML .= "<operation>SENDSMS</operation>";
    $myXML .= '		<message start_time="' . $start_time . '" end_time="' . $end_time . '" livetime="' . $livetime . '" rate="' . $rate . '" desc="' . $description . '" source="' . $source . '">' . "\n";
    $myXML .= "		<body>" . $text . "</body>";
    $myXML .= "		<recipient>" . $recipient . "</recipient>";
    $myXML .= "</message>";
    $myXML .= "</request>";

    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERPWD, $user . ':' . $password);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, 'http://sms-fly.com/api/api.php');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml", "Accept: text/xml"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myXML);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($option['sandbox'] == 1) {
            echo '<textarea spellcheck="false" style="width:300px;height:300px">';
            echo 'Запрос: '.iconv('utf-8','windows-1251',$myXML);
            echo '-----';
            echo 'Ответ:'.$response;
            echo '</textarea>';
        }
        return true;
    }
}

$addHandler = array
    (
    'sms' => 'sms_mod_smsfly_hook'
);
?>