<?php

/**
 * PHPShop MoySklad Rest API
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 */
class MoyskladRest {

    /**
     * Последние сообщения об ошибках
     * @var array
     */
    protected $_errors = array();

    /**
     * Данные API
     * @var array
     */
    public $_credentials = array(
        'USER' => '',
        'PWD' => '',
        'VAT' => '18',
        'ORG' => ''
    );

    /**
     * Указываем, куда будет отправляться запрос
     * @var string
     */
    public $_endPoint = 'online.moysklad.ru';

    /**
     * Сформировываем запрос
     *
     * @param string $method запрос [PUT /exchange/rest/ms/xml/CustomerOrder]
     * @param string $body данные для передачи
     * @param string $parse обработка тегов формата product:uuid
     * @param string $sandbox режим песочницы, данные
     * @return string
     */
    function request($method, $body = false, $parse = false, $sandbox = false, $format=true) {

        if (empty($sandbox)) {

            $sock = fsockopen("ssl://online.moysklad.ru", 443, $errno, $errstr, 30);

            // UTF8
            if($format)
            $body = iconv("windows-1251", "utf-8", $body);

            $this->_request[] = $method;

            if (!$sock)
                die("$errstr ($errno)\n");

            fputs($sock, $method . " HTTP/1.0\r\n");
            fputs($sock, "Host: " . $this->_endPoint . "\r\n");
            fputs($sock, "Authorization: Basic " . base64_encode($this->_credentials['USER'] . ":" . $this->_credentials['PWD']) . "\r\n");
            fputs($sock, "Content-Type: application/xml \r\n");
            fputs($sock, "Accept: */*\r\n");
            fputs($sock, "Content-Length: " . strlen($body) . "\r\n");
            fputs($sock, "Connection: close\r\n\r\n");
            fputs($sock, "$body");

            while ($str = trim(fgets($sock, 4096)));

            $body = "";

            while (!feof($sock))
                $body.= fgets($sock, 4096);

            fclose($sock);
        }
        else
            $body = $sandbox;


        // Парсинг XML тега good:uuid
        if ($parse) {
            if (strstr($parse, ":")) {
                $parse_array = explode(":", $parse);

                $xml = @simplexml_load_string($body);
                $json = @json_encode($xml);
                $array = @json_decode($json, true);

                if(is_array($array))
                $body = $array[$parse_array[0]][$parse_array[1]];
            }
        }

        return $body;
    }

    /**
     * Проверка пользователя по mail
     * @param array $data массив данных
     * @return type
     */
    function getUser($data = array()) {
        $result = $this->request('GET /exchange/rest/ms/xml/Company/list?filter=contact.email%3D' . $data['email'] . '&count=1', false, 'company:uuid');
        $this->_result[__FUNCTION__] = $result;
        return $result;
    }

    /**
     * Код продавца
     * @param string $code код продавца
     * @return type
     */
    function getMyCompany($code) {
        $result = $this->request('GET /exchange/rest/ms/xml/MyCompany/list?filter=code%3D' . $code . '&count=1', false, 'myCompany:uuid');
        return $result;
    }

    /**
     * Проверка товара по id
     * @param string $user_email почта покупателя
     * @return type
     */
    function getProduct($product_id) {
        $result = $this->request('GET /exchange/rest/ms/xml/Good/list?filter=code%3D' . $product_id . '&count=1', $body = false, 'good:uuid');
        $this->_result[__FUNCTION__] = $result;
        return $result;
    }
    
    
     /**
     * Обновление данных продукта по uuid
     * @param string $user_email почта покупателя
     * @return type
     */
    function updateProduct($uuid,$id) {
        
        // Запрос тела xml
        $result = $this->request('GET /exchange/rest/ms/xml/Good/list?filter=uuid%3D' . $uuid . '&count=1');
        $body=str_replace('productCode=""','productCode="'.$id.'"',$result);
        
        echo $body;
        
        // Обновление
        $result = $this->request('PUT /exchange/rest/ms/xml/Good/list/update', $body, false, false, false);
        $this->_result[__FUNCTION__] = $result;
        return $result;
    }
    
    

    function format($price) {
        return number_format($price, 1, '.', '');
    }

