<?php

$TitlePage = __("������ ������");

// �������� �����
$key_name = array(
    'id' => 'Id',
    'name' => '������������',
    'uid' => '�������',
    'price' => '���� 1',
    'price2' => '���� 2',
    'price3' => '���� 3',
    'price4' => '���� 4',
    'price5' => '���� 5',
    'price_n' => '������ ����',
    'sklad' => '��� �����',
    'newtip' => '�������',
    'spec' => '���������������',
    'items' => '�����',
    'weight' => '���',
    'num' => '���������',
    'enabled' => '�����',
    'content' => '��������� ��������',
    'description' => '������� ��������',
    'pic_small' => '��������� �����������',
    'pic_big' => '������� �����������',
    'yml' => '������.������',
    'icon' => '������',
    'parent_to' => '��������',
    'category' => '�������',
    'title' => '���������',
    'login' => '�����',
    'tel' => '�������',
    'cumulative_discount' => '������������� ������',
    'seller' => '������ �������� � 1�',
    'fio' => '�.�.�',
    'city' => '�����',
    'street' => '�����',
    'odnotip' => '������������� ������',
    'page' => '��������',
    'parent' => '����������� ������',
    'dop_cat' => '�������������� ��������',
    'ed_izm' => '������� ���������',
    'baseinputvaluta' => '������',
    'vendor_array' => '��������������',
    'p_enabled' => '������� � ������.������',
    'parent_enabled' => '������',
    'descrip' => 'Meta description',
    'keywords' => 'Meta keywords',
    "prod_seo_name" => 'SEO ������',
    'num_row' => '������� � �����',
    'num_cow' => '������� �� ��������',
    'count' => '�������� �������',
    'cat_seo_name' => 'SEO ������ ��������',
    'sum' => '�����',
    'servers' => '�������',
    'items1' => '����� 2',
    'items2' => '����� 3',
    'items3' => '����� 4',
    'items4' => '����� 5',
    'vendor' => '@��������������',
    'data_adres' => '�����',
    'color' => '��� �����',
    'parent2' => '����',
    'rate' => '�������',
    'productday' => '����� ���',
    'hit' => '���',
    'sendmail' => '�������� �� ��������',
    'statusi' => '������ ������',
    'country' => '������',
    'state' => '�������',
    'index' => '������',
    'house' => '���',
    'porch' => '�������',
    'door_phone' => '�������',
    'flat' => '��������',
    'delivtime' => '����� ��������',
    'org_name' => '�����������',
    'org_inn' => '���',
    'org_kpp' => '���',
    'org_yur_adres' => '����������� �����',
    'dop_info' => '����������� �����������',
    'tracking' => '��� ������������',
    'path' => '���� ��������',
    'length' => '�����',
    'width' => '������',
    'height' => '������',
    'moysklad_product_id' => '�������� Id',
    'bonus' => '�����',
    'price_purch' => '���������� ����'
);

if ($GLOBALS['PHPShopBase']->codBase == 'utf-8')
    unset($key_name);

// ���� ����
$key_stop = array('password', 'wishlist', 'sort', 'yml_bid_array', 'status', 'files', 'datas', 'price_search', 'vid', 'name_rambler', 'servers', 'skin', 'skin_enabled', 'secure_groups', 'icon_description', 'title_enabled', 'title_shablon', 'descrip_shablon', 'descrip_enabled', 'productsgroup_check', 'productsgroup_product', 'keywords_enabled', 'keywords_shablon', 'rate_count', 'sort_cache', 'sort_cache_created_at', 'parent_title', 'menu', 'order_by', 'order_to', 'org_ras', 'org_bank', 'org_kor', 'org_bik', 'org_city', 'admin', 'org_fakt_adres');

if (empty($subpath[2]))
    $subpath[2] = null;

switch ($subpath[2]) {
    case 'catalog':
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
        $key_base = array('id');
        break;
    case 'user':
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
        $key_base = array('id', 'login');
        array_push($key_stop, 'tel_code', 'adres', 'inn', 'kpp', 'company', 'mail', 'token', 'token_time');
        break;
    case 'order':
        PHPShopObj::loadClass('order');
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
        $key_base = array('id', 'uid');
        array_push($key_stop, 'orders', 'user');
        $key_name['uid'] = __('� ������');
        $TitlePage .= ' ' . __('�������');
        break;
    default: $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
        $key_base = array('id', 'uid');
        break;
}

// �������� ����������� �� ������ 
function downloadFile($url, $path) {

    $newfname = $path;
    $url = iconv("windows-1251", "utf-8//IGNORE", $url);

    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );

    $file = @fopen($url, 'rb', false, stream_context_create($arrContextOptions));
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

// ��������� ���������
function setCategory() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
    $row = $PHPShopOrm->getOne(array('id'), array('name' => '="�������� CSV ' . PHPShopDate::get() . '"'));

    if (empty($row['id'])) {
        $result = $PHPShopOrm->insert(array('name_new' => '�������� CSV ' . PHPShopDate::get(), 'skin_enabled_new' => 1));
        return $result;
    } else
        return $row['id'];
}

function sort_encode($sort, $category) {

    $return = [];
    $delim = $_POST['export_sortdelim'];
    $sortsdelim = $_POST['export_sortsdelim'];
    $debug = false;
    if (!empty($sort)) {

        if (strstr($sort, $delim)) {
            $sort_array = explode($delim, $sort);
        } else
            $sort_array[] = $sort;

        if (is_array($sort_array))
            foreach ($sort_array as $sort_list) {

                if (strstr($sort_list, $sortsdelim)) {

                    $sort_list_array = explode($sortsdelim, $sort_list, 2);
                    $sort_name = PHPShopSecurity::TotalClean($sort_list_array[0]);
                    $sort_value = PHPShopSecurity::TotalClean($sort_list_array[1]);
                    
                    $return += (new sortCheck($sort_name,$sort_value,$category,$debug))->result();
                }
            }
    }

    return $return;
}

