<?php

/**
 * Библиотека работы с Ozon Seller API
 * @author PHPShop Software
 * @version 1.6
 * @package PHPShopModules
 * @todo https://docs.ozon.ru/api/seller/#tag/Environment
 */
class OzonSeller {

    const GET_PARENT_TREE = '/v2/category/tree';
    const GET_TREE_ATTRIBUTE = '/v3/category/attribute';
    const GET_ATTRIBUTE_VALUES = '/v2/category/attribute/values';
    const API_URL = 'https://api-seller.ozon.ru';
    const IMPORT_PRODUCT = '/v2/product/import';
    const IMPORT_PRODUCT_INFO = '/v1/product/import/info';
    const GET_FBS_ORDER_LIST = '/v3/posting/fbs/list';
    const GET_FBS_ORDER = '/v3/posting/fbs/get';
    const GET_FBO_ORDER_LIST = '/v2/posting/fbo/list';
    const GET_FBO_ORDER = '/v2/posting/fbo/get';
    const GET_WAREHOUSE_LIST = '/v1/warehouse/list';
    const UPDATE_PRODUCT_STOCKS = '/v2/products/stocks';
    const GET_PRODUCT_LIST = '/v2/product/list';
    const GET_PRODUCT = '/v2/product/info';
    const GET_PRODUCT_DESCRIPTION = "/v1/product/info/description";
    const GET_PRODUCT_ATTRIBUTES = '/v3/products/info/attributes';
    const GET_PRODUCT_PRICES = '/v4/product/info/prices';

    public $api_key;
    public $client_id;

    public function __construct() {
        global $PHPShopSystem;

        $PHPShopOrm = new PHPShopOrm('phpshop_modules_ozonseller_system');

        $this->options = $PHPShopOrm->select();
        $this->client_id = $this->options['client_id'];
        $this->api_key = $this->options['token'];

        $this->vat = $PHPShopSystem->getParam('nds') / 100;
        $this->image_save_source = $PHPShopSystem->ifSerilizeParam('admoption.image_save_source');
        $this->status = $this->options['status'];
        $this->fee_type = $this->options['fee_type'];
        $this->fee = $this->options['fee'];
        $this->price = $this->options['price'];
        $this->type = $this->options['type'];
        $this->warehouse_name = $this->options['warehouse'];
        $this->warehouse_id = $this->options['warehouse_id'];

        $this->status_list = [
            'acceptance_in_progress' => 'идёт приёмка',
            'awaiting_approve' => 'ожидает подтверждения',
            'awaiting_packaging' => 'ожидает упаковки',
            'awaiting_deliver' => 'ожидает отгрузки',
            'arbitration' => 'арбитраж',
            'client_arbitration' => 'клиентский арбитраж доставки',
            'delivering' => 'доставляется',
            'driver_pickup' => 'у водителя',
            'delivered' => 'доставлено',
            'cancelled' => 'отменено'
        ];
    }

    /**
     * Изменение остатка на складе
     */
    public function setProductStock($product) {

        // Если нет OZON ID
        if (empty($product['export_ozon_id'])) {
            $info = $this->sendProductsInfo($product)['result']['items'][0];
        }
        else {
            $info['offer_id']=$product['uid'];
            $info['product_id']=$product['export_ozon_id'];
        }

        if (empty($product['enabled']))
            $product['items'] = 0;

        $params['stocks'][] = [
            'offer_id' => $info['offer_id'],
            'product_id' => $info['product_id'],
            'stock' => (int) $product['items'],
            'warehouse_id' => $this->warehouse_id
        ];

        $result = $this->request(self::UPDATE_PRODUCT_STOCKS, $params);

        // Журнал
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, $product['id'], self::UPDATE_PRODUCT_STOCKS);

