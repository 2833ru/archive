<?php
/**
 * ���������� ������ � Ozon Seller API
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopModules
 * @todo https://docs.ozon.ru/api/seller/#tag/Environment
 */
class OzonSeller {

    const GET_TREE = '/v1/categories/tree';
    const GET_PARENT_TREE = '/v2/category/tree';
    const GET_TREE_ATTRIBUTE = '/v3/category/attribute';
    const GET_ATTRIBUTE_VALUES = '/v2/category/attribute/values';
    const API_URL = 'https://api-seller.ozon.ru';
    const IMPORT_PRODUCT = '/v2/product/import';
    const IMPORT_PRODUCT_INFO = '/v1/product/import/info';
    const GET_FBS_ORDER_LIST = '/v2/posting/fbs/list';
    const GET_FBS_ORDER = '/v3/posting/fbs/get';
    const GET_FBO_ORDER_LIST = '/v2/posting/fbo/list';
    const GET_FBO_ORDER = '/v2/posting/fbo/get';

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

        $this->status_list = [
            'acceptance_in_progress' => '��� ������',
            'awaiting_approve' => '������� �������������',
            'awaiting_packaging' => '������� ��������',
            'awaiting_deliver' => '������� ��������',
            'arbitration' => '��������',
            'client_arbitration' => '���������� �������� ��������',
            'delivering' => '������������',
            'driver_pickup' => '� ��������',
            'delivered' => '����������',
            'cancelled' => '��������'
        ];
    }

    /**
     * �������������� ����
     */
    public function getTime($date) {
        $d = explode('T', $date);
        $t = explode('Z', $d[1]);
        return $d[0] . ' ' . $t[0];
    }

    /**
     * ������ FBS ������
     */
    public function getOrderFbo($num) {

        $params = [
            'posting_number' => $num,
        ];

        $result = $this->request(self::GET_FBO_ORDER, $params);

        // ������
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, $num, self::GET_FBO_ORDER);

        return $result;
    }

    /**
     * ������ FBS ������
     */
    public function getOrderFbs($num) {

        $params = [
            'posting_number' => $num,
        ];

        $result = $this->request(self::GET_FBS_ORDER, $params);

        // ������
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, $num, self::GET_FBS_ORDER);

        return $result;
    }

    /**
     *  ����� ��� ��������?
     */
    public function checkOrderBase($id) {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
        $data = $PHPShopOrm->getOne(['id'], ['ozonseller_order_data' => '="' . $id . '"']);
        if (!empty($data['id']))
            return $data['id'];
    }

    /**
     *  ������ ������
     */
    public function getStatus($name) {

        return $this->status_list[$name];
    }

    /**
     *  ������  ������� FBS
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

        // ������
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, 0, self::GET_FBS_ORDER_LIST);

        return $result;
    }

    /**
     *  ������ ������� FBO
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

        // ������
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, 0, self::GET_FBO_ORDER_LIST);

        return $result;
    }

    /**
     * ������ � ������
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
     * ������ � ������ JSON
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

            // ������ ���� �������������
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

                // ������ �������� �������������
                $PHPShopOrm = new PHPShopOrm();
                $result = $PHPShopOrm->query("select * from " . $GLOBALS['SysValue']['base']['sort'] . " where id IN ( $sortValue 0) order by num");
                while (@$row = mysqli_fetch_array($result)) {
                    $arrayVendorValue[$row['category']]['name'][$row['id']] = $row['name'];
                    $arrayVendorValue[$row['category']]['id'][] = $row['id'];
                }

                //print_r($arrayVendor);

                if (is_array($arrayVendor))
                    foreach ($arrayVendor as $idCategory => $value) {
                        $values = [];

                        if (strstr($value['name'], '��������')) {
                            $values[] = [
                                'value' => PHPShopString::win_utf8($product['name']),
                            ];
                        }

                        if (!empty($arrayVendorValue[$idCategory]['name'])) {
                            if (!empty($value['name'])) {



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

        // ������
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, $attribute_id, self::GET_ATTRIBUTE_VALUES);

        if (is_array($result['result'])) {
            foreach ($result['result'] as $val) {

                // ����� �� �����
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

        // ������
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, $product['id'], self::IMPORT_PRODUCT_INFO);

        return $result;
    }

    public function getImages($id) {

        $images = [];
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
        $data = $PHPShopOrm->select(['*'], ['parent' => '=' . (int) $id], ['order' => 'num'], ['limit' => 15]);

        if (is_array($data)) {
            foreach ($data as $row) {

                $name = $row['name'];
                $name_b = str_replace(".", "_big.", $name);

                // ������ ��������� �����������
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

                $params['items'][] = [
                    "attributes" => $this->getAttributes($prod)['attributes'],
                    "barcode" => $prod['uid'],
                    "category_id" => $this->getAttributes($prod)['category'],
                    "color_image" => "",
                    "complex_attributes" => [],
                    "depth" => $prod['length'],
                    "dimension_unit" => "cm",
                    "height" => $prod['height'],
                    "images" => $this->getImages($prod['id']),
                    "images360" => [],
                    "name" => PHPShopString::win_utf8($prod['name']),
                    "offer_id" => $prod['id'],
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

            // ��� JSON
            //$this->log_json(json_encode($params), 0, 'sendProducts');
            // ������
            $log['params'] = $params;
            $log['result'] = $result;

            $this->log($log, $prod['id'], self::IMPORT_PRODUCT);

            return $result;
        }
    }

    /**
     * ��������� ���������
     */
    public function getTree($params = []) {
        if (!empty($params))
            $method = self::GET_PARENT_TREE;
        else
            $method = self::GET_TREE;
        return $this->request($method, $params);
    }

    /*
     *  ��������� ������������� ���������
     */

    public function getTreeAttribute($params = []) {
        $result = $this->request(self::GET_TREE_ATTRIBUTE, $params);

        // ������
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, null, self::GET_TREE_ATTRIBUTE);

        return $result;
    }

    /**
     * ������ � API
     * @param string $method ����� ������
     * @param array $params ���������
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

    // ����� ������
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

        // ����� ������
        $ouid = $order_num . "-" . substr(abs(crc32(uniqid(session_id()))), 0, 3);
        return $ouid;
    }

}

?>