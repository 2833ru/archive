<?php

// Настройки модуля
PHPShopObj::loadClass("array");

class PHPShopYandexkassaArray extends PHPShopArray {

    function __construct() {
        $this->objType = 3;
        $this->objBase = $GLOBALS['SysValue']['base']['yandexkassa']['yandexkassa_system'];
        parent::__construct("status", "title", 'title_end', 'merchant_id', 'merchant_sig', 'pay_variants', 'merchant_scid', 'test');
    }

    function get_pay_variants_array($arr = null, $forDoneCore = null) {
        $pay_variants_array['PC'] = array('Оплата из кошелька в Яндекс.Деньгах', 'PC', '');
        $pay_variants_array['AC'] = array('Оплата с произвольной банковской карты', 'AC', '');
        $pay_variants_array['MC'] = array('Платеж со счета мобильного телефона', 'MC', '');
        $pay_variants_array['GP'] = array('Оплата наличными через кассы и терминалы', 'GP', '');
        $pay_variants_array['WM'] = array('Оплата из кошелька в системе WebMoney', 'WM', '');
        $pay_variants_array['SB'] = array('Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн', 'SB', '');
//        $pay_variants_array['MP'] = array('Оплата через мобильный терминал (mPOS).', 'MP', '');
        $pay_variants_array['AB'] = array('Оплата через Альфа-Клик', 'AB', '');
        $pay_variants_array['МА'] = array('Оплата через MasterPass', 'МА', '');
        $pay_variants_array['PB'] = array('Оплата через Промсвязьбанк', 'PB', '');
        $pay_variants_array['QW'] = array('QIWI Wallet', 'QW', '');
        $pay_variants_array['KV'] = array('КупиВкредит', 'KV', '');

        if (is_array($arr))
            foreach ($arr as $key => $value) {
                $pay_variants_array[$value][2] = $value;
                if ($forDoneCore) {
                    $pay_variants_arrayDone[$value][0] = $pay_variants_array[$value][0];
                    $pay_variants_arrayDone[$value][1] = $pay_variants_array[$value][1];
                    $pay_variants_arrayDone[$value][2] = $pay_variants_array[$value][2];
                }
            }
        if ($forDoneCore)
            return $pay_variants_arrayDone;
        return $pay_variants_array;
    }

    /**
     * Запись лога
     * @param array $message содержание запроса в ту или иную сторону
     * @param string $order_id номер заказа
     * @param string $status статус оплаты
     * @param string $type request
     */
    function log($message, $order_id, $status, $type) {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['yandexkassa']['yandexkassa_log']);
        $log = array(
            'message_new' => serialize($message),
            'order_id_new' => $order_id,
            'status_new' => $status,
            'type_new' => $type,
            'date_new' => time()
        );
        $PHPShopOrm->insert($log);
    }

}

?>