    /**
     * Новый заказ
     * @param array $data данные заказа
     * @return string
     */
    function addOrder($data = array()) {

        // Код продавца
        $targetAgentUuid = $this->getMyCompany($this->_credentials['ORG']);

        // Поиск пользователя в базе
        $useruuid = $this->getUser($data);
        if (empty($useruuid))
            $this->addUser($data);
        $useruuid = $this->getUser($data);

        $body = '<?xml version="1.0" encoding="UTF-8"?>
<customerOrder vatIncluded="true"
    applicable="true"
    sourceStoreuuId="1" 
    payerVat="true"
    sourceAgentUuid="' . $useruuid . '" ';

        // Код продавца если есть
        if (!empty($targetAgentUuid))
            $body.='targetAgentUuid="' . $targetAgentUuid . '" ';

        $body.='name="' . $data['ouid'] . '">';

        if (is_array($data['cart'])) {
            foreach ($data['cart'] as $item) {

                // Поиск товара в базе склада
                $uuid = $this->getProduct($item['id']);
                if (!empty($uuid) and !strstr($uuid,'Status report')) {
                    $body.='
    <customerOrderPosition vat="' . $this->_credentials['VAT'] . '" goodUuid="' . $uuid . '" quantity="' . $this->format($item['num']) . '" discount="0.0">
    <basePrice sumInCurrency="' . $this->format($item['price'] * 100) . '" sum="' . $this->format($item['price']) . '"/>
    <reserve>0.0</reserve>
    <price sum="' . $this->format($item['price']) . '" sumInCurrency="' . $this->format($item['price'] * 100) . '"/>
    </customerOrderPosition>';
                } else {
                    $this->_errors[] = 'Товара ID=' . $item['id'] . ', ' . $item['name'] . ' нет в базе МойСклад';
                }
            }
        }

        // Доставка
        if (!empty($data['delivery_id'])) {

            // Поиск услуги в базе склада
            $delivery_uuid = $this->getProduct('delivery_' . $data['delivery_id']);
            if (!empty($delivery_uuid) and strstr($delivery_uuid,'Status report')) {
                $body.='
    <customerOrderPosition vat="0" goodUuid="' . $delivery_uuid . '" quantity="' . $this->format(1) . '" discount="0.0">
    <basePrice sumInCurrency="' . $this->format($data['delivery_price'] * 100) . '" sum="' . $this->format($data['delivery_price']) . '"/>
    <reserve>0.0</reserve>
    <price sum="' . $this->format($item['delivery_price']) . '" sumInCurrency="' . $this->format($data['delivery_price'] * 100) . '"/>
    </customerOrderPosition>';
            } else {
                $this->_errors[] = 'Доставки ID=' . $data['delivery_id'] . ' нет в базе МойСклад';
            }
        }


        $body.='</customerOrder>';
        $result = $this->request('PUT /exchange/rest/ms/xml/CustomerOrder', $body);
        $this->_body[__FUNCTION__] = $body;
        $this->_result[__FUNCTION__] = $result;
        return $result;
    }

    /**
     * Добавление пользователя
     * @param array $data данные покупателя
     * @return type
     */
    function addUser($data = array()) {
        $body = '<?xml version="1.0" encoding="UTF-8"?>
<company payerVat="true" companyType="URLI" discount="' . $data['discount'] . '" autoDiscount="0.0" discountCardNumber="" discountCorrection="0.0" name="' . $data['name'] . '">
   <requisite legalTitle="' . $data['legalTitle'] . '" legalAddress="' . $data['legalAddress'] . '" actualAddress="' . $data['actualAddress'] . '" inn="' . $data['inn'] . '" kpp="' . $data['kpp'] . '" okpo="" ogrn="" ogrnip="" nomerSvidetelstva=""/>
   <bankAccount accountNumber="' . $data['accountNumber'] . '" bankLocation="' . $data['bankLocation'] . '" bankName="' . $data['bankName'] . '" bic="' . $data['bic'] . '" correspondentAccount="' . $data['correspondentAccount'] . '" isDefault="true"></bankAccount>
   <contact address="' . $data['addres'] . '" phones="' . $data['phones'] . '" faxes="" mobiles="" email="' . $data['email'] . '"/>
</company>';
        $result = $this->request('PUT /exchange/rest/ms/xml/Company', $body);
        $this->_result[__FUNCTION__] = $result;
        $this->_body[__FUNCTION__] = $body;
    }

    /**
     * Запись лога
     * @param array $message сообщенеи платежной системы
     * @param string $order_id номер заказа
     */
    function log($message, $order_id) {

        $message['Ошибки'] = $this->_errors;
        $message['Ответ'] = $this->_result;
        $message['Запрос'] = $this->_body;
        $message['URL'] = $this->_request;

        if (is_array($message))
            foreach ($message as $v) {
                if (is_array($v))
                    foreach ($v as $val) {
                        preg_match("/<error>(.*)<\/error>/is", $val, $matches);
                        if (!empty($matches[0]))
                            $this->_errors[] = $matches[0];
                    }
            }

        $message['Ошибки'] = $this->_errors;

        if (count($message['Ошибки']) > 0)
            $status = 2;
        else
            $status = 1;


        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['moysklad']['moysklad_log']);
        $log = array(
            'message_new' => serialize($message),
            'order_id_new' => $order_id,
            'date_new' => time(),
            'status_new' => $status
        );
        $PHPShopOrm->insert($log);
    }

    /**
     * Информация по складу
     * @param array $option
     * @return array
     */
    function stock($option=array()) {
        $mode='?';
        if(is_array($option))
            foreach($option as $k=>$v)
                $mode.=$k.'='.$v.'&';
        
        $result = $this->request('GET https://online.moysklad.ru/exchange/rest/stock/json'.$mode);
        //$this->_result[__FUNCTION__] = $result;
        $array = json_decode($result, true);
        
        if(is_array($array))
        return $array;
        else return $result;
    }

}

?>