        return $result;
    }

    /**
     * Получение списка складов
     */
    public function getWarehouse() {

        $result = $this->request(self::GET_WAREHOUSE_LIST, ['name' => $this->warehouse_name]);

        // Журнал
        $log['params'] = [];
        $log['result'] = $result;

        $this->log($log, null, self::GET_WAREHOUSE_LIST);

        return $result;
    }

    /**
     * Преобразование даты
     */
    public function getTime($date, $full = true) {
        $d = explode('T', $date);
        $t = explode('Z', $d[1]);

        if ($full)
            return $d[0] . ' ' . $t[0];
        else
            return $d[0];
    }

    /**
     * Данные FBS заказа
     */
    public function getOrderFbo($num) {

        $params = [
            'posting_number' => $num,
        ];

        $result = $this->request(self::GET_FBO_ORDER, $params);

        // Журнал
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, $num, self::GET_FBO_ORDER);

        return $result;
    }

    /**
     * Данные FBS заказа
     */
    public function getOrderFbs($num) {

        $params = [
            'posting_number' => $num,
        ];

        $result = $this->request(self::GET_FBS_ORDER, $params);

        // Журнал
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, $num, self::GET_FBS_ORDER);

        return $result;
    }

    /**
     *  Заказ уже загружен?
     */
    public function checkOrderBase($id) {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
        $data = $PHPShopOrm->getOne(['id'], ['ozonseller_order_data' => '="' . $id . '"']);
        if (!empty($data['id']))
            return $data['id'];
    }

    /**
     *  Статус заказа
     */
    public function getStatus($name) {

        return $this->status_list[$name];
    }

    /**
     * Цены товара из Ozon
     */
    public function getProductPrices($product_id) {

        $params = [
            'filter' => [
                'product_id' => [$product_id],
                'visibility' => 'ALL',
            ],
            'limit' => 1,
        ];

        $result = $this->request(self::GET_PRODUCT_PRICES, $params);

        // Журнал
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, $product_id, self::GET_PRODUCT_PRICES);

        return $result;
    }

    /**
     * Атрибуты товара из Ozon
     */
    public function getProductAttribures($product_id) {

        $params = [
            'filter' => [
                'product_id' => [$product_id],
                'visibility' => 'ALL',
            ],
            'limit' => 1,
        ];

        $result = $this->request(self::GET_PRODUCT_ATTRIBUTES, $params);

        // Журнал
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, $product_id, self::GET_PRODUCT_ATTRIBUTES);

        return $result;
    }

    /**
     * Описание товара из Ozon
     */
    public function getProductDescription($product_id) {

        $params = [
            'product_id' => $product_id,
        ];

        $result = $this->request(self::GET_PRODUCT_DESCRIPTION, $params);

        // Журнал
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, $product_id, self::GET_PRODUCT_DESCRIPTION);

        return $result;
    }

    /**
     * Данные товаров из Ozon
     */
    public function getProduct($product_id) {

        $params = [
            'product_id' => $product_id,
        ];

        $result = $this->request(self::GET_PRODUCT, $params);

        // Журнал
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, $product_id, self::GET_PRODUCT);

        return $result;
    }

    /**
     *  Список товаров из Ozon
     */
    public function getProductList($visibility = "ALL", $offer_id = null, $product_id = null, $limit = null) {

        if (empty($limit))
            $limit = 100;

        $params = [
            'filter' => [
                'visibility' => $visibility,
            ],
            'limit' => $limit,
        ];

        if (!empty($offer_id))
            $params['filter']['offer_id'] = [$offer_id];

        if (!empty($product_id))
            $params['filter']['product_id'] = [$product_id];



        $result = $this->request(self::GET_PRODUCT_LIST, $params);

        // Журнал
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, 0, self::GET_PRODUCT_LIST);

        return $result;
    }

    /**
     *  Список заказов FBS
     */
    public function getOrderListFbs($date1, $date2, $status) {

        $params = [
            'dir' => 'desc',
            'filter' => [
                'since' => $date1 . 'T' . date('h:m:s') . 'Z',
                'status' => $status,
                'to' => $date2 . 'T' . date('h:m:s') . 'Z',
            ],
            'limit' => 100,
            'offset' => 0,
        ];

        $result = $this->request(self::GET_FBS_ORDER_LIST, $params);

        // Журнал
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, 0, self::GET_FBS_ORDER_LIST);

        return $result;
    }

    /**
     *  Список заказов FBO
     */
    public function getOrderListFbo($date1, $date2, $status) {

        $params = [
            'dir' => 'desc',
            'filter' => [
                'since' => $date1 . 'T' . date('h:m:s') . 'Z',
                'status' => $status,
                'to' => $date2 . 'T' . date('h:m:s') . 'Z',
            ],
            'limit' => 100,
            'offset' => 0,
        ];

        $result = $this->request(self::GET_FBO_ORDER_LIST, $params);

        // Журнал
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, 0, self::GET_FBO_ORDER_LIST);

        return $result;
    }

    /**
     * Запись в журнал
     */
    public function log($message, $id, $type) {
        $PHPShopOrm = new PHPShopOrm('phpshop_modules_ozonseller_log');

        $log = array(
            'message_new' => serialize($message),
            'order_id_new' => $id,
            'type_new' => $type,
            'date_new' => time()
        );

        $PHPShopOrm->insert($log);
    }

    /**
     * Запись в журнал JSON
     */
    public function log_json($message, $id, $type) {
        $PHPShopOrm = new PHPShopOrm('phpshop_modules_ozonseller_log');

        $log = array(
            'message_new' => $message,
            'order_id_new' => $id,
            'type_new' => $type,
            'date_new' => time()
        );

        $PHPShopOrm->insert($log);
    }

    private function getAttributes($product) {

        $category = new PHPShopCategory((int) $product['category']);
        $category_ozonseller = $category->getParam('category_ozonseller');

        $sort = $category->unserializeParam('sort');
        $sortCat = $sortValue = null;
        $arrayVendorValue = [];

        if (is_array($sort))
            foreach ($sort as $v) {
                $sortCat .= (int) $v . ',';
            }

        if (!empty($sortCat)) {

            // Массив имен характеристик
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);
            $arrayVendor = array_column($PHPShopOrm->getList(['*'], ['id' => sprintf(' IN (%s 0)', $sortCat)], ['order' => 'num']), null, 'id');

            $product['vendor_array'] = unserialize($product['vendor_array']);

            if (is_array($product['vendor_array']))
                foreach ($product['vendor_array'] as $v) {
                    foreach ($v as $value)
                        if (is_numeric($value))
                            $sortValue .= (int) $value . ',';
                }

            if (!empty($sortValue)) {

                // Массив значений характеристик
                $PHPShopOrm = new PHPShopOrm();
                $result = $PHPShopOrm->query("select * from " . $GLOBALS['SysValue']['base']['sort'] . " where id IN ( $sortValue 0) order by num");
                while (@$row = mysqli_fetch_array($result)) {
                    $arrayVendorValue[$row['category']]['name'][$row['id']] = $row['name'];
                    $arrayVendorValue[$row['category']]['id'][] = $row['id'];
                }

                // Название товара
                $list[] = [
                    'id' => 4180,
                    'values' => [0 => ['value' => PHPShopString::win_utf8($product['name'])]],
                ];

                // Описание
                $list[] = [
                    'id' => 4191,
                    'values' => [0 => ['value' => PHPShopString::win_utf8(strip_tags($product['content'], '<br><ul><li>'))]],
                ];

                if (is_array($arrayVendor))
                    foreach ($arrayVendor as $idCategory => $value) {

                        /*
                          if (strstr($value['name'], 'Название')) {
                          $values[] = [
                          'value' => PHPShopString::win_utf8($product['name']),
                          ];
                          } */

                        if (!empty($arrayVendorValue[$idCategory]['name'])) {
                            if (!empty($value['name'])) {

                                $values = [];

                                $arr = [];
                                foreach ($arrayVendorValue[$idCategory]['id'] as $valueId) {
                                    $arr[] = $arrayVendorValue[$idCategory]['name'][(int) $valueId];
                                }

                                if (is_array($arr)) {
                                    foreach ($arr as $k => $v) {
                                        $values[$k] = [
                                            "value" => PHPShopString::win_utf8($v)
                                        ];
                                        $dictionary_value_id = $this->getAttributesValues($value['attribute_ozonseller'], $category_ozonseller, $v);
                                        if (!empty($dictionary_value_id))
                                            $values[$k]["dictionary_value_id"] = $dictionary_value_id;
                                    }
                                }
                            }
                        }
                        $list[] = ["id" => $value['attribute_ozonseller'], "values" => $values];
                    }

                return ['attributes' => $list, 'category' => $category_ozonseller];
            }
        }
    }

    public function getAttributesValues($attribute_id, $category_id, $sort_name, $return_array = false) {

        $sort_name = PHPShopString::win_utf8($sort_name);
        $str = [];

        $params = [
            'attribute_id' => $attribute_id,
            'category_id' => $category_id,
            'last_value_id' => 0,
            'limit' => 1000,
            'language' => 'DEFAULT'
        ];

        $result = $this->request(self::GET_ATTRIBUTE_VALUES, $params);

        // Журнал
        $log['params'] = $params;
        $log['result'] = $result;

        //$this->log($log, $attribute_id, self::GET_ATTRIBUTE_VALUES);

        if (is_array($result['result'])) {
            foreach ($result['result'] as $val) {

                // Поиск по имени
                if (empty($return_array)) {

                    if ($val['value'] == $sort_name)
                        return $val['id'];
                } else
                    $str[] = PHPShopString::utf8_win1251($val['value']);
            }
        }

        if (!empty($return_array))
            return $str;
    }

    public function sendProductsInfo($product) {
        $params = ['task_id' => $product['export_ozon_task_id']];
        $result = $this->request(self::IMPORT_PRODUCT_INFO, $params);

        // Журнал
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, $product['id'], self::IMPORT_PRODUCT_INFO);

        return $result;
    }

    public function getImages($id, $pic_main) {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
        $data = $PHPShopOrm->select(['*'], ['parent' => '=' . (int) $id, 'name' => '!="' . $pic_main . '"'], ['order' => 'num'], ['limit' => 15]);

        // Главное изображение
        $pic_main_b = str_replace(".", "_big.", $pic_main);
        if (!$this->image_save_source or ! file_exists($_SERVER['DOCUMENT_ROOT'] . $pic_main_b))
            $pic_main_b = $pic_main;

        if (!strstr($pic_main_b, 'https'))
            $pic_main_b = 'https://' . $_SERVER['SERVER_NAME'] . $pic_main_b;

        $images[] = $pic_main_b;

        if (is_array($data)) {
            foreach ($data as $row) {

                $name = $row['name'];
                $name_b = str_replace(".", "_big.", $name);

                // Подбор исходного изображения
                if (!$this->image_save_source or ! file_exists($_SERVER['DOCUMENT_ROOT'] . $name_b))
                    $name_b = $name;

                if (!strstr($name_b, 'https'))
                    $name_b = 'https://' . $_SERVER['SERVER_NAME'] . $name_b;

                $images[] = $name_b;
            }
        }

        return $images;
    }

    public function sendProducts($products = [], $params = []) {

        if (is_array($products)) {
            foreach ($products as $prod) {

                // price columns
                $price = $prod['price'];

                if (!empty($prod['price_ozon'])) {
                    $price = $prod['price_ozon'];
                } elseif (!empty($prod['price' . (int) $this->price])) {
                    $price = $prod['price' . (int) $this->price];
                }

                if ($this->fee > 0) {
                    if ($this->fee_type == 1) {
                        $price = $price - ($price * $this->fee / 100);
                    } else {
                        $price = $price + ($price * $this->fee / 100);
                    }
                }

                // Ключ обновления артикул
                if ($this->type == 2) {
                    $offer_id = $prod['uid'];
                } else
                    $offer_id = $prod['id'];

                $params['items'][] = [
                    "attributes" => $this->getAttributes($prod)['attributes'],
                    "barcode" => (string) $prod['barcode_ozon'],
                    "category_id" => $this->getAttributes($prod)['category'],
                    "color_image" => "",
                    "complex_attributes" => [],
                    "depth" => $prod['length'],
                    "dimension_unit" => "cm",
                    "height" => $prod['height'],
                    "images" => $this->getImages($prod['id'], $prod['pic_big']),
                    "images360" => [],
                    "name" => PHPShopString::win_utf8($prod['name']),
                    "offer_id" => $offer_id,
                    "old_price" => $prod['price_n'],
                    "pdf_list" => [],
                    "premium_price" => "",
                    "price" => (string) $price,
                    "primary_image" => "",
                    "vat" => (string) $this->vat,
                    "weight" => $prod['weight'],
                    "weight_unit" => "g",
                    "width" => $prod['width']
                ];
            }

            $result = $this->request(self::IMPORT_PRODUCT, $params);

            // Лог JSON
            //$this->log_json(json_encode($params), 0, 'sendProducts');
            // Журнал
            $log['params'] = $params;
            $log['result'] = $result;

            $this->log($log, $prod['id'], self::IMPORT_PRODUCT);

            return $result;
        }
    }

    /**
     * Получение категорий
     */
    public function getTree($params = []) {
        $method = self::GET_PARENT_TREE;
        return $this->request($method, $params);
    }

    /*
     *  Получение характеристик категории
     */

    public function getTreeAttribute($params = []) {
        $result = $this->request(self::GET_TREE_ATTRIBUTE, $params);

        // Журнал
        $log['params'] = $params;
        $log['result'] = $result;

        //$this->log($log, null, self::GET_TREE_ATTRIBUTE);

        return $result;
    }

    /**
     * Запрос к API
     * @param string $method адрес метода
     * @param array $params параметры
     * @return array
     */
    public function request($method, $params = []) {

        $api = self::API_URL;
        $ch = curl_init();
        $header = [
            'Client-Id: ' . $this->client_id,
            'Api-Key: ' . $this->api_key,
            'Content-Type: application/json'
        ];
        curl_setopt($ch, CURLOPT_URL, $api . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        if (!empty($params)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    // номер заказа
    function setOrderNum() {

        $PHPShopOrm = new PHPShopOrm();
        $res = $PHPShopOrm->query("select uid from " . $GLOBALS['SysValue']['base']['orders'] . " order by id desc LIMIT 0, 1");
        $row = mysqli_fetch_array($res);
        $last = $row['uid'];
        $all_num = explode("-", $last);
        $ferst_num = $all_num[0];

        if ($ferst_num < 100)
            $ferst_num = 100;
        $order_num = $ferst_num + 1;

        // Номер заказа
        $ouid = $order_num . "-" . substr(abs(crc32(uniqid(session_id()))), 0, 3);
        return $ouid;
    }

}

?>