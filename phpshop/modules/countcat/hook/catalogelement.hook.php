<?php

//$countcat['34'] = countcat_hook_recurs_calculate(34);
function countcat_hook_recurs_calculate($row, $obj) {
    global $countcat;
    if ($row['count'] != 0)
        return $row['count'];

    if ($obj->chek($row['id'])) {
        // если нет подкаталогов
        $count = subcatalog_countcat_hook_get_count($row['id']);
        if ($count)
            subcatalog_countcat_hook_set_count($row['id'], $count);
        return $count;
    } else {
        // если есть подкаталоги
        // получаем данные подкаталогов в значениях массива
        $subcatArr = countcat_hook_getSubcatalogIds($row['id'], $obj);
        foreach ($subcatArr as $value) {
            $count_temp = countcat_hook_recurs_calculate($value, $obj); //рекурсия
            $countcat[$value['id']] = $count_temp;
            if ($count_temp)
                subcatalog_countcat_hook_set_count($value['id'], $count_temp);
            $count += $count_temp;
        }
        if ($count)
            subcatalog_countcat_hook_set_count($row['id'], $count);
        return $count;
    }
}

function leftCatal_countcat_hookMiddle($obj, $row, $rout) {
    global $countcat, $countcat_hook_option;
    if ($rout == 'MIDDLE') {
        $countcat[$row['id']] = countcat_hook_recurs_calculate($row, $obj);
    }
    
    if ($rout == 'END') {
        if (empty($countcat_hook_option['enabled']))
            $obj->set('catalogCount', $row['count']);
        else
            $obj->set('catalogName', $row['name'] . ' [' . $row['count'] . ']');
    }
}

function countcat_hook_getSubcatalogIds($n, $obj) {


    $dis = null;

    $PHPShopOrm = new PHPShopOrm($obj->objBase);
    $PHPShopOrm->cache_format = $obj->cache_format;
    $PHPShopOrm->cache = $obj->cache;
    $PHPShopOrm->debug = $obj->debug;

    $where['parent_to'] = '=' . $n;

    // Не выводить скрытые каталоги
    $where['skin_enabled'] = "!='1'";

    // Мультибаза
    if (defined("HostID")){
        $where['servers'] = " REGEXP 'i" . HostID . "i'";
    }

    $data = $PHPShopOrm->select(array('*'), $where, false, array('limit' => 100), __CLASS__, __FUNCTION__);

    if (is_array($data)) {
        foreach ($data as $row) {
            $ret[] = $row;
        }
    } else {
        $ret = array();
    }
    return $ret;
}

function countcat_hook_option() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['countcat']['countcat_system']);
    $data = $PHPShopOrm->select(array('enabled'), false, array('order' => 'id DESC'), array('limit' => 1));
    return $data['enabled'];
}

function subcatalog_countcat_hook_get_count($cat) {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->select(array('COUNT(id) as count'), array('category' => '=' . intval($cat), 'enabled' => "='1'"), false, array('limit' => 1));
    return $action['count'];
}

function subcatalog_countcat_hook_set_count($cat, $count) {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
    $PHPShopOrm->debug = false;
    $PHPShopOrm->update(array('count_new' => $count), array('id' => '=' . intval($cat)));
}


function subcatalog_countcat_hook($obj, $row) {
    global $countcat, $countcat_hook_option;
   // Настройки модуля
    if(!$row['count'])
        $countcat[$row['id']] = countcat_hook_recurs_calculate($row, $obj);
    
    if (empty($countcat_hook_option))
        $countcat_hook_option['enabled'] = countcat_hook_option();

    if (empty($countcat_hook_option['enabled']))
        $obj->set('catalogCount', '' . $row['count'] . '');
    else
        $obj->set('catalogName', $row['name'] . ' [' . $row['count'] . ']');
}

$addHandler = array
    (
    'subcatalog' => 'subcatalog_countcat_hook',
    'leftCatal' => 'leftCatal_countcat_hookMiddle'
);
?>