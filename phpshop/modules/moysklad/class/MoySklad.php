<?php
/**
 * Библиотека связи с МойСклад
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 * @subpackage RestApi
 */
class MoySklad {

    var $request;

    const API_URL = 'https://online.moysklad.ru/api/remap/1.2/entity';
    const CREATE_ORDER_METHOD = 'customerorder';
    const GET_ORGANIZATIONS = 'organization';
    const GET_AGENT = 'counterparty';
    const CREATE_PRODUCT = 'product';
    const CREATE_VARIANT = 'variant';
    const CREATE_DELIVERY = 'service';
    const GET_CURRENCYS = 'currency';
    const GET_CHARACTER = 'variant/metadata';
    const CREATE_CHARACTER = 'variant/metadata/characteristics';
    const GET_PRICETYPE = '../context/companysettings/pricetype';

    public function __construct($order = array()) {

        $this->PHPShopOrm = new PHPShopOrm();
        $PHPShopSystem = new PHPShopSystem();
        $this->nds = $PHPShopSystem->getParam('nds');

        /*
         * Опции модуля
         */
        $this->PHPShopOrm->objBase = $GLOBALS['SysValue']['base']['moysklad']['moysklad_system'];
        $this->option = $this->PHPShopOrm->select();
        $this->token = $this->option['token'];

        /*
         * Код валюты
         */
        $this->iso = $PHPShopSystem->getDefaultValutaIso();

        /*
         * Исходное изображение
         */
        $this->image_source = $PHPShopSystem->ifSerilizeParam('admoption.image_save_source');

        /*
         * Заказ
         */
        if (isset($order['orders']) and ! empty($order['orders']))
            $order['orders'] = unserialize($order['orders']);
        $this->order = $order;
    }

    public function init() {
        $this->getProducts();
        $this->products();
        $this->customer();
        $this->delivery();
        $this->deal();
    }

    /*
     * Добавление услуги доставки.
     *
     * @return void
     */

    public function delivery() {
        $this->PHPShopOrm->objBase = $GLOBALS['SysValue']['base']['delivery'];
        $this->PHPShopOrm->_SQL = '';
        $delivery = $this->PHPShopOrm->select(array('*'), array('id=' => '"' . $this->order['orders']['Person']['dostavka_metod'] . '"'));
        $this->delivery_name = $delivery['city'];
        $this->nds_delivery = $delivery['ofd_nds'];

        if (empty($delivery['moysklad_delivery_id'])) {

            $fields = array(
                "name" => PHPShopString::win_utf8($delivery['city']),
                "vat" => intval($this->nds_delivery),
                "salePrices" => array(
                    "value" => floatval($this->order['orders']['Cart']['dostavka'] * 100),
                    "currency" => array(
                        "meta" => array(
                            "href" => self::API_URL . "/currency/" . $this->option['currency'],
                            "metadataHref" => "https://online.moysklad.ru/api/remap/1.2/entity/currency/metadata",
                            "type" => "currency",
                            "mediaType" => "application/json"
                        )
                    ),
                    "priceType" => array(
                        "meta" => array(
                            "href" => self::API_URL . "../context/companysettings/pricetype/" . $this->option['pricetype'],
                            "type" => "pricetype",
                            "mediaType" => "application/json"
                        )
                    )
                )
            );

            $result = $this->post(self::CREATE_DELIVERY, $fields);

            if (!empty($result['id'])) {
                $this->PHPShopOrm->objBase = $GLOBALS['SysValue']['base']['delivery'];
                $this->PHPShopOrm->_SQL = '';
                $this->PHPShopOrm->update(array('moysklad_delivery_id_new' => "$result[id]"), array('id=' => '"' . $this->order['orders']['Person']['dostavka_metod'] . '"'));
                $this->delivery_id = $result['id'];
            } else {
                $this->log(array('parameters' => $fields, 'response' => $result), $this->order['uid'], 'Ошибка создания товара доставки', 'createDelivery', 'error');
            }
        } else
            $this->delivery_id = $delivery['moysklad_delivery_id'];
    }