// ��������� ������ CSV
function csv_update($data) {
    global $PHPShopOrm, $PHPShopBase, $csv_load_option, $key_name, $csv_load_count, $subpath, $PHPShopSystem, $csv_load, $csv_load_totale, $img_load;

    // ��������� UTF-8
    if ($_POST['export_code'] == 'utf' and is_array($data)) {
        foreach ($data as $k => $v)
            $data[$k] = PHPShopString::utf8_win1251($v);
    }

    require_once $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/phpshop/lib/thumb/phpthumb.php';
    $width_kratko = $PHPShopSystem->getSerilizeParam('admoption.width_kratko');
    $img_tw = $PHPShopSystem->getSerilizeParam('admoption.img_tw');
    $img_th = $PHPShopSystem->getSerilizeParam('admoption.img_th');

    if (is_array($data)) {

        $key_name_true = array_flip($key_name);

        // ����� �����
        if (empty($csv_load_option)) {
            $select = false;

            // ������������� �����
            if (is_array($_POST['select_action'])) {

                foreach ($_POST['select_action'] as $k => $name) {
                    
                    // �������������
                    if(!empty($_POST['bot'])){
                        $_POST['select_action'][$k]= PHPShopString::utf8_win1251($name,true);
                    }

                    if (!empty($name))
                        $select = true;

                    if (substr($name, 0, 1) == '@')
                        $_POST['select_action'][$k] = '@' . $data[$k];
                }
            }

            if ($select)
                $csv_load_option = $_POST['select_action'];
            else
                $csv_load_option = $data;
        }
        // ��������
        else {
            // ����������� �����
            foreach ($csv_load_option as $k => $cols_name) {

                // base64
                if (substr($data[$k], 0, 7) == 'base64-') {

                    // ������������
                    if ($subpath[2] == 'user') {
                        $array = array();
                        $array['main'] = 0;
                        $array['list'][] = json_decode(base64_decode(substr($data[$k], 7, strlen($data[$k]) - 7)), true);
                        array_walk_recursive($array, 'array2iconv');

                        $data[$k] = serialize($array);
                    }
                }

                // ���� �������������
                if (!empty($key_name_true[$cols_name])) {
                    $row[$key_name_true[$cols_name]] = $data[$k];
                }
                // ���� �������������� � ��������
                elseif (substr($cols_name, 0, 1) == '@') {
                    $row[$cols_name] = $data[$k];
                    $sort_name = substr($cols_name, 1, (strlen($cols_name) - 1));

                    // ��������� ��������
                    if (strstr($data[$k], $_POST['export_sortsdelim'])) {
                        $sort_array = explode($_POST['export_sortsdelim'], $data[$k]);
                    } else
                        $sort_array[] = $data[$k];

                    if (is_array($sort_array)) {
                        foreach ($sort_array as $v)
                            $row['vendor_array'] .= $sort_name . $_POST['export_sortsdelim'] . $v . $_POST['export_sortdelim'];
                    }

                    unset($row[$cols_name]);
                    unset($sort_array);
                }
                // ���������
                else
                    $row[strtolower($cols_name)] = $data[$k];
            }

            // ������� ������������
            if (!empty($row['data_adres'])) {

                $row['enabled'] = 1;

                $tel['main'] = 0;
                $tel['list'][0]['tel_new'] = $row['data_adres'];
                $row['data_adres'] = serialize($tel);
            }

            // ���� ��������
            if (isset($row['path'])) {
                if (empty($row['category'])) {
                    $search = $row['path'];
                    $category = new PHPShopCategory(0);
                    $category->getChildrenCategories(100, ['id', 'parent_to', 'name'], false, $search);

                    while (count($category->search) != $category->found) {
                        $PHPShopOrmCat = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
                        $PHPShopOrmCat->debug = false;
                        $category->search_id = $PHPShopOrmCat->insert(array('name_new' => $category->search[$category->found], 'parent_to_new' => $category->search_id));
                        $category->found++;
                    }

                    $row['category'] = $category->search_id;
                }
            }

            // ��������� ����� �������
            if (isset($row['parent']) and $row['parent'] == '')
                unset($row['parent']);

            // ��������������
            if (!empty($row['vendor_array'])) {

                // ��������� ���������
                if (empty($row['category'])) {
                    $row['category'] = setCategory();
                }

                $row['vendor'] = null;
                $vendor_array = sort_encode($row['vendor_array'], $row['category']);

                if (is_array($vendor_array)) {
                    $row['vendor_array'] = serialize($vendor_array);
                    foreach ($vendor_array as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $p) {
                                $row['vendor'] .= "i" . $k . "-" . $p . "i";
                            }
                        } else
                            $row['vendor'] .= "i" . $k . "-" . $v . "i";
                    }
                } else
                    $row['vendor_array'] = null;
            }

            // ������ ���� � �������������
            if (!strstr($row['pic_big'], '/UserFiles/Image/') and ! strstr($row['pic_big'], 'http'))
                $_POST['export_imgpath'] = true;
            else
                $_POST['export_imgpath'] = false;


            if (!empty($_POST['export_imgpath'])) {
                if (!empty($row['pic_small']))
                    $row['pic_small'] = '/UserFiles/Image/' . $row['pic_small'];
            }

            // ����������� ��� �����������
            if (empty($_POST['export_imgdelim'])) {
                $imgdelim = [' ', ',', ';', '#'];
                foreach ($imgdelim as $delim) {
                    if (strstr($row['pic_big'], $delim)) {
                        $_POST['export_imgdelim'] = $delim;
                    }
                }
            }

            // �������������� �����������
            if (!empty($_POST['export_imgdelim']) and strstr($row['pic_big'], $_POST['export_imgdelim'])) {
                $data_img = explode($_POST['export_imgdelim'], $row['pic_big']);
            } elseif (!empty($row['pic_big']))
                $data_img[] = $row['pic_big'];

            if (!empty($data_img) and is_array($data_img)) {

                // ��������� ID ������ �� �������� ��� ����������
                if ($_POST['export_action'] == 'update' and empty($row['id']) and ! empty($row['uid'])) {
                    $PHPShopOrmProd = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                    $data_prod = $PHPShopOrmProd->getOne(array('id'), array('uid' => '="' . $row['uid'] . '"'));
                    $row['id'] = $data_prod['id'];
                }

                // ����� ��������
                $path = $PHPShopSystem->getSerilizeParam('admoption.image_result_path');
                if (!empty($path))
                    $path = $path . '/';

                foreach ($data_img as $k => $img) {
                    if (!empty($img)) {

                        // ������ ���� � ������������
                        if (!empty($_POST['export_imgpath']))
                            $img = '/UserFiles/Image/' . $img;

                        // �������� ����������� �� ������
                        if (isset($_POST['export_imgload']) and strstr($img, 'http')) {

                            $path_parts = pathinfo($img);
                            $path_parts['basename'] = PHPShopFile::toLatin($path_parts['basename']);

                            // ���� ��������
                            if (downloadFile($img, $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . '/UserFiles/Image/' . $path . $path_parts['basename']))
                                $img_load++;
                            else
                                continue;

                            // ����� ���
                            $img = $GLOBALS['dir']['dir'] . '/UserFiles/Image/' . $path . $path_parts['basename'];
                        }

                        // �������� ������������� �����������
                        $PHPShopOrmImg = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
                        $PHPShopOrmImg->debug = false;
                        $check = $PHPShopOrmImg->select(array('name'), array('name' => '="' . $img . '"', 'parent' => '=' . intval($row['id'])), false, array('limit' => 1));

                        // ������� �����
                        if (!is_array($check)) {

                            // ������ � �����������
                            $PHPShopOrmImg->insert(array('parent_new' => intval($row['id']), 'name_new' => $img, 'num_new' => $k));

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

                                $row['pic_big'] = $img;

                                // ������� ������
                                if (empty($row['pic_small']) or isset($_POST['export_imgload']) or isset($_POST['export_imgproc']))
                                    $row['pic_small'] = str_replace(array(".png", ".jpg", ".jpeg", ".gif", ".PNG", ".JPG", ".JPEG", ".GIF"), array("s.png", "s.jpg", "s.jpeg", "s.gif", "s.png", "s.jpg", "s.jpeg", "s.gif"), $img);
                            }
                        }
                    }
                }
            }
            // ������ ���� � �������������
            else if (isset($_POST['export_imgpath']) and ! empty($row['pic_big']))
                $row['pic_big'] = '/UserFiles/Image/' . $row['pic_big'];

            // �������� ������
            if ($_POST['export_action'] == 'insert') {

                $PHPShopOrm->debug = false;
                $PHPShopOrm->mysql_error = false;

                // ���������� �� ������
                if (isset($row['items'])) {
                    switch ($GLOBALS['admoption_sklad_status']) {

                        case(3):
                            if ($row['items'] < 1) {
                                $row['sklad'] = 1;
                            } else {
                                $row['sklad'] = 0;
                            }
                            break;

                        case(2):
                            if ($row['items'] < 1) {
                                $row['enabled'] = 0;
                            } else {
                                $row['enabled'] = 1;
                            }
                            break;

                        default:
                            break;
                    }
                }

                // ���� ��������
                $row['datas'] = time();

                // �������� ������������ �������
                if (empty($subpath[2]) and ! empty($_POST['export_uniq']) and ! empty($row['uid'])) {
                    $uniq = $PHPShopBase->getNumRows('products', "where uid = '" . $row['uid'] . "'");
                } else
                    $uniq = 0;
                
                // �������� SEO ����� ��������
                if($subpath[2] == 'catalog' and !empty($row['name'])){
                    $uniq_cat_data = (new PHPShopOrm($GLOBALS['SysValue']['base']['categories']))->getOne(['*'],['name'=>'="'.$row['name'].'"']);
                    
                    // ���� ����������� �������
                    if(!empty($uniq_cat_data['name'])){
                        $parent_cat_data = (new PHPShopOrm($GLOBALS['SysValue']['base']['categories']))->getOne(['*'],['id'=>'="'.$uniq_cat_data['parent_to'].'"']);
                        $row['cat_seo_name'] = PHPShopString::toLatin($row['name']);
                        $row['cat_seo_name'] = PHPShopString::toLatin($parent_cat_data['name']).'-'.PHPShopString::toLatin($row['name']);
                    }
                    else $row['cat_seo_name'] = PHPShopString::toLatin($row['name']);
                    
                }

                // �������� ������� �����
                if (isset($row['name']) and empty($row['name']))
                    $uniq = true;

                if (empty($uniq)) {

                    if (isset($row['price'])) {
                        $row['price'] = str_replace(',', '.', $row['price']);
                    }
                    if (isset($row['price_n'])) {
                        $row['price_n'] = str_replace(',', '.', $row['price_n']);
                    }
                    if (isset($row['price2'])) {
                        $row['price2'] = str_replace(',', '.', $row['price2']);
                    }
                    if (isset($row['price3'])) {
                        $row['price3'] = str_replace(',', '.', $row['price3']);
                    }
                    if (isset($row['price4'])) {
                        $row['price4'] = str_replace(',', '.', $row['price4']);
                    }
                    if (isset($row['price5'])) {
                        $row['price5'] = str_replace(',', '.', $row['price5']);
                    }

                    $insertID = $PHPShopOrm->insert($row, '');
                    if (is_numeric($insertID)) {

                        $PHPShopOrm->clean();

                        // ��������� ID � ����������� ������ ������
                        if ($PHPShopOrmImg)
                            $PHPShopOrmImg->update(array('parent_new' => $insertID), array('parent' => '=0'));

                        // �������
                        $csv_load_count++;
                        $csv_load_totale++;

                        // �����
                        $GLOBALS['csv_load'][] = $row;
                    }
                }
            }
            // ���������� ������
            else {

                // ������������� ����
                if (!empty($_POST['export_key'])) {
                    $where = array($_POST['export_key'] => '="' . $row[$_POST['export_key']] . '"');
                    unset($row[$_POST['export_key']]);
                } else {

                    // ���������� �� ID
                    if (!empty($row['id'])) {
                        $where = array('id' => '="' . intval($row['id']) . '"');
                        unset($row['id']);
                    }

                    // ���������� �� ��������
                    elseif (!empty($row['uid'])) {
                        $where = array('uid' => '="' . $row['uid'] . '"');
                        unset($row['uid']);
                    }

                    // ���������� �� ������
                    elseif (!empty($row['login'])) {
                        $where = array('login' => '="' . $row['login'] . '"');
                        unset($row['login']);
                    }

                    // ������
                    else {
                        unset($row);
                        return false;
                    }
                }

                // ���������� �� ������
                if (isset($row['items'])) {
                    switch ($GLOBALS['admoption_sklad_status']) {

                        case(3):
                            if ($row['items'] < 1) {
                                $row['sklad'] = 1;
                            } else {
                                $row['sklad'] = 0;
                            }
                            break;

                        case(2):
                            if ($row['items'] < 1) {
                                $row['enabled'] = 0;
                            } else {
                                $row['enabled'] = 1;
                            }
                            break;

                        default:
                            break;
                    }
                }

                // ���� ����������
                $row['datas'] = time();

                if (!empty($where)) {
                    $PHPShopOrm->debug = false;
                    if ($PHPShopOrm->update($row, $where, '') === true) {

                        // ��������� ID � ����������� ������ �� ��������
                        if (!empty($where['uid']) and is_array($data_img) and $PHPShopOrmImg) {

                            $PHPShopOrmProduct = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                            $data_product = $PHPShopOrmProduct->select(array('id'), array('uid' => $where['uid']), false, array('limit' => 1));
                            $PHPShopOrmImg->update(array('parent_new' => $data_product['id']), array('parent' => '=0'));
                        }

                        // �������
                        $count = $PHPShopOrm->get_affected_rows();

                        $csv_load_count += $count;
                        $csv_load_totale++;

                        // �����
                        if (!empty($count))
                            $GLOBALS['csv_load'][] = $row;
                    }
                }
            }
        }
    }
}

