<?php
/**
 * ���������� ������ � Wildberries Seller API
 * @author PHPShop Software
 * @version 1.5
 * @package PHPShopModules
 * @todo https://openapi.wb.ru/
 */
class WbSeller {

    const API_URL = 'https://suppliers-api.wildberries.ru';
    const GET_PRODUCT_LIST = '/content/v1/cards/cursor/list';
    const GET_PRODUCT = '/content/v1/cards/filter';
    const GET_PARENT_TREE = '/content/v1/object/parent/all';
    const GET_TREE = '/content/v1/object/all';
    const GET_TREE_ATTRIBUTE = '/content/v1/object/characteristics/';
    const IMPORT_PRODUCT = '/content/v1/cards/upload';
    const IMPORT_ADD_PRODUCT = '/content/v1/cards/upload/add';
    const IMPORT_MEDIA = '/content/v1/media/save';
    const GET_WAREHOUSE_LIST = '/api/v3/warehouses';
    const UPDATE_PRODUCT_STOCKS = '/api/v3/stocks/';
    const UPDATE_PRODUCT_PRICES = '/public/api/v1/prices';
    const GET_ORDER_LIST = '/api/v3/orders';
    const GET_ORDER_NEW = '/api/v3/orders/new';

    public $api_key;

    public function __construct() {
        global $PHPShopSystem;

        // ��������� ���������
        PHPShopObj::loadClass("valuta");
        $this->PHPShopValuta = (new PHPShopValutaArray())->getArray();
        $this->percent = $PHPShopSystem->getValue('percent');
        $this->defvaluta = $PHPShopSystem->getValue('dengi');
        $this->format = $PHPShopSystem->getSerilizeParam('admoption.price_znak');
        $this->vat = $PHPShopSystem->getParam('nds') / 100;
        $this->image_save_source = $PHPShopSystem->ifSerilizeParam('admoption.image_save_source');

        // ��������� ������
        $PHPShopOrm = new PHPShopOrm('phpshop_modules_wbseller_system');
        $this->options = $PHPShopOrm->select();
        $this->api_key = $this->options['token'];
        $this->status = $this->options['status'];
        $this->fee_type = $this->options['fee_type'];
        $this->fee = $this->options['fee'];
        $this->price = $this->options['price'];
        $this->type = $this->options['type'];
        $this->warehouse = $this->options['warehouse_id'];
        $this->status_import = $this->options['status_import'];
        $this->delivery = $this->options['delivery'];
        $this->create_products = $this->options['create_products'];

        $this->status_list = [
            'new' => '����� ������',
            'all' => '��� ������',
        ];

        if (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS']))
            $this->ssl = 'https://';
        else
            $this->ssl = 'http://';
    }

    /**
     *  �������� ������
     */
    public function addProduct($id) {
        global $PHPShopSystem;

        $product_array = $this->getProduct([$id])['data'];

        if (is_array($product_array))
            foreach ($product_array as $row) {

                if ($row['vendorCode'] == $id)
                    $product_info = $row;
                else
                    continue;
            }

        // ����� �����
        if (is_array($product_info['characteristics']))
            foreach ($product_info['characteristics'] as $characteristics) {

                if (!empty($characteristics[PHPShopString::win_utf8('������������')]))
                    $product_info['name'] = $characteristics[PHPShopString::win_utf8('������������')];

                if (!empty($characteristics[PHPShopString::win_utf8('�������')]))
                    $product_info['category'] = $characteristics[PHPShopString::win_utf8('�������')];

                if (!empty($characteristics[PHPShopString::win_utf8('��������')]))
                    $product_info['description'] = $characteristics[PHPShopString::win_utf8('��������')];

                if (!empty($characteristics[PHPShopString::win_utf8('��� ������ � ��������� (�)')]))
                    $product_info['weight'] = $characteristics[PHPShopString::win_utf8('��� ������ � ��������� (�)')][0];

                if (!empty($characteristics[PHPShopString::win_utf8('������ ��������')]))
                    $product_info['width'] = $characteristics[PHPShopString::win_utf8('������ ��������')][0] * 100;

                if (!empty($characteristics[PHPShopString::win_utf8('������ ��������')]))
                    $product_info['height'] = $characteristics[PHPShopString::win_utf8('������ ��������')][0] * 100;

                if (!empty($characteristics[PHPShopString::win_utf8('����� ��������')]))
                    $product_info['length'] = $characteristics[PHPShopString::win_utf8('����� ��������')][0] * 100;
            }

        $insert['name_new'] = PHPShopString::utf8_win1251($product_info['name']);
        $insert['uid_new'] = PHPShopString::utf8_win1251($product_info['vendorCode']);
        $insert['export_wb_id_new'] = PHPShopString::utf8_win1251($product_info['nmID']);
        $insert['export_wb_new'] = 1;
        $insert['datas_new'] = time();
        $insert['user_new'] = $_SESSION['idPHPSHOP'];
        $insert['export_wb_task_status_new'] = time();
        $insert['barcode_wb_new'] = PHPShopString::utf8_win1251($product_info['sizes'][0]['skus'][0]);

        // ���������
        $insert['category_new'] = (new PHPShopOrm($GLOBALS['SysValue']['base']['categories']))->getOne(['id,name'], ['category_wbseller' => '="' . PHPShopString::utf8_win1251($product_info['category']) . '"'])['id'];
      
        $insert['items_new'] = 1;
        $insert['enabled_new'] = 1;
        $insert['price_new'] = $product_info['sizes'][0]['price'];
        $insert['baseinputvaluta_new'] = $PHPShopSystem->getDefaultOrderValutaId();
        $insert['weight_new'] = $product_info['weight'];
        $insert['height_new'] = $product_info['height'];
        $insert['width_new'] = $product_info['width'];
        $insert['length_new'] = $product_info['length'];
        $insert['content_new'] = PHPShopString::utf8_win1251($product_info['description']);

        $prodict_id = (new PHPShopOrm($GLOBALS['SysValue']['base']['products']))->insert($insert);

        // �������� �����������
        $this->addProductImage($product_info['mediaFiles'], $prodict_id);

        return $prodict_id;
    }

    /**
     *  �������� �����������
     */
    public function addProductImage($mediaFiles, $prodict_id) {
        global $PHPShopSystem;

        require_once $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/phpshop/lib/thumb/phpthumb.php';
        $width_kratko = $PHPShopSystem->getSerilizeParam('admoption.width_kratko');
        $img_tw = $PHPShopSystem->getSerilizeParam('admoption.img_tw');
        $img_th = $PHPShopSystem->getSerilizeParam('admoption.img_th');

        // ����� ��������
        $path = $PHPShopSystem->getSerilizeParam('admoption.image_result_path');
        if (!empty($path))
            $path = $path . '/';

        $img_load = 0;


        if (is_array($mediaFiles)) {
            foreach ($mediaFiles as $k => $img) {
                if (!empty($img) and ! stristr($img, '.mp4')) {

                    $path_parts = pathinfo($img);

                    $path_parts['basename'] = $prodict_id . '_' . $path_parts['basename'];

                    // ���� ��������
                    if ($this->downloadFile($img, $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . '/UserFiles/Image/' . $path . $path_parts['basename']))
                        $img_load++;
                    else
                        continue;

                    // ����� ���
                    $img = $GLOBALS['dir']['dir'] . '/UserFiles/Image/' . $path . $path_parts['basename'];

                    // ������ � �����������
                    $PHPShopOrmImg = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
                    $PHPShopOrmImg->insert(array('parent_new' => intval($prodict_id), 'name_new' => $img, 'num_new' => $k));

                    $file = $_SERVER['DOCUMENT_ROOT'] . $img;
                    $name = str_replace(array(".png", ".jpg", ".jpeg", ".gif", ".PNG", ".JPG", ".JPEG", ".GIF"), array("s.png", "s.jpg", "s.jpeg", "s.gif", "s.png", "s.jpg", "s.jpeg", "s.gif"), $file);

                    if (!file_exists($name) and file_exists($file)) {

                        // ��������� �������� 
                        if (!empty($_POST['export_imgproc'])) {
                            $thumb = new PHPThumb($file);
                            $thumb->setOptions(array('jpegQuality' => $width_kratko));
                            $thumb->resize($img_tw, $img_th);
                            $thumb->save($name);
                        } else
                            copy($file, $name);
                    }

                    // ������� �����������
                    if ($k == 0 and ! empty($file)) {

                        $update['pic_big_new'] = $img;

                        // ������� ������
                        $update['pic_small_new'] = str_replace(array(".png", ".jpg", ".jpeg", ".gif", ".PNG", ".JPG", ".JPEG", ".GIF"), array("s.png", "s.jpg", "s.jpeg", "s.gif", "s.png", "s.jpg", "s.jpeg", "s.gif"), $img);
                    }
                }
            }

            (new PHPShopOrm($GLOBALS['SysValue']['base']['products']))->update($update, ['id' => '=' . intval($prodict_id)]);
        }
    }

    /**
     *  �������� ����������� �� ������ 
     */
    public function downloadFile($url, $path) {
        $newfname = $path;

        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        $file = fopen($url, 'rb', false, stream_context_create($arrContextOptions));
        if ($file) {
            $newf = fopen($newfname, 'wb');
            if ($newf) {
                while (!feof($file)) {
                    fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                }
            }
        }
        if ($file) {
            fclose($file);
        }
        if ($newf) {
            fclose($newf);
            return true;
        }
    }

    /**
     *  ���� ������
     */
    public function price($price, $baseinputvaluta) {

        // ���� ������ ���������� �� �������
        if ($baseinputvaluta !== $this->defvaluta) {
            $vkurs = $this->PHPShopValuta[$baseinputvaluta]['kurs'];

            // ���� ���� ������� ��� ������ �������
            if (empty($vkurs))
                $vkurs = 1;

            // �������� ���� � ������� ������
            $price = $price / $vkurs;
        }

        $price = ($price + (($price * $this->percent) / 100));
        $price = round($price, intval($this->format));

        return $price;
    }

    /**
     * ��������� ������� �� ������
     */
    public function setProductStock($products = []) {

        if (is_array($products)) {
            foreach ($products as $product) {

                if (empty($product['enabled']))
                    $product['items'] = 0;

                if (empty($product['barcode_wb']))
                    $product['barcode_wb'] = $product['uid'];

                $params['stocks'][] = [
                    'sku' => (string) $product['barcode_wb'],
                    'amount' => (int) $product['items']
                ];
            }
        }

        $result = $this->request(self::UPDATE_PRODUCT_STOCKS . $this->warehouse, $params, true);

        // ������
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, $product['id'], self::UPDATE_PRODUCT_STOCKS . $this->warehouse);

        return $result;
    }