    public function getProducts() {
        $product_id = array();
        foreach ($this->order['orders']['Cart']['cart'] as $cart)
            $product_id[] = $cart['id'];

        $this->PHPShopOrm->_SQL = '';
        $query = $this->PHPShopOrm->query("SELECT * FROM " . $GLOBALS['SysValue']['base']['products'] . " WHERE `id` IN ('" . implode("', '", $product_id) . "')");

        while ($row = $query->fetch_assoc()) {
            $this->products[$row['id']] = $row;
        }
    }

    /**
     * Изображения товаров
     */
    public function getPicture($pictureLink, $sourceSetting = false) {

        if (!empty($pictureLink)) {
            if (strpos('http:', $pictureLink) === false or strpos('https:', $pictureLink) === false) {

                if (!empty($sourceSetting))
                    $pictureLink = str_replace(".", "_big.", $pictureLink);

                $link = 'http://' . $_SERVER['SERVER_NAME'] . $pictureLink;
            } else
                $link = $pictureLink;

            $pictureLinkParts = explode('/', $link);

            return array(
                array(
                    'filename' => array_pop($pictureLinkParts),
                    'content' => base64_encode(file_get_contents($link))
                )
            );
        }
    }

    /*
     * Добавление характеристик.
     */

    public function getCharacteristic($categoryId) {

        $this->PHPShopOrm->objBase = $GLOBALS['SysValue']['base']['categories'];
        $this->PHPShopOrm->_SQL = '';
        $parent_titles = $this->PHPShopOrm->select(array('parent_title'), array('id=' => '"' . $categoryId . '"'));

        $this->PHPShopOrm->objBase = $GLOBALS['SysValue']['base']['parent_name'];
        $this->PHPShopOrm->_SQL = '';
        $sort = $this->PHPShopOrm->select(array('*'), array('id=' => '"' . $parent_titles['parent_title'] . '"'));

        // Поиск созданной характеристики
        if (empty($sort['moysklad_char_id']) or empty($sort['moysklad_char2_id'])) {
            $characteristics = $this->get(self::GET_CHARACTER);
            if (is_array($characteristics))
                foreach ($characteristics['rows'] as $characteristic) {

                    // Размер
                    if ($sort['name'] == $characteristic['name']) {
                        $sort['moysklad_char_id'] = $characteristic['id'];

                        // Обновление данных в базе
                        $this->PHPShopOrm->objBase = $GLOBALS['SysValue']['base']['parent_name'];
                        $this->PHPShopOrm->_SQL = '';
                        $this->PHPShopOrm->update(array('moysklad_char_id_new' => $sort['moysklad_char_id']), array('id=' => '"' . $sort['id'] . '"'));
                    }

                    // Цвет
                    if ($sort['color'] == $characteristic['name']) {
                        $sort['moysklad_char2_id'] = $characteristic['id'];

                        // Обновление данных в базе
                        $this->PHPShopOrm->objBase = $GLOBALS['SysValue']['base']['parent_name'];
                        $this->PHPShopOrm->_SQL = '';
                        $this->PHPShopOrm->update(array('moysklad_char_id2_new' => $sort['moysklad_char_id']), array('id=' => '"' . $sort['id'] . '"'));
                    }
                }
        }

        // Создание новой характеристики Размер
        if (empty($sort['moysklad_char_id'])) {

            $fields = array(
                "name" => PHPShopString::win_utf8($sort['name']),
            );

            $result = $this->post(self::CREATE_CHARACTER, $fields);

            if (!empty($result['id'])) {
                $this->PHPShopOrm->objBase = $GLOBALS['SysValue']['base']['parent_name'];
                $this->PHPShopOrm->_SQL = '';
                $this->PHPShopOrm->update(array('moysklad_char_id_new' => "$result[id]"), array('id=' => '"' . $sort['id'] . '"'));
                $sort['moysklad_char_id'] = $result['id'];
            } else {
                $this->log(array('parameters' => $fields, 'response' => $result), $this->order['uid'], 'Ошибка создания 1 характеристики товара', 'createCharacter', 'error');
            }
        }

        // Создание новой характеристики Цвет
        if (empty($sort['moysklad_char2_id'])) {

            $fields = array(
                "name" => PHPShopString::win_utf8($sort['color']),
            );

            $result = $this->post(self::CREATE_CHARACTER, $fields);

            if (!empty($result['id'])) {
                $this->PHPShopOrm->objBase = $GLOBALS['SysValue']['base']['parent_name'];
                $this->PHPShopOrm->_SQL = '';
                $this->PHPShopOrm->update(array('moysklad_char2_id_new' => "$result[id]"), array('id=' => '"' . $sort['id'] . '"'));
                $sort['moysklad_char2_id'] = $result['id'];
            } else {
                $this->log(array('parameters' => $fields, 'response' => $result), $this->order['uid'], 'Ошибка создания 2 характеристики товара', 'createCharacter', 'error');
            }
        }

        return $sort;
    }