// ������� ����������
function actionSave() {
    global $PHPShopGUI, $PHPShopSystem, $key_name, $key_name, $result_message, $csv_load_count, $subpath, $csv_load, $csv_load_totale, $img_load;

    // ������� ���������
    if ($_POST['exchanges'] != 'new') {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges']);

        // �������� ��� ���������
        if (!empty($_POST['exchanges_new'])) {
            $PHPShopOrm->update(array('name_new' => $_POST['exchanges_new']), array('id' => '=' . intval($_POST['exchanges'])));
        }

        // ��������� ��� Cron
        if (!empty($_POST['exchanges_cron'])) {
            $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_POST['exchanges'])), false, array("limit" => 1));
            if (is_array($data)) {
                unset($_POST);
                $_POST = unserialize($data['option']);
                $exchanges_name = $data['name'];
                unset($_POST['exchanges_new']);
            }
        }
    }

    // ������� ���������
    if (!empty($_POST['exchanges_remove']) and is_array($_POST['exchanges_remove'])) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges']);
        foreach ($_POST['exchanges_remove'] as $v)
            $data = $PHPShopOrm->delete(array('id' => '=' . intval($v)));
    }

    // ������ �� ������ ��������
    if (!empty($_POST['subpath']))
        $subpath[2] = $_POST['subpath'];

    switch ($subpath[2]) {
        case 'catalog':
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
            break;
        case 'user':
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
            break;
        case 'order':
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
            break;
        default: $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
            break;
    }

    $delim = $_POST['export_delim'];

    // ��������� �������� ������
    $GLOBALS['admoption_sklad_status'] = $PHPShopSystem->getSerilizeParam('admoption.sklad_status');

    // ������ ��������
    $memory[$_GET['path']]['export_sortdelim'] = @$_POST['export_sortdelim'];
    $memory[$_GET['path']]['export_sortsdelim'] = @$_POST['export_sortsdelim'];
    $memory[$_GET['path']]['export_imgdelim'] = @$_POST['export_imgdelim'];
    $memory[$_GET['path']]['export_imgpath'] = @$_POST['export_imgpath'];
    $memory[$_GET['path']]['export_uniq'] = @$_POST['export_uniq'];
    $memory[$_GET['path']]['export_action'] = @$_POST['export_action'];
    $memory[$_GET['path']]['export_delim'] = @$_POST['export_delim'];
    $memory[$_GET['path']]['export_imgproc'] = @$_POST['export_imgproc'];
    $memory[$_GET['path']]['export_imgload'] = @$_POST['export_imgload'];

    // �������� csv �� ������������
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if ($_FILES['file']['ext'] == "csv") {
            if (@move_uploaded_file($_FILES['file']['tmp_name'], "csv/" . PHPShopString::toLatin($_FILES['file']['name']).'.'.$_FILES['file']['ext'])) {
                $csv_file_name = PHPShopString::toLatin($_FILES['file']['name']).'.'.$_FILES['file']['ext'];
                $csv_file = "csv/" . $csv_file_name;
                $_POST['lfile'] = $GLOBALS['dir']['dir'] . "/phpshop/admpanel/csv/" . $csv_file_name;
            } else
                $result_message = $PHPShopGUI->setAlert(__('������ ���������� �����') . ' <strong>' . $csv_file_name . '</strong> � phpshop/admpanel/csv', 'danger');
        }
    }

    // ������ csv �� URL
    elseif (!empty($_POST['furl'])) {

        // Google
        $path = parse_url($_POST['furl']);
        if ($path['host'] == 'docs.google.com') {
            $a_path = explode("/", $path['path']);
            if (is_array($a_path)) {
                $id = $a_path[3];

                if ($id == 'e') {
                    $id = $a_path[4];
                    $csv_file = $_POST['furl'];
                } else
                    $csv_file = 'https://docs.google.com/spreadsheets/d/' . $id . '/export?format=csv&' . $path['fragment'];

                $csv_file_name = 'Google ������ ' . $_POST['exchanges_new'] . $exchanges_name;
                $_POST['export_code'] = 'utf';
                $delim = ',';
            }
        }
        // Url
        else {
            $csv_file = $_POST['furl'];
            $path_parts = pathinfo($csv_file);
            $csv_file_name = $path_parts['basename'];
        }
    }

    // ������ csv �� ��������� ���������
    elseif (!empty($_POST['lfile'])) {
        $csv_file = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $_POST['lfile'];
        $path_parts = pathinfo($csv_file);
        $csv_file_name = $path_parts['basename'];
    }
    // �������������
    elseif (!empty($_POST['csv_file'])) {
        $csv_file = $_POST['csv_file'];
        $path_parts = pathinfo($csv_file);
        $csv_file_name = $path_parts['basename'];
    }


    // ��������� csv
    if (!empty($csv_file)) {
        PHPShopObj::loadClass('file');

        // �������������
        if (!empty($_POST['bot'])) {

            $limit = intval($_POST['line_limit']);

            if (empty($_POST['end']))
                $_POST['end'] = intval($_POST['line_limit']);

            $end = $_POST['end'];

            if (isset($_POST['total']) and $_POST['end'] > $_POST['total'])
                $end = $_POST['total'];

            if (empty($_POST['start']))
                $_POST['start'] = 0;

            // ������ ��������
            if (empty($_POST['total'])) {

                // ����� � �����
                $total = 0;
                $handle = fopen($csv_file, "r");
                while ($data = fgetcsv($handle, 0, $delim)) {
                    $total++;
                }

                $bar = 0;
                $end = 0;
                $csv_load_count = 0;
                $bar_class = "active";

                if ($_POST['export_action'] == 'insert')
                    $do = '�������';
                else
                    $do = '��������';

                $total_min = round($total / $_POST['line_limit'] * $_POST['time_limit']);

                $result_message = $PHPShopGUI->setAlert('<div id="bot_result">' . __('����') . ' <strong>' . $csv_file_name . '</strong> ' . __('��������. ���������� ') . $end . __(' �� ') . $total . __(' �����. ' . $do) . ' <b id="total-update">' . intval($csv_load_count) . '</b> ' . __('�������.') . '</div>
<div class="progress bot-progress">
  <div class="progress-bar progress-bar-striped  progress-bar-success ' . $bar_class . '" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: ' . $bar . '%"> ' . $bar . '% 
  </div>
</div>','success load-result',true,false,false);
                $result_message .= $PHPShopGUI->setAlert('<b>����������, �� ���������� ���� �� ������ �������� �������</b><br>
�� ������ ���������� ������ � ������� ��������� �����, �������� ���� � ����� ������� (������� <kbd>CTRL</kbd> � �������� �� ������).', 'info load-info',true,false,false);
                $result_message .= $PHPShopGUI->setInput("hidden", "csv_file", $csv_file);
                $result_message .= $PHPShopGUI->setInput("hidden", "total", $total);
                $result_message .= $PHPShopGUI->setInput("hidden", "stop", 0);
            } else {

                $result = PHPShopFile::readCsvGenerators($csv_file, 'csv_update', $delim, array($_POST['start'], $_POST['end']));
                if ($result) {

                    $total = $_POST['total'];

                    $bar = round($_POST['line_limit'] * 100 / $total);

                    // �����
                    if ($end > $total) {
                        $end = $total;
                        $bar = 100;
                        $bar_class = null;
                    } else {
                        $bar_class = "active";
                    }

                    if ($_POST['export_action'] == 'insert')
                        $lang_do = '�������';
                    else
                        $lang_do = '��������';

                    if ($csv_load_count < 0)
                        $csv_load_count = 0;

                    $total_min = round(($total - $csv_load_count) / $_POST['line_limit'] * $_POST['time_limit']);
                    $action = true;
                    $json_message = __('����') . ' <strong>' . $csv_file_name . '</strong> ' . __('��������. ���������� ') . $end . __(' �� ') . $total . __(' �����. ' . $lang_do) . ' <b id="total-update">' . intval($csv_load_count) . '</b> ' . __('�������.');

                    // ���� ��������
                    if ($_POST['line_limit'] >= 10) {
                        $result_csv = './csv/result_' . date("d_m_y_His") . '.csv';
                        PHPShopFile::writeCsv($result_csv, $GLOBALS['csv_load']);
                    }

                    // ������ ��� �������
                    $csv_load_totale = $_POST['start'] . '-' . $_POST['end'];
                } else
                    $result_message = $PHPShopGUI->setAlert(__('��� ���� �� ������ �����') . ' ' . $csv_file, 'danger');
            }
        }
        else {

            $result = PHPShopFile::readCsv($csv_file, 'csv_update', $delim);

            if ($result) {

                if (empty($csv_load_count))
                    $result_message = $PHPShopGUI->setAlert(__('����') . ' <strong>' . $csv_file_name . '</strong> ' . __('��������. ���������� ' . $csv_load_totale . ' �����. ��������') . ' <strong>' . intval($csv_load_count) . '</strong> ' . __('�������') . '.', 'warning');
                else {

                    // ���� ��������
                    $result_csv = './csv/result_' . date("d_m_y_His") . '.csv';
                    PHPShopFile::writeCsv($result_csv, $csv_load);

                    if ($_POST['export_action'] == 'insert') {
                        $lang_do = '�������';
                        $lang_do2 = '���������';
                    } else {
                        $lang_do = '��������';
                        $lang_do2 = '�����������';
                    }

                    $result_message = $PHPShopGUI->setAlert(__('����') . ' <strong>' . $csv_file_name . '</strong> ' . __('��������. ���������� ' . $csv_load_totale . ' �����. ' . $lang_do) . ' <strong>' . intval($csv_load_count) . '</strong> ' . __('�������') . '. ' . __('����� �� ' . $lang_do2 . ' �������� ') . ' <a href="' . $result_csv . '" target="_blank">CSV</a>.');
                }
            } else {
                $result = 0;
                $result_message = $PHPShopGUI->setAlert(__('��� ���� �� ������ �����') . ' ' . $csv_file, 'danger');
            }
        }
    }

    // ���������� ���������
    if ($_POST['exchanges'] == 'new' and ! empty($_POST['exchanges_new'])) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges']);
        $PHPShopOrm->insert(array('name_new' => $_POST['exchanges_new'], 'option_new' => serialize($_POST), 'type_new' => 'import'));
    }

    if (!empty($_POST['bot']) and (empty($_POST['total']) or $_POST['line_limit'] < 10))
        $log_off = true;

    // ������ ��������
    if (empty($log_off)) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges_log']);
        $PHPShopOrm->insert(array('date_new' => time(), 'file_new' => $csv_file, 'status_new' => $result, 'info_new' => serialize([$csv_load_totale, $lang_do, (int) $csv_load_count, $result_csv, (int) $img_load]), 'option_new' => serialize($_POST)));
    }

    // �������������
    if (!empty($_POST['ajax'])) {

        if ($total > $end) {

            $bar = round($_POST['end'] * 100 / $total);

            return array("success" => $action, "bar" => $bar, "count" => $csv_load_count, "result" => PHPShopString::win_utf8($json_message), 'limit' => $limit,'action'=>PHPShopString::win_utf8(mb_strtolower($lang_do,$GLOBALS['PHPShopBase']->codBase)));
        } else
            return array("success" => 'done', "count" => $csv_load_count, "result" => PHPShopString::win_utf8($json_message), 'limit' => $limit,'action'=>PHPShopString::win_utf8(mb_strtolower($lang_do,$GLOBALS['PHPShopBase']->codBase)));
    }
}

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopOrm, $key_name, $subpath, $key_base, $key_stop, $result_message;

    // ������� ���������
    if (!empty($_GET['exchanges'])) {

        $PHPShopOrmExchanges = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges']);
        $data_exchanges = $PHPShopOrmExchanges->select(array('*'), array('id' => '=' . intval($_GET['exchanges'])), false, array("limit" => 1));

        if (is_array($data_exchanges)) {
            $_POST = unserialize($data_exchanges['option']);
            $exchanges_name = ": " . $data_exchanges['name'];
        }
    }

    if (!empty($_POST['lfile'])) {
        $memory[$_GET['path']]['export_sortdelim'] = @$_POST['export_sortdelim'];
        $memory[$_GET['path']]['export_sortsdelim'] = @$_POST['export_sortsdelim'];
        $memory[$_GET['path']]['export_imgdelim'] = @$_POST['export_imgdelim'];
        $memory[$_GET['path']]['export_imgpath'] = @$_POST['export_imgpath'];
        $memory[$_GET['path']]['export_imgload'] = @$_POST['export_imgload'];
        $memory[$_GET['path']]['export_uniq'] = @$_POST['export_uniq'];
        $memory[$_GET['path']]['export_action'] = @$_POST['export_action'];
        $memory[$_GET['path']]['export_delim'] = @$_POST['export_delim'];
        $memory[$_GET['path']]['export_imgproc'] = @$_POST['export_imgproc'];
        $memory[$_GET['path']]['export_code'] = @$_POST['export_code'];
        $memory[$_GET['path']]['bot'] = @$_POST['bot'];

        $export_sortdelim = @$memory[$_GET['path']]['export_sortdelim'];
        $export_sortsdelim = @$memory[$_GET['path']]['export_sortsdelim'];
        $export_imgvalue = @$memory[$_GET['path']]['export_imgdelim'];
        $export_code = $memory[$_GET['path']]['export_code'];
    }
    // ��������� �� ���������
    else {
        $memory[$_GET['path']]['export_imgload'] = 1;
        $memory[$_GET['path']]['export_imgproc'] = 1;

        $_POST['line_limit'] = 1;

        if ($_GET['path'] == 'exchange.import')
            $_POST['bot'] = 1;
        
        if($subpath[2] == 'catalog')
            $memory[$_GET['path']]['export_action']='insert';
    }


    $PHPShopGUI->action_button['������'] = array(
        'name' => __('���������'),
        'action' => 'saveID',
        'class' => 'btn btn-primary btn-sm navbar-btn',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-save'
    );

    $list = null;
    $PHPShopOrm->clean();
    $data = $PHPShopOrm->select(array('*'), false, false, array('limit' => 1));
    $select_value[] = array('�� �������', false, false);

    // ������ ����
    if (!is_array($data)) {
        $PHPShopOrm->insert(array('name_new' => '�������� �����'));
        $PHPShopOrm->clean();
        $data = $PHPShopOrm->select(array('*'), false, false, array('limit' => 1));
        $PHPShopOrm->delete(array('name' => '="�������� �����"'));
        
       if(empty($subpath[2]))
         $memory[$_GET['path']]['export_action']='insert';
    }

    if (is_array($data)) {

        // ���� ��������
        if (empty($subpath[2])) {
            $data['path'] = null;
        }

        foreach ($data as $key => $val) {

            if (!empty($key_name[$key]))
                $name = $key_name[$key];
            else
                $name = $key;

            if (@in_array($key, $key_base)) {
                if ($key == 'id')
                    $kbd_class = 'enabled';
                else
                    $kbd_class = null;

                $list .= '<div class="pull-left" style="width:190px;min-height: 19px;"><kbd class="' . $kbd_class . '">' . ucfirst($name) . '</kbd></div>';
                $help = 'data-subtext="<span class=\'glyphicon glyphicon-flag text-success\'></span>"';
            }
            elseif (!in_array($key, $key_stop)) {
                $list .= '<div class="pull-left" style="width:190px;min-height: 19px;">' . ucfirst($name) . '</div>';
                $help = null;
            }

            if (!in_array($key, $key_stop)) {
                $select_value[] = array(ucfirst($name), ucfirst($name), false, $help);

                // ���� ���������
                if ($key != 'id' and $key != 'uid' and $key != 'vendor' and $key != 'vendor_array')
                    $key_value[] = array(ucfirst($name), $key, false);
            }
        }
    } else
        $list = '<span class="text-warning hidden-xs">' . __('������������ ������ ��� �������� ����� �����. �������� ���� ������ � ������ ������� � ������ ������ ��� ������ ������') . '.</span>';

    // ������ �������� ����
    $PHPShopGUI->field_col = 3;
    $PHPShopGUI->addJSFiles('./exchange/gui/exchange.gui.js');
    $PHPShopGUI->_CODE = $result_message;

    // ������
    if (empty($subpath[2])) {
        $class = false;
        $TitlePage .= ' ' . __('�������');
        $data['path'] = null;
    }

    // ��������
    elseif ($subpath[2] == 'catalog') {
        $class = 'hide';
        $TitlePage .= ' ' . __('���������');
    }

    // ������������
    elseif ($subpath[2] == 'user') {
        $class = 'hide';
        $TitlePage .= ' ' . __('�������������');
    }

    // ������������
    elseif ($subpath[2] == 'order') {
        $class = 'hide';
    }

    $PHPShopGUI->setActionPanel($TitlePage . $exchanges_name, false, array('������'));

    $delim_value[] = array('����� � �������', ';', @$memory[$_GET['path']]['export_delim']);
    $delim_value[] = array('�������', ',', @$memory[$_GET['path']]['export_delim']);

    $action_value[] = array('����������', 'update', @$memory[$_GET['path']]['export_action']);
    $action_value[] = array('��������', 'insert', @$memory[$_GET['path']]['export_action']);

    $delim_sortvalue[] = array('#', '#', $export_sortdelim);
    $delim_sortvalue[] = array('@', '@', $export_sortdelim);
    $delim_sortvalue[] = array('$', '$', $export_sortdelim);
    $delim_sortvalue[] = array(__('�������'), '-', $export_sortdelim);

    $delim_sort[] = array('/', '/', $export_sortsdelim);
    $delim_sort[] = array('\\', '\\', $export_sortsdelim);
    $delim_sort[] = array('-', '-', $export_sortsdelim);
    $delim_sort[] = array('&', '&', $export_sortsdelim);
    $delim_sort[] = array(';', ';', $export_sortsdelim);
    $delim_sort[] = array(',', ',', $export_sortsdelim);

    $delim_imgvalue[] = array(__('��������������'), 0, $export_imgvalue);
    $delim_imgvalue[] = array(__('�������'), ',', $export_imgvalue);
    $delim_imgvalue[] = array(__('����� � �������'), ';', $export_imgvalue);
    $delim_imgvalue[] = array('#', '#', $export_imgvalue);
    $delim_imgvalue[] = array(__('������'), ' ', $export_imgvalue);

    $code_value[] = array('ANSI', 'ansi', $export_code);
    $code_value[] = array('UTF-8', 'utf', $export_code);

    $key_value[] = array('Id ��� �������', 0, 'selected');

    // �������� 1
    $Tab1 = $PHPShopGUI->setField("����", $PHPShopGUI->setFile($_POST['lfile'])) .
            $PHPShopGUI->setField('��������', $PHPShopGUI->setSelect('export_action', $action_value, 150, true)) .
            $PHPShopGUI->setField('CSV-�����������', $PHPShopGUI->setSelect('export_delim', $delim_value, 150, true)) .
            $PHPShopGUI->setField('����������� ��� �������������', $PHPShopGUI->setSelect('export_sortdelim', $delim_sortvalue, 150), false, false, $class) .
            $PHPShopGUI->setField('����������� �������� �������������', $PHPShopGUI->setSelect('export_sortsdelim', $delim_sort, 150), false, false, $class) .
            $PHPShopGUI->setField('��������� �����������', $PHPShopGUI->setCheckbox('export_imgproc', 1, null, @$memory[$_GET['path']]['export_imgproc']), 1, '�������� ��������� � ����������', $class) .
            $PHPShopGUI->setField('�������� �����������', $PHPShopGUI->setCheckbox('export_imgload', 1, null, @$memory[$_GET['path']]['export_imgload']), 1, '�������� ����������� �� ������ �� ������', $class) .
            $PHPShopGUI->setField('����������� ��� �����������', $PHPShopGUI->setSelect('export_imgdelim', $delim_imgvalue, 150), 1, '�������������� �����������', $class) .
            $PHPShopGUI->setField('��������� ������', $PHPShopGUI->setSelect('export_code', $code_value, 150)) .
            $PHPShopGUI->setField('���� ����������', $PHPShopGUI->setSelect('export_key', $key_value, 150, false, false, true), 1, '��������� ����� ���������� ����� �������� � ����� ������', $class) .
            $PHPShopGUI->setField('�������� ������������', $PHPShopGUI->setCheckbox('export_uniq', 1, null, @$memory[$_GET['path']]['export_uniq']), 1, '��������� ������������ ������ ��� ��������', $class);

    // ������
    if (is_array($_POST['select_action'])) {
        foreach ($_POST['select_action'] as $x => $p)
            if (is_array($select_value)) {
                $select_value_pre = [];
                foreach ($select_value as $k => $v) {

                    if ($v[0] == $p or ( strstr($v[0], '@') and strstr($p, '@')))
                        $v[2] = 'selected';
                    else
                        $v[2] = null;

                    $select_value_pre[] = [$v[0], $v[1], $v[2], $v[3]];
                }
                ${'select_value' . ($x + 1)} = $select_value_pre;
            }
    }else {
        $n = 1;
        while ($n < 21) {
            ${'select_value' . ($n)} = $select_value;
            $n++;
        }
    }

    // �������� 2
    $Tab2 = $PHPShopGUI->setField(array('������� A', '������� B'), array($PHPShopGUI->setSelect('select_action[]', $select_value1, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value2, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('������� C', '������� D'), array($PHPShopGUI->setSelect('select_action[]', $select_value3, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value4, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('������� E', '������� F'), array($PHPShopGUI->setSelect('select_action[]', $select_value5, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value6, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('������� G', '������� H'), array($PHPShopGUI->setSelect('select_action[]', $select_value7, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value8, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('������� I', '������� J'), array($PHPShopGUI->setSelect('select_action[]', $select_value9, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value10, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('������� K', '������� L'), array($PHPShopGUI->setSelect('select_action[]', $select_value11, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value12, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('������� M', '������� N'), array($PHPShopGUI->setSelect('select_action[]', $select_value13, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value14, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('������� O', '������� P'), array($PHPShopGUI->setSelect('select_action[]', $select_value15, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value16, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('������� Q', '������� R'), array($PHPShopGUI->setSelect('select_action[]', $select_value17, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value18, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('������� S', '������� T'), array($PHPShopGUI->setSelect('select_action[]', $select_value19, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value20, 150, true, false, true)), array(array(3, 2), array(2, 2)));

    // �������� 3
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges']);
    $data = $PHPShopOrm->select(array('*'), array('type' => '="import"'), array('order' => 'id DESC'), array("limit" => "1000"));
    $exchanges_value[] = array(__('������� ����� ���������'), 'new');
    if (is_array($data)) {
        foreach ($data as $row) {
            $exchanges_value[] = array($row['name'], $row['id'], $_REQUEST['exchanges']);
            $exchanges_remove_value[] = array($row['name'], $row['id']);
        }
    } else
        $exchanges_remove_value = null;

    $Tab3 = $PHPShopGUI->setField('������� ���������', $PHPShopGUI->setSelect('exchanges', $exchanges_value, 300, false));
    $Tab3 .= $PHPShopGUI->setField('��������� ���������', $PHPShopGUI->setInputArg(array('type' => 'text', 'placeholder' => '��� ���������', 'size' => '300', 'name' => 'exchanges_new', 'class' => 'vendor_add')));

    if (is_array($exchanges_remove_value))
        $Tab3 .= $PHPShopGUI->setField('������� ���������', $PHPShopGUI->setSelect('exchanges_remove[]', $exchanges_remove_value, 300, false, false, false, false, 1, true));

    // �������� 4
    if (empty($_POST['time_limit']))
        $_POST['time_limit'] = 10;

    if (empty($_POST['line_limit']))
        $_POST['line_limit'] = 50;

    if (empty($_POST['bot']))
        $_POST['bot'] = null;

    $Tab4 = $PHPShopGUI->setField('����� �����', $PHPShopGUI->setInputText(null, 'line_limit', $_POST['line_limit'], 150), 1, '������� �� �������� ��������');
    //$Tab4 .= $PHPShopGUI->setField('��������� ��������', $PHPShopGUI->setInputText(null, 'time_limit', $_POST['time_limit'], 150, __('������')), 1, '������� �� �������� ��������');
    //$Tab4 .= $PHPShopGUI->setInput("hidden", "line_limit", $_POST['line_limit']);
    $Tab4 .= $PHPShopGUI->setField("��������", $PHPShopGUI->setCheckbox('bot', 1, __('����� �������� ��� ���������� ������� ����������� �� ��������'), @$_POST['bot'], false, false));

    $Tab1 = $PHPShopGUI->setCollapse('���������', $Tab1);
    $Tab2 = $PHPShopGUI->setCollapse('���������', $PHPShopGUI->setHelp('���� �� ���������� ����, ������� ������� � ���� "����" &rarr; "������� ����", � �� �������� <a name="import-col-name" href="#">������� ��������� ��������</a> � ������������� ����� ������ <b>�� �����</b>. ���� ��� ��������� ����� �� ������ ���������� �������, �������� <b>C������������ �����</b>.<div style="margin-top:10px" id="import-col-name" class="none panel panel-default"><div class="panel-body">' . $list . '</div></div>')) .
            $PHPShopGUI->setCollapse('������������� �����', $Tab2);

    $Tab3 = $PHPShopGUI->setCollapse('����������� ���������', $Tab3);
    $Tab4 = $PHPShopGUI->setCollapse('�������������', $Tab4);

    $Tab5 = $PHPShopGUI->loadLib('tab_log', $data, 'exchange/');
    if (!empty($Tab5))
        $Tab5_status = false;
    else
        $Tab5_status = true;

    $PHPShopGUI->tab_return = true;
    $PHPShopGUI->setTab(array('���������', $Tab1, true), array('������������� �����', $Tab2, true), array('����������� ���������', $Tab3, true), array('�������������', $Tab4, true), array('������� ��������', $Tab5, true, $Tab5_status));

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", true, "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "���������", "right", 70, "", "but", "actionUpdate.exchange.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionSave.exchange.edit");

    $PHPShopGUI->setFooter($ContentFooter);

    $help = '<p class="text-muted data-row">' . __('��� ������� ������ ����� �������') . ' <a href="?path=exchange.export"><span class="glyphicon glyphicon-share-alt"></span>' . __('������ �����') . '</a>' . __(', ������ ������ ��� ����. ����� �������� ��� �������� ������ ����������, �� ������� ���������, � �������� ����') . ' <em> ' . __('"������ ������"') . '</em></p>';

    $sidebarleft[] = array('title' => '��� ������', 'content' => $PHPShopGUI->loadLib('tab_menu', false, './exchange/'));
    $sidebarleft[] = array('title' => '���������', 'content' => $help, 'class' => 'hidden-xs');

    $PHPShopGUI->setSidebarLeft($sidebarleft, 2);

    // �����
    $PHPShopGUI->Compile(2);

    return true;
}

// ��������� �������������
class sortCheck {

    var $debug = false;

    function __construct($name, $value, $category,$debug=false) {
        
        $this->debug = $debug;

        $this->debug('���� �������������� "' . $name . '" = "' . $value . '" � �������� � ID=' . $category);

        // �������� ����� �������������� 
        $check_name = (new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']))->getOne(['*'], ['name' => '="' . $name . '"']);
        if ($check_name) {

            $this->debug('���� �������������� "' . $name . '" c ID=' . $check_name['id'] . ' � CATEGORY=' . $check_name['category']);

            // �������� �������� ��������������
            $check_value = (new PHPShopOrm($GLOBALS['SysValue']['base']['sort']))->getOne(['*'], ['name' => '="' . $value . '"']);
            if ($check_value) {
                $this->debug('���� �������� �������������� "' . $name . '" = "' . $value . '" c ID=' . $check_value['id']);

                // �������� ��������� ������ ��������������
                $check_category = (new PHPShopOrm($GLOBALS['SysValue']['base']['categories']))->getOne(['*'], ['id' => '="' . $category . '"']);
                $sort = unserialize($check_category['sort']);

                if (is_array($sort) and in_array($check_name['id'], $sort)) {
                    $this->debug('���� ����� �������������� "' . $name . '" = "' . $value . '" c ID=' . $check_value['id'] . ' � �������� ' . $check_category['name'] . '" � ID=' . $category);
                } else {
                    $sort_categories = (new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']))->getOne(['*'], ['id' => '=' . $check_name['category']]);
                    $this->debug('��� ����� �������������� "' . $sort_categories['name'] . '" c ID=' . $check_name['category'] . ' � �������� ' . $check_category['name'] . '" � ID=' . $category);

                    // ���������� � ��������� ������ ��������������
                    $sort[] = $check_name['id'];
                    (new PHPShopOrm($GLOBALS['SysValue']['base']['categories']))->update(['sort_new' => serialize($sort)], ['id' => '=' . $category]);
                    $this->debug('����� ������������� "' . $sort_categories['name'] . '" c ID=' . $check_name['category'] . ' �������� � ������� "' . $check_category['name'] . '" � ID=' . $category);

                    $result[$check_name['id']][] = $check_value['id'];
                }
                $result[$check_name['id']][] = $check_value['id'];
            } else {
                $this->debug('��� �������� �������������� "' . $name . '" = "' . $value . '"');

                // �������� ������ �������� ��������������
                $new_value_id = (new PHPShopOrm($GLOBALS['SysValue']['base']['sort']))->insert(['name_new' => $value, 'category_new' => $check_name['id'],'sort_seo_name_new'=>str_replace("_", "-",PHPShopString::toLatin($value))]);

                $this->debug('�������� ������ �������� �������������� "' . $name . '" = "' . $value . '" c ID=' . $new_value_id);
                $result[$check_name['id']][] = $new_value_id;
            }
        } else {

            $this->debug('��� �������������� "' . $name . '"');

            // �������� ��������� ������ ��������������
            $check_category = (new PHPShopOrm($GLOBALS['SysValue']['base']['categories']))->getOne(['*'], ['id' => '="' . $category . '"']);
            $sort = unserialize($check_category['sort']);

            // � �������� ���� ��������������
            if (is_array($sort)) {

                // �������� �������� ��������������
                foreach ($sort as $val) {
                    $check_value = (new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']))->getOne(['*'], ['id' => '=' . $val]);
                    if (!empty($check_value['category'])) {
                        $sort_categories = $check_value['category'];
                        continue;
                    }
                }

                $this->debug('������ ����� ������������� c ID=' . $sort_categories);
            }
            // � �������� ��� ������ �������������
            else {

                // �������� ������ ������ �������������
                $new_sort_categories_name = $check_category['name'];
                $new_sort_categories = (new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']))->insert(['name_new' => $new_sort_categories_name, 'category_new' => 0]);
                $sort_categories = $new_sort_categories;
                $this->debug('�������� ������ ����� ������������� "' . $new_sort_categories_name . '" c ID=' . $sort_categories . ' ');
            }

            // �������� ����� �������������� 
            $new_name_id = (new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']))->insert(['name_new' => $name, 'category_new' => $sort_categories]);
            $this->debug('�������� ����� �������������� "' . $name . '" c ID=' . $new_name_id . ' � ������ ������������� ID=' . $sort_categories);

            // �������� ������ �������� ��������������
            $new_value_id = (new PHPShopOrm($GLOBALS['SysValue']['base']['sort']))->insert(['name_new' => $value, 'category_new' => $new_name_id,'sort_seo_name_new'=>str_replace("_", "-",PHPShopString::toLatin($value))]);
            $this->debug('�������� ������ �������� �������������� "' . $name . '" = "' . $value . '" c ID=' . $new_value_id);

            // ���������� � ��������� ��������������
            $sort[] = $new_name_id;
            (new PHPShopOrm($GLOBALS['SysValue']['base']['categories']))->update(['sort_new' => serialize($sort)], ['id' => '=' . $category]);
            $this->debug('�������������� "' . $name . '" c ID=' . $new_name_id . ' �������� � ������� "' . $check_category['name'] . '" � ID=' . $category);

            $result[$new_name_id][] = $new_value_id;
        }

        $this->result = $result;
    }

    // �������
    function debug($str) {
        if ($this->debug)
            echo $str . PHP_EOL . '<br>';
    }
    
    // ���������
    function result(){
        return $this->result;
    }
}

// ��������� �������
$PHPShopGUI->getAction();
?>