    /**
     * ��������� ������ �������
     */
    public function getWarehouse() {

        $result = $this->request(self::GET_WAREHOUSE_LIST);

        // ������
        $log['params'] = [];
        $log['result'] = $result;

        $this->log($log, null, self::GET_WAREHOUSE_LIST);

        return $result;
    }

    /**
     * �������������� ����
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
     *  ����� ��� ��������?
     */
    public function checkOrderBase($id) {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
        $data = $PHPShopOrm->getOne(['id'], ['wbseller_order_data' => '="' . $id . '"']);
        if (!empty($data['id']))
            return $data['id'];
    }

    /**
     * ������ ������� �� Wb
     */
    public function getProduct($product_id) {

        $params = [
            'vendorCodes' => $product_id,
        ];

        $result = $this->request(self::GET_PRODUCT, $params);

        // ������
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, null, self::GET_PRODUCT);

        return $result;
    }

    /**
     *  ������ ������� �� WB
     */
    public function getProductList($search = "", $limit = null) {

        if (empty($limit))
            $limit = 50;

        $params = [
            'sort' => [
                'cursor' => [
                    'limit' => (int) $limit
                ],
                'filter' => [
                    "textSearch" => (string) PHPShopString::win_utf8($search),
                    "withPhoto" => (int) 1
                ],
                'sort' => [
                    "sortColumn" => "",
                    "ascending" > false
                ]
            ],
        ];


        $result = $this->request(self::GET_PRODUCT_LIST, $params);

        // ������
        $log['params'] = $params;
        $log['result'] = $result;


        $this->log($log, 0, self::GET_PRODUCT_LIST);

        return $result;
    }

    /**
     *  ������ �������
     */
    public function getOrderList($date1, $date2, $status = 'all') {

        if ($status == 'new')
            $method = self::GET_ORDER_NEW;
        else
            $method = self::GET_ORDER_LIST . '?dateFrom=' . PHPShopDate::GetUnixTime($date1, '-', true) . '&dateTo=' . PHPShopDate::GetUnixTime($date2, '-', true) . '&limit=1000&next=0';

        $result = $this->request($method);

        // ������
        $log['params'] = ['dateFrom' => PHPShopDate::GetUnixTime($date1, '-', true), 'dateTo' => PHPShopDate::GetUnixTime($date2, '-', true)];
        $log['result'] = $result;

        $this->log($log, 0, $method);

        return $result;
    }

    /**
     * ������ � ������
     */
    public function log($message, $id, $type) {
        $PHPShopOrm = new PHPShopOrm('phpshop_modules_wbseller_log');

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
        $PHPShopOrm = new PHPShopOrm('phpshop_modules_wbseller_log');

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
        $category_wbseller = $category->getParam('category_wbseller');

        // ��������
        $list[] = [PHPShopString::win_utf8('������ ��������') => (int) $product['width']];
        $list[] = [PHPShopString::win_utf8('����� ��������') => (int) $product['length']];
        $list[] = [PHPShopString::win_utf8('������ ��������') => (int) $product['height']];
        $list[] = [PHPShopString::win_utf8('��� ������ � ��������� (�)') => (int) $product['weight']];

        // �������
        $list[] = [PHPShopString::win_utf8('�������') => PHPShopString::win_utf8($category_wbseller)];

        // ������������
        $list[] = [PHPShopString::win_utf8('������������') => PHPShopString::win_utf8($product['name'])];

        // ��������
        $list[] = [PHPShopString::win_utf8('��������') => PHPShopString::win_utf8(strip_tags($product['content']))];

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

                if (is_array($arrayVendor))
                    foreach ($arrayVendor as $idCategory => $value) {

                        if (!empty($arrayVendorValue[$idCategory]['name'])) {
                            if (!empty($value['name'])) {

                                $arr = [];
                                foreach ($arrayVendorValue[$idCategory]['id'] as $valueId) {
                                    $arr[] = $arrayVendorValue[$idCategory]['name'][(int) $valueId];
                                }

                                if (is_array($arr)) {
                                    foreach ($arr as $v) {
                                        $values = PHPShopString::win_utf8($v);
                                    }
                                }
                            }
                        }

                        if (!empty($value['attribute_wbseller']) and ! empty($values))
                            $list[] = [PHPShopString::win_utf8($value['attribute_wbseller']) => [$values]];
                    }
            }
        }

        return $list;
    }

    public function getImages($id, $pic_main) {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
        $data = $PHPShopOrm->select(['*'], ['parent' => '=' . (int) $id, 'name' => '!="' . $pic_main . '"'], ['order' => 'num'], ['limit' => 15]);

        // ������� �����������
        $pic_main_b = str_replace(".", "_big.", $pic_main);
        if (!$this->image_save_source or ! file_exists($_SERVER['DOCUMENT_ROOT'] . $pic_main_b))
            $pic_main_b = $pic_main;

        if (!strstr($pic_main_b, 'http'))
            $pic_main_b = $this->ssl . $_SERVER['SERVER_NAME'] . $pic_main_b;

        $images[] = $pic_main_b;

        if (is_array($data)) {
            foreach ($data as $row) {

                $name = $row['name'];
                $name_b = str_replace(".", "_big.", $name);

                // ������ ��������� �����������
                if (!$this->image_save_source or ! file_exists($_SERVER['DOCUMENT_ROOT'] . $name_b))
                    $name_b = $name;

                if (!strstr($name_b, 'http'))
                    $name_b = $this->ssl . $_SERVER['SERVER_NAME'] . $name_b;

                $images[] = $name_b;
            }
        }

        return $images;
    }

    /**
     * ������� ���
     */
    public function sendPrices($params) {


        $result = $this->request(self::UPDATE_PRODUCT_PRICES, $params);

        // ������
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, null, self::UPDATE_PRODUCT_PRICES);

        return $result;
    }

    /**
     * ������� �����������
     */
    public function sendImages($prod) {

        $params = [
            "vendorCode" => (string) $prod['uid'],
            "data" => $this->getImages($prod['id'], $prod['pic_big'])
        ];

        $result = $this->request(self::IMPORT_MEDIA, $params);

        // ������
        $log['params'] = $params;
        $log['result'] = $result;

        $this->log($log, $prod['id'], self::IMPORT_MEDIA);

        return $result;
    }

    /**
     *  ������� �������
     */
    public function sendProducts($products = [], $params = []) {

        if (is_array($products)) {
            foreach ($products as $prod) {

                // price columns
                $price = $prod['price'];

                if (!empty($prod['price_wb'])) {
                    $price = $prod['price_wb'];
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

                if (empty($prod['barcode_wb']))
                    $prod['barcode_wb'] = $prod['uid'];

                $params[] = [[
                "characteristics" => $this->getAttributes($prod),
                "vendorCode" => (string) PHPShopString::win_utf8($prod['uid']),
                "sizes" => [[
                'price' => (int) $this->price($price, $prod['baseinputvaluta']),
                'skus' => [$prod['barcode_wb']]
                    ]],
                ]];
            }

            $result = $this->request(self::IMPORT_PRODUCT, $params);


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
    public function getTree($name = false) {

        if (!empty($name))
            $method = self::GET_TREE . '?name=' . urlencode($name) . '&top=20';
        else
            $method = self::GET_PARENT_TREE;

        $result = $this->request($method, false);

        // ������
        $log['params'] = $name;
        $log['result'] = $result;

        //$this->log($log, null, $method);

        return $result;
    }

    /*
     *  ��������� ������������� ���������
     */

    public function getTreeAttribute($name) {

        $result = $this->request(self::GET_TREE_ATTRIBUTE . PHPShopString::win_utf8($name));


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
    public function request($method, $params = [], $put = false, $debug = false) {

        $api = self::API_URL;
        $ch = curl_init();
        $header = [
            'Authorization: ' . $this->api_key,
            'Content-Type: application/json'
        ];

        curl_setopt($ch, CURLOPT_URL, $api . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        if (!empty($params)) {

            if (empty($put))
                curl_setopt($ch, CURLOPT_POST, true);
            else
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        $result = curl_exec($ch);

        if ($debug)
            echo $result;

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