    /*
     * Синхронизация товаров.
     * @return void
     */

    public function products() {

        foreach ($this->products as $product) {

            // Обычный товар
            if (empty($product['parent_enabled'])) {
                $method = self::CREATE_PRODUCT;
                $fields = array(
                    "name" => PHPShopString::win_utf8($product['name']),
                    "price" => floatval($this->order['orders']['Cart']['cart'][$product['id']]['price'] * 100),
                    "article" => $product['uid'],
                    "description" => PHPShopString::win_utf8($product['description']),
                    "weight" => floatval($product['weight']),
                    "vat" => intval($this->nds),
                    "salePrices" => array(
                        array(
                            "value" => floatval($this->order['orders']['Cart']['cart'][$product['id']]['price'] * 100),
                            "currency" => array(
                                "meta" => array(
                                    "href" => self::API_URL . "/currency/" . $this->option['currency'],
                                    "metadataHref" => "https://online.moysklad.ru/api/remap/1.2/entity/currency/metadata",
                                    "type" => "currency",
                                    "mediaType" => "application/json"
                                )
                            ),
                            "priceType" => array(
                                "meta" => array(
                                    "href" => "https://online.moysklad.ru/api/remap/1.2/context/companysettings/pricetype/" . $this->option['pricetype'],
                                    "type" => "pricetype",
                                    "mediaType" => "application/json"
                                )
                            )
                        )
                    ),
                    "images" => $this->getPicture($product['pic_big'])
                );
            }
            // Подтип
            else {

                // Изображение родительского товара подтипа (если у подтипа нет изображения)
                if (empty($product['pic_big'])) {
                    $product['pic_big'] = $this->order['orders']['Cart']['cart'][$product['id']]['pic_small'];
                }

                $method = self::CREATE_VARIANT;
                $PHPShopProduct = new PHPShopProduct($this->order['orders']['Cart']['cart'][$product['id']]['parent']);
                $fields = array(
                    "name" => PHPShopString::win_utf8($product['parent'] . ' ' . $product['parent2']),
                    "article" => $product['uid'],
                    "weight" => floatval($product['weight']),
                    "vat" => intval($this->nds),
                    "product" => array(
                        "meta" => array(
                            "href" => self::API_URL . "/product/" . $PHPShopProduct->getValue('moysklad_product_id'),
                            "metadataHref" => self::API_URL . "/product/metadata",
                            "type" => "product",
                            "mediaType" => "application/json"
                        )
                    ),
                    "salePrices" => array(
                        array(
                            "value" => floatval($this->order['orders']['Cart']['cart'][$product['id']]['price'] * 100),
                            "currency" => array(
                                "meta" => array(
                                    "href" => self::API_URL . "/currency/" . $this->option['currency'],
                                    "metadataHref" => "https://online.moysklad.ru/api/remap/1.2/entity/currency/metadata",
                                    "type" => "currency",
                                    "mediaType" => "application/json"
                                )
                            ),
                            "priceType" => array(
                                "meta" => array(
                                    "href" => "https://online.moysklad.ru/api/remap/1.2/context/companysettings/pricetype/" . $this->option['pricetype'],
                                    "type" => "pricetype",
                                    "mediaType" => "application/json"
                                )
                            )
                        )
                    ),
                    "images" => $this->getPicture($product['pic_big'])
                );

                // Создание характеристики
                $sort = $this->getCharacteristic($product['category']);
                if (is_array($sort)) {

                    // Размер
                    $fields['characteristics'][] = array(
                        "id" => $sort['moysklad_char_id'],
                        "value" => PHPShopString::win_utf8($product['parent'])
                    );

                    // Цвет
                    if (!empty($product['parent2'])) {
                        $fields['characteristics'][] = array(
                            "id" => $sort['moysklad_char2_id'],
                            "value" => PHPShopString::win_utf8($product['parent2'])
                        );
                    }
                }
            }

            // Если товар еще не добавлялся в CRM - добавляем
            if (empty($product['moysklad_product_id'])) {

                $result = $this->post($method, $fields);

                if (!empty($result['id'])) {
                    $this->PHPShopOrm->objBase = $GLOBALS['SysValue']['base']['products'];
                    $this->PHPShopOrm->_SQL = '';
                    $this->PHPShopOrm->update(array('moysklad_product_id_new' => "$result[id]"), array('id=' => "$product[id]"));
                    $this->products[$product['id']]['moysklad_product_id'] = $result['id'];

                    $this->log(array('parameters' => $fields, 'response' => $result), $this->order['uid'], 'Успешное создание товара', 'createProduct', 'success');
                } else {
                    $this->log(array('parameters' => $fields, 'response' => $result), $this->order['uid'], 'Ошибка создания товара', 'createProduct', 'error');
                }
            }
        }
    }

