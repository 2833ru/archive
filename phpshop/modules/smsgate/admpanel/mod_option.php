<?php

// Настройки модуля
PHPShopObj::loadClass("array");

class PHPShopSmsgate extends PHPShopArray
{
    private $domen_api;
    private $login_api;
    private $password_api;
    private $admin_phone;
    private $sender;
    private $order_template_sms;
    private $done_order_template_sms;
    private $reject_order_template_sms;
    private $change_status_order_template_sms;


    function __construct()
    {
        
        $this->option();

        $this->domen_api                        = trim($this->option['domen_api']);
        $this->login_api                        = trim($this->option['login_api']);
        $this->password_api                     = trim($this->option['password_api']);
        $this->admin_phone                      = trim($this->option['admin_phone']);
        $this->sender                           = trim($this->option['sender']);
        $this->order_template_sms               = trim($this->option['order_template_sms']);
        $this->done_order_template_sms          = trim($this->option['done_order_template_sms']);
        $this->order_template_admin_sms         = trim($this->option['order_template_admin_sms']);
        $this->change_status_order_template_sms = trim($this->option['change_status_order_template_sms']);

    }

    /**
     * Настройки модуля
     */
    function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['smsgate']['smsgate_message']);
        $this->option = $PHPShopOrm->select();
    }

    /**
     * @return server api
     */
    function getServerApi()
    {
        return $this->domen_api;
    }


    /**
     * @return login
     */
    function getloginApi()
    {
        return $this->login_api;
    }

    /**
     * @return password
     */
    function getPassApi()
    {
        return $this->password_api;
    }

    /**
     * @return phone
     */
    function getAdminPhone()
    {
        return $this->admin_phone;
    }

    /**
     * @return sender
     */
    function getSender()
    {
        return $this->sender;
    }

    /**
     * @return order template
     */
    function getTplOrder()
    {
        return $this->order_template_sms;
    }

    /**
     * @return done order template
     */
    function getTplDoneOrder()
    {
        return $this->done_order_template_sms;
    }

    /**
     * @return reject order template
     */
    function getTplAdminOrder()
    {
        return $this->order_template_admin_sms;
    }

    /**
     * @return status order template
     */
    function getTplStatusOrder()
    {
        return $this->change_status_order_template_sms;
    }

    /**
     * parser string
     */
    function parseString($string, $datainsert)
    {
      $str = strtr($string, $datainsert);
      return $str;
    }

    /**
     * validate phone
     */
    function true_num($phone)
    {
        $str = preg_replace("/[^0-9]/", "", $phone);

        return $str;
    }


    /**
     * send sms
     */
    function sendSmsgate($phone, $msg)
    {
        $msg = mb_convert_encoding($msg, 'utf-8', 'windows-1251');
        $sender = mb_convert_encoding($this->sender, 'utf-8', 'windows-1251');

        $src = '<?xml version="1.0" encoding="utf-8"?>
        <request>
            <security>
                <login value="' . $this->login_api . '" />
                <password value="' . $this->password_api . '" />
            </security>
            <message type="sms">';

        if($sender){
            $src.= '<sender>' . $sender . '</sender>';
        }

        $src.='<text>' . $msg . '</text>';
        
        foreach ($phone as $k => $p) {
          $src.='<abonent phone="' . trim($p) . '" number_sms="' . $k . '" />';
        }
        
        $src.='</message>
        </request>';

        // XML-документ
        $href = 'https://'.$this->domen_api.'/xml/'; // адрес сервера

        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array ('Content-type: text/xml','charset=utf-8','Expect:'));
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_CRLF, true);
        curl_setopt ($ch, CURLOPT_POST, true);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $src);
        curl_setopt ($ch, CURLOPT_URL, $href);
        $result = curl_exec($ch);
        curl_close($ch);

        
        //echo $result;
        
    }

    /**
     * @param $msg
     * отправка смс администратору
     */
    function sendSmsAdmin($msg)
    {
        $phone = $this->admin_phone;
        $phone = explode(',', $phone);
        $this->sendSmsgate($phone, $msg);
    }
}