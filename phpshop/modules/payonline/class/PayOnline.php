<?php

/**
 * Class PayOnline
 */
class PayOnline {

    const PAYMENT_ID = 10033;
    const CURRENCY = 'RUB';
    const FORM_ACTION = 'https://secure.payonlinesystem.com/ru/payment/';

    private $orderId;
    private $amount;

    public function __construct()
    {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['payonline']['payonline_system']);

        /*
         * Опции модуля
         */
        $this->option = $PHPShopOrm->select();
    }

    /**
     * Генерация формы
     *
     * @return string
     */
    public function getForm()
    {
        $payment_forma = PHPShopText::setInput('hidden', 'OrderId', $this->getOrderId(), false);
        $payment_forma .= PHPShopText::setInput('hidden', 'Amount', $this->getAmount(), false);
        $payment_forma .= PHPShopText::setInput('hidden', 'MerchantId', $this->option['merchant_id'], false);
        $payment_forma .= PHPShopText::setInput('hidden', 'Currency', self::CURRENCY, false);
        $payment_forma .= PHPShopText::setInput('hidden', 'SecurityKey', $this->generateSecurityKey(), false);
        $payment_forma .= PHPShopText::setInput('hidden', 'FailUrl', $this->getFailUrl(), false);
        $payment_forma .= PHPShopText::setInput('hidden', 'ReturnUrl', $this->getSuccessUrl(), false);
        $payment_forma .= '<p>' . $this->getOffer() . '</p>';

        $payment_forma .=PHPShopText::setInput('submit', 'send', $this->option['title_payment'], $float = "left; margin-left:10px;", 250);

        return $payment_forma;
    }

    /**
     * @param $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    private function generateSecurityKey()
    {
        return md5('MerchantId=' . $this->option['merchant_id'] . '&OrderId=' . $this->getOrderId() . '&Amount=' . $this->getAmount() . '&Currency=' . self::CURRENCY . '&PrivateSecurityKey=' . $this->option['key']);
    }

    /**
     * @return string
     */
    private function getFailUrl()
    {
        if(!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }

        return $protocol . $_SERVER['SERVER_NAME'] . '/success/?status=fail';
    }

    /**
     * @return string
     */
    private function getSuccessUrl()
    {
        if(!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }

        return $protocol . $_SERVER['SERVER_NAME'] . '/success/?status=success&Order_ID=' . $this->getOrderId();
    }

    /**
     * @return string
     */
    private function getOrderDescription()
    {
        global $PHPShopSystem;

        return PHPShopString::win_utf8($PHPShopSystem->getName() . ' оплата заказа ' . $this->getOrderId());
    }

    /**
     * @return string
     */
    public function getOffer()
    {
        if(!$this->option['page_id']) {
            return '';
        }

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page']);
        $page = $PHPShopOrm->select(array('link'), array('id=' => '"' . $this->option['page_id'] . '"'));

        return '<label style="padding-top: 10px;">
                    <input type="checkbox" value="on" name="offer" class="req" required="required" checked="">
                    Нажимая на кнопку, вы подтверждаете, что ознакомились с 
                    <a href="/page/' . $page['link']. '.html" target="_blank" class="payonline-link">Публичной офертой.</a>
               </label><style>.payonline-link {color: #4a7eb7;} .payonline-link:hover, .payonline-link:focus  {text-decoration: underline;}</style>';
    }

    /**
     * Запись лога
     * @param array $message содержание запроса в ту или иную сторону
     * @param string $order_id номер заказа
     * @param string $status статус оплаты
     */
    public function log($message, $order_id, $status, $type)
    {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['payonline']['payonline_log']);

        $log = array(
            'message_new'  => serialize($message),
            'order_id_new' => $order_id,
            'status_new'   => $status,
            'type_new'     => $type,
            'date_new'     => time()
        );

        $PHPShopOrm->insert($log);
    }
}