    /*
     * Синхронизация покупателя.
     *
     * @return void
     */

    public function customer() {
        $this->PHPShopOrm->objBase = $GLOBALS['SysValue']['base']['shopusers'];
        $this->PHPShopOrm->_SQL = '';
        $moysklad_client_id = $this->PHPShopOrm->select(array('moysklad_client_id'), array('id=' => '"' . $this->order['user'] . '"'));

        if (!empty($moysklad_client_id['moysklad_client_id']))
            $this->client_id = $moysklad_client_id['moysklad_client_id'];
        else {
            if (!empty($this->order['org_name']))
                $this->addCompany();
            else {
                $this->addContact();
            }
        }
    }

    /*
     * Добавление покупателя
     *
     * @return void
     */

    public function addContact() {
        $fields = array(
            'name' => PHPShopString::win_utf8($this->order['fio']),
        );

        if (!empty($this->order['tel'])) {
            $fields['phone'] = $this->order['tel'];
        }

        if (!empty($this->order['orders']['Person']['mail'])) {
            $fields['email'] = $this->order['orders']['Person']['mail'];
        }
        $result = $this->post(self::GET_AGENT, $fields);
        if (!empty($result['id'])) {
            $this->PHPShopOrm->objBase = $GLOBALS['SysValue']['base']['shopusers'];
            $this->PHPShopOrm->_SQL = '';
            $this->PHPShopOrm->update(array('moysklad_client_id_new' => "$result[id]"), array('id=' => '"' . $this->order['user'] . '"'));
            $this->client_id = $result['id'];
        } else {
            $this->log(array('parameters' => $fields, 'response' => $result), $this->order['uid'], 'Ошибка создания покупателя', 'addContact', 'error');
        }
    }

    /*
     * Добавление компании.
     *
     * @return void
     */

    public function addCompany() {
        $fields = array(
            'name' => PHPShopString::win_utf8($this->order['org_name']),
            'inn' => PHPShopString::win_utf8($this->order['org_inn']),
            'kpp' => PHPShopString::win_utf8($this->order['org_kpp']),
            'actualAddress' => PHPShopString::win_utf8($this->order['org_fakt_adress']),
            'legalAddress' => PHPShopString::win_utf8($this->order['org_yur_adress']),
        );

        if (!empty($this->order['tel'])) {
            $fields['phone'] = $this->order['tel'];
        }

        if (!empty($this->order['orders']['Person']['mail'])) {
            $fields['email'] = $this->order['orders']['Person']['mail'];
        }
        $result = $this->post(self::GET_AGENT, $fields);
        if (!empty($result['id'])) {
            $this->PHPShopOrm->objBase = $GLOBALS['SysValue']['base']['shopusers'];
            $this->PHPShopOrm->_SQL = '';
            $this->PHPShopOrm->update(array('moysklad_client_id_new' => "$result[id]"), array('id=' => '"' . $this->order['user'] . '"'));
            $this->client_id = $result['id'];
        } else {
            $this->log(array('parameters' => $fields, 'response' => $result), $this->order['uid'], 'Ошибка создания компании', 'createCompany', 'error');
        }
    }

