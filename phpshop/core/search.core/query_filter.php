<?php

function query_multibase($obj) {

    $multi_cat = null;

    // Мультибаза
    if (defined("HostID")) {

        $where['servers'] = " REGEXP 'i" . HostID . "i'";
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
        $PHPShopOrm->debug = $obj->debug;
        $data = $PHPShopOrm->select(array('id'), $where, false, array('limit' => 100));
        if (is_array($data)) {
            foreach ($data as $row) {
                $multi_cat.='category=' . $row['id'] . ' or ';
            }
            $multi_cat = substr($multi_cat, 0, strlen($multi_cat) - 4);
            $multi_cat = ' and (' . $multi_cat . ')';
        }
    }

    return $multi_cat;
}

/**
 * Составление SQL запроса для поиска товара
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopCoreFunction
 * @param obj $obj объект класса
 * @return mixed
 */
function query_filter($obj) {
    global $SysValue;

    if (!empty($_REQUEST['v']))
        $v = $_REQUEST['v'];
    else
        $v = null;

    if (!empty($_REQUEST['set']))
        $set = intval($_REQUEST['set']);
    else
        $set = 2;

    if (!empty($_REQUEST['pole']))
        $pole = intval($_REQUEST['pole']);
    else
        $pole = 1;

    if (!empty($_REQUEST['p']))
        $p = intval($_REQUEST['p']);
    else
        $p = 1;

    $cat = intval(@$_REQUEST['cat']);
    $words = trim(PHPShopSecurity::true_search(@$_REQUEST['words']));
    $num_row = $obj->num_row;
    $num_ot = $q = 0;

    $sortV = $sort = null;

    // Сортировка по характеристикам
    if (empty($_POST['v']))
        @$v = $SysValue['nav']['query']['v'];
    if (is_array($v))
        foreach ($v as $key => $value) {
            if (!empty($value)) {
                $hash = $key . "-" . $value;
                $sortV.=" and vendor REGEXP 'i" . $hash . "i' ";
            }
        }

    // Чистка запроса Secure Fix
    $words = PHPShopSecurity::true_search(PHPShopSecurity::TotalClean($words, 2));

    // Разделяем слова
    $_WORDS = explode(" ", $words);


    // Ajax поиск        
    if (!empty($_POST['ajax'])) {
        foreach ($_WORDS as $w)
            $sort.="(name REGEXP '\x20*$w' or uid REGEXP '^$w' or keywords REGEXP '$w') or ";
    }
    // Обычный поиск
    else {
        foreach ($_WORDS as $w)
            $wrd .= '%' . $w;

        $wrd .='%';

        switch ($pole) {
            case(1):
                $sort.="(name LIKE '$wrd' or keywords LIKE '$wrd' or id LIKE '$wrd') and ";
                break;

            case(2):
                $sort.="(name LIKE '$wrd' or content LIKE '$wrd' or description LIKE '$wrd' or keywords LIKE '$wrd' or uid LIKE '$wrd') and ";
                break;
        }
    }

    $sort = substr($sort, 0, strlen($sort) - 4);

    // По категориям
    if ($cat != 0)
        $string = " category=$cat and";
    else
        $string = null;

    // Перенаправление поиска
    $prewords = search_base($obj, $words);

    // Мультибаза
    $multibase = query_multibase($obj);

    // Все страницы
    if ($p == "all") {
        $sql = "select * from " . $SysValue['base']['table_name2'] . " where $sort $prewords $multibase and enabled='1' and parent_enabled='0' order by num, name";
    }
    else
        while ($q < $p) {

            $sql = "select * from " . $SysValue['base']['table_name2'] . " where  $string ($sort) $prewords $sortV $multibase and enabled='1' and parent_enabled='0' order by num, name LIMIT $num_ot, $num_row";
            $q++;
            $num_ot = $num_ot + $num_row;
        }

    $obj->search_order = array(
        'words' => $words,
        'pole' => $pole,
        'set' => $set,
        'cat' => $cat,
        'string' => $string,
        'sort' => $sort,
        'prewords' => $prewords,
        'sortV' => $sortV
    );

    $obj->set('searchString', $words);

    if ($set == 1)
        $obj->set('searchSetA', 'checked');
    elseif ($set == 2)
        $obj->set('searchSetB', 'checked');
    else
        $obj->set('searchSetA', 'checked');

    if ($pole == 1)
        $obj->set('searchSetC', 'checked');
    elseif ($pole == 2)
        $obj->set('searchSetD', 'checked');
    else
        $obj->set('searchSetC', 'checked');

    // Возвращаем SQL запрос
    return $sql;
}

/**
 * Выдача переадресации поиска из БД
 * @package PHPShopCoreFunction
 * @param obj $obj объект класса
 * @param string $words строка поиска
 * @return string 
 */
function search_base($obj, $words) {
    $string = null;

    $PHPShopOrm = new PHPShopOrm();
    $PHPShopOrm->mysql_error = false;
    $PHPShopOrm->debug = $obj->debug;
    $result = $PHPShopOrm->query("select * from " . $GLOBALS['SysValue']['base']['search_base'] . " where name REGEXP 'i" . PHPShopSecurity::true_search($words) . "i'  and enabled='1' limit 1");
    $row = mysqli_fetch_array($result);

    // Переадресация на товары
    if (!empty($row['uid'])) {

        $uid = $row['uid'];
        if (strstr($row['uid'], ','))
            $uids = explode(",", $uid);
        else
            $uids[] = $uid;

        if (is_array($uids))
            $string = ' or id IN (' . @implode(",", $uids) . ') ';

        return $string;
    }
    // Переадресация на категорию
    else if (!empty($row['category'])) {
        header('Location: /' . $GLOBALS['dir']['dir'] . 'shop/CID_' . $row['category'] . '.html');
    }
}

?>