<?php

include_once dirname(__FILE__) . '/tinkoffMerchantAPI.class.php';

class Tinkoff
{
    public $currency = 'RUB';
    public $customerEmail = '';
    public $settings = array();

    public function __construct()
    {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['tinkoff']['tinkoff_system']);
        $this->settings = $PHPShopOrm->select();
    }

    static public $tinkoffVats = array(
        'none' => 'none',
        '0' => 'vat0',
        '10' => 'vat10',
        '18' => 'vat18',
        '20' => 'vat20',
    );

    public function getPaymentUrl($obj, $value)
    {
        $this->customerEmail = $value['mail'];

        $requestData = array(
            'OrderId' => $obj->ouid,
            'Amount' => $obj->tinkoff_total,
            'DATA' => array(
                'Email' => $this->customerEmail,
                'Connection_type' => 'phpshop'
            ),
        );

        if ($this->settings['enabled_taxation']) {
            $requestData['Receipt'] = $this->getReceipt($obj);

            if (count($requestData['Receipt']['Items']) > 99) {
                return array('error' => 'Превышено допустимое количество позиций в чеке');
            }
        }

        $tinkoff = new TinkoffMerchantAPI($this->settings['terminal'], $this->settings['secret_key'], $this->settings['gateway']);
        $request = $tinkoff->buildQuery('Init', $requestData);
        $request = json_decode($request);

        return isset($request->PaymentURL) ? array('url' => $request->PaymentURL) : array('error' => 'Запрос в Тинькофф Банк совершился неудачей');
    }

    function getReceipt($obj)
    {
        global $PHPShopSystem;
        $receiptItems = array();

        foreach ($obj->tinkoff_cart as $product) {

            // Скидка
            if($obj->discount > 0)
                $price = $product['price']  - ($product['price']  * $obj->discount  / 100);
            else $price = $product['price'];
            $receiptItems[] = array(
                'Name' => mb_convert_encoding($product['name'], "UTF-8", "Windows-1251"),
                "Price" => $price * 100,
                "Quantity" => $product['num'],
                "Amount" => $price * $product['num'] * 100,
                "PaymentMethod" => "full_prepayment",
                "PaymentObject" => "commodity",
                "Tax" => self::getTinkoffVat($PHPShopSystem->objRow['nds']),
            );
        }

        if ($obj->delivery > 0) {
            $receiptItems[] = array(
                'Name' => mb_convert_encoding('Доставка', "UTF-8", "Windows-1251"),
                "Price" => $obj->delivery * 100,
                "Quantity" => 1,
                "Amount" => $obj->delivery * 100,
                "PaymentMethod" => "full_prepayment",
                "PaymentObject" => "service",
                "Tax" => self::getTinkoffVat($obj->tinkoff_delivery_nds),
            );
        }

        $receipt = array(
            'Email' => $this->customerEmail,
            'Taxation' => $this->settings['taxation'],
            'Items' => $receiptItems,
        );

        return $receipt;
    }

    /**
     * @param $rate
     * @return mixed
     */
    public static function getTinkoffVat($rate)
    {
        global $PHPShopSystem;

        if ($PHPShopSystem->getParam('nds_enabled')) {
            return self::$tinkoffVats[$rate] ? self::$tinkoffVats[$rate] : self::$tinkoffVats['none'];
        }

        return self::$tinkoffVats['none'];
    }
}