    /*
     * Добавление сделки.
     *
     * @return void
     */

    public function deal() {

        if (empty($this->order['moysklad_deal_id'])) {

            $this->PHPShopOrm->objBase = $GLOBALS['SysValue']['base']['payment_systems'];
            $this->PHPShopOrm->_SQL = '';
            $payment_method = $this->PHPShopOrm->select(array('name'), array('id=' => '"' . $this->order['orders']['Person']['order_metod'] . '"'));

            if (!empty($this->order['city']))
                $city = $this->order['city'] . ', ';
            else
                $city = ' ';

            if (!empty($this->order['street']))
                $adress = '. Адрес доставки:' . $city . $this->order['street'];
            else
                $adress = '.';
            if (!empty($this->order['house']))
                $adress .= ' ' . $this->order['house'];

            if (!empty($this->order['flat']))
                $adress .= ', кв. ' . $this->order['flat'];

            if (!empty($this->order['dop_info']))
                $comment = '. Комментарий: ' . $this->order['dop_info'];
            else
                $comment = '.';

            $fields = array(
                'name' => $this->order['uid'],
                'organization' => array(
                    'meta' => array(
                        'href' => self::API_URL . "/" . $this->option['organization'],
                        'type' => 'organization',
                        "mediaType" => "application/json"
                    )
                ),
                'agent' => array(
                    'meta' => array(
                        'href' => self::API_URL . "/" . $this->client_id,
                        'type' => 'counterparty',
                        "mediaType" => "application/json"
                    )
                )
            );

            // Товары
            $rows = array();
            foreach ($this->products as $product) {

                // Товар
                if (empty($this->order['orders']['Cart']['cart'][$product['id']]['parent'])) {
                    $rows[] = array(
                        "name" => PHPShopString::win_utf8($this->order['orders']['Cart']['cart'][$product['id']]['name']),
                        "quantity" => $this->order['orders']['Cart']['cart'][$product['id']]['num'],
                        "price" => floatval($this->order['orders']['Cart']['cart'][$product['id']]['price'] * 100),
                        "discount" => 0,
                        "vat" => intval($this->nds),
                        "assortment" => array(
                            "meta" => array(
                                "href" => self::API_URL . "/product/" . $product['moysklad_product_id'],
                                "type" => "product",
                                "mediaType" => "application/json"
                            )
                        ),
                    );
                }
                // Подтип
                else {
                    $rows[] = array(
                        "name" => PHPShopString::win_utf8($this->order['orders']['Cart']['cart'][$product['id']]['name']),
                        "quantity" => $this->order['orders']['Cart']['cart'][$product['id']]['num'],
                        "price" => floatval($this->order['orders']['Cart']['cart'][$product['id']]['price'] * 100),
                        "discount" => 0,
                        "vat" => intval($this->nds),
                        "assortment" => array(
                            "meta" => array(
                                "href" => self::API_URL . "/variant/" . $product['moysklad_product_id'],
                                "metadataHref" => self::API_URL . "/variant/metadata",
                                "type" => "variant",
                                "mediaType" => "application/json"
                            )
                        ),
                    );
                }
            }

            // Доставка
            $rows[] = array(
                "name" => PHPShopString::win_utf8($this->delivery_name),
                "price" => floatval($this->order['orders']['Cart']['dostavka'] * 100),
                "quantity" => 1,
                "discount" => 0,
                "vat" => intval($this->nds_delivery),
                "assortment" => array(
                    "meta" => array(
                        "href" => self::API_URL . "/service/" . $this->delivery_id,
                        "type" => "service",
                        "mediaType" => "application/json"
                    )
                ),
            );

            $fields['positions'] = $rows;

            // Запись заказа
            $result = $this->post(self::CREATE_ORDER_METHOD, $fields);



            if (!empty($result['id'])) {

                $this->log(array(
                    'parameters' => $fields,
                    'response' => $result,
                    'products' => $fields['positions']
                        ), $this->order['id'], 'Успешная передача заказа', 'createDeal', 'success');

                $orm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
                $orm->update(array('moysklad_deal_id_new' => $result['id']), array('uid' => "='" . $this->order['uid'] . "'"));
            } else {
                $this->log(array('parameters' => $fields, 'response' => $result), $this->order['id'], 'Ошибка передачи заказа', 'createDeal', 'error');
            }
        }
    }

