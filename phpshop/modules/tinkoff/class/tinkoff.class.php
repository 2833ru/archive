<?php

include_once dirname(__FILE__) . '/tinkoffMerchantAPI.class.php';

class Tinkoff
{
    public $currency = 'RUB';
    public $customerEmail = '';
    public $settings = array();

    static public $tinkoffVats = [
        'none' => 'none',
        '0' => 'vat0',
        '10' => 'vat10',
        '18' => 'vat18',
    ];

    public function getPaymentUrl($obj, $value, $rout)
    {
        $PHPShopTinkoffArray = new PHPShopTinkoffArray();
        $this->settings = $PHPShopTinkoffArray->getArray();
        $this->customerEmail = $value['mail'];

        $requestData = array(
            'OrderId' => $obj->ouid,
            'Amount' => $obj->get('total') * 100,
            'DATA' => array(
                'Email' => $this->customerEmail,
                'Connection_type' => 'phpshop'
            ),
        );

        if ($this->settings['enabled_taxation']) {
            $requestData['Receipt'] = $this->getReceipt($obj, $value);

            if (count($requestData['Receipt']['Items']) > 99) {
                return array('error' => 'Превышено допустимое количество позиций в чеке');
            }
        }

        $tinkoff = new TinkoffMerchantAPI($this->settings['terminal'], $this->settings['secret_key'], $this->settings['gateway']);
        $request = $tinkoff->buildQuery('Init', $requestData);
        $request = json_decode($request);

        return isset($request->PaymentURL) ? array('url' => $request->PaymentURL) : array('error' => 'Запрос в Тинькофф Банк совершился неудачей');
    }

    function getReceipt($obj, $value)
    {
        global $PHPShopSystem;
        $receiptItems = array();
        $cart = $obj->PHPShopCart->getArray();

        foreach ($cart as $product) {
            /*������������ �� Windows-1251 � UTF-8*/
            $receiptItems[] = array(
                'Name' => mb_convert_encoding($product['name'], "UTF-8", "Windows-1251"),
                "Price" => $product['price'] * 100,
                "Quantity" => $product['num'],
                "Amount" => $product['price'] * $product['num'] * 100,
                "Tax" => self::getTinkoffVat($PHPShopSystem->objRow['nds']),
            );
        }

        if ($obj->delivery > 0) {
            $receiptItems[] = array(
                'Name' => mb_convert_encoding($obj->PHPShopDelivery->objRow['city'], "UTF-8", "Windows-1251"),
                "Price" => $obj->delivery * 100,
                "Quantity" => 1,
                "Amount" => $obj->delivery * 100,
                "Tax" => self::getTinkoffVat($obj->PHPShopDelivery->objRow['ofd_nds']),
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
     * @return bool
     */
    public function getCurrencyId()
    {
        $PHPShopValutaArray = new PHPShopValutaArray();
        $currencies = $PHPShopValutaArray->getArray();

        if (is_array($currencies)) {
            foreach ($currencies as $currency) {
                if ($currency['iso'] == $this->currency) {
                    return $currency['id'];
                }
            }
        }

        return false;
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