<?php

class ddeliverywidget {

    public function __construct() {
        
    }

    public function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['ddeliverywidget']['ddeliverywidget_system']);
        return $PHPShopOrm->select();
    }
}




class DDeliveryHelper
{

    const PROD_URL = 'https://ddelivery.ru/api/';

    public $apiKey;

    public function __construct($apiKey = null)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param $urlSuf
     * @param array $params
     * @return array
     */
    public function request($urlSuf, $params = array())
    {
        $url = self::PROD_URL;
        $url .= $urlSuf; //echo $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        $result = json_decode(curl_exec($ch), true); // run the whole process
        curl_close($ch);
        return $result;
    }

    /**
     *
     * Отправка "временной"" заявки на DDelivery.ru
     *
     * @param array $params
     * to_name - client name,
     * to_phone - client phone,
     * to_email - client email,
     * to_flat - client flat,
     * to_street - client street
     * to_house - client house
     * comment - client comment
     * payment_variant - CMS id
     * local_status - CMS status id
     * shop_refnum - CMS order ID,
     * payment_price - sum
     * @return array
     * @throws Exception
     */
    public function sendOrder($params)
    {
        if(!$this->apiKey){
            throw new Exception('No API - key defined');
        }
        return $this->request($this->apiKey . '/sdk/update-order.json', $params);
    }

}
?>