    /**
     * Выбор организации
     */
    public function getOrganizations($currentOrganization) {
        $organizations = $this->get(self::GET_ORGANIZATIONS);
        $result = array();
        if (is_array($organizations['rows']))
            foreach ($organizations['rows'] as $organization) {
                $result[] = array($organization['name'], $organization['id'], $currentOrganization);
            }

        return $result;
    }

    /**
     * Выбор валюты
     */
    public function getCurrencys($currentCurrency) {
        $currencys = $this->get(self::GET_CURRENCYS);
        $result = array();
        if (is_array($currencys['rows']))
            foreach ($currencys['rows'] as $currency) {
                $result[] = array($currency['name'], $currency['id'], $currentCurrency);
            }

        return $result;
    }

    /**
     * Выбор типов цен
     */
    public function getPricetype($current) {
        $data = $this->get(self::GET_PRICETYPE);
        $result = array();
        if (is_array($data))
            foreach ($data['rows'] as $row) {
                $result[] = array($row['name'], $row['id'], $current);
            }

        return $result;
    }

    /**
     * @param $method
     * @param array $properties
     * @return array
     */
    public function post($method, $properties = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . '/' . $method);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($properties));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json',
        ));

        return $this->request($ch, $method);
    }

    /**
     * @param $method
     * @return array
     */
    public function get($method) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . '/' . $method);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json',
        ));

        return $this->request($ch, $method);
    }

    /**
     * @param $ch
     * @return array
     */
    public function request($ch, $method) {
        $result = json_decode(curl_exec($ch), true);
        $result = $this->utf8ToWindows1251($result, $method);
        return $result;
    }

    /**
     * Запись лога
     * @param array $message содержание запроса в ту или иную сторону
     * @param string $order_id номер заказа
     * @param string $status статус отправки
     * @param string $type request
     */
    public function log($message, $order_id, $status, $type, $status_code = 'succes') {

        $PHPShopOrm = new PHPShopOrm('phpshop_modules_moysklad_log');
        $id = explode("-", $order_id);

        $log = array(
            'message_new' => serialize($message),
            'order_id_new' => $id[0],
            'status_new' => $status,
            'type_new' => $type,
            'date_new' => time(),
            'status_code_new' => $status_code
        );
        $PHPShopOrm->insert($log);
    }

    public function utf8ToWindows1251($data, $method) {

        // errors
        if (isset($data['errors'])) {
            foreach ($data['errors'] as $errorKey => $error) {
                $data['errors'][$errorKey]['error'] = iconv('utf-8', 'windows-1251', $error['error']);
            }
        }

        switch ($method) {

            // Продавец
            case self::GET_ORGANIZATIONS: {

                    if (is_array($data['rows']))
                        foreach ($data['rows'] as $key => $organization) {
                            $data['rows'][$key]['name'] = iconv('UTF-8', 'Windows-1251', $organization['name']);
                        }
                    break;
                }

            // Валюты
            case self::GET_CURRENCYS: {

                    if (is_array($data['rows']))
                        foreach ($data['rows'] as $key => $currency) {
                            $data['rows'][$key]['name'] = iconv('UTF-8', 'Windows-1251', $currency['name']);
                        }
                    break;
                }

            // Валюты
            case self::GET_PRICETYPE: {
                    if (is_array($data))
                        foreach ($data as $key => $price) {
                            $data['rows'][$key]['name'] = iconv('UTF-8', 'Windows-1251', $price['name']);
                            $data['rows'][$key]['id'] = $price['id'];
                        }
                    break;
                }

            // Характеристики
            case self::GET_CHARACTER: {
                    if (is_array($data['characteristics']))
                        foreach ($data['characteristics'] as $key => $sort) {
                            $data['rows'][$key]['name'] = iconv('UTF-8', 'Windows-1251', $sort['name']);
                            $data['rows'][$key]['id'] = $sort['id'];
                        }
                    break;
                }
        }

        return $data;
    }

}
