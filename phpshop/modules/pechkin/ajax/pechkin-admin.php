<?php

/**
 * Печкин
 * @package PHPShopAjaxElements
 */
session_start();

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
include("../class/PHPechkin.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("cart");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("user");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("file");


// Подключаем библиотеку поддержки JsHttpRequest
if($_REQUEST['type'] != 'json'){
    require_once $_classPath . "/lib/Subsys/JsHttpRequest/Php.php";
    $JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");
}
else{
    //$_REQUEST['promocode']=PHPShopString::utf8_win1251($_REQUEST['promocode']);
}

//Действие
$get = $_REQUEST['get'];
$subscribe = $_REQUEST['subscribe'];
//Переменные
$login = $_REQUEST['login'];
$pass = $_REQUEST['pass'];
//Адресная база
$list_id = $_REQUEST['list_id'];
//Параметры
$merge1_new = $_REQUEST['merge1_new'];
$merge2_new = $_REQUEST['merge2_new'];
$merge3_new = $_REQUEST['merge3_new'];
$merge4_new = $_REQUEST['merge4_new'];
$merge5_new = $_REQUEST['merge5_new'];
//Массив параметров
$merge_ar_w = $merge1_new.'::'.$merge2_new.'::'.$merge3_new.'::'.$merge4_new.'::'.$merge5_new;
//Галочка автозагрузки
$autoload = $_REQUEST['autoload'];
//Лимиты
$limit = 300; //По умолчанию (по 300 пользователей)
$limit_start = $_REQUEST['limitstart'];
//Счетчик
$add_users_count = $_REQUEST['add_users_count'];

//Типы
if($subscribe==1) {
    $type='1'; //Подписчики
}
else {
    $type='2'; //пользователи
}

// Функции для заказа
$PHPShopOrder = new PHPShopOrderFunction();

// Модули
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

if($get=='auth') {
    //Печкин
    $PHPechkin = new PHPechkin();
    $PHPechkin->__construct($login,$pass);

    //Авторизация
    $lists_get = $PHPechkin->lists_get();


    if($lists_get['row']['id']!='') {
        $lists_get_new[0] = $lists_get['row'];
    }
    else {
        $lists_get_new = $lists_get['row'];
    }


    if(isset($lists_get)):
        if(isset($lists_get_new[0]['id'])):
            //Успешная авторизация
            $status = 1;
            //Запись сессии
            $_SESSION['pechkinID'] = $lists_get['row']['id'];
            $_SESSION['pechkinLogin'] = $login;
            $_SESSION['pechkinPass'] = $pass;
        else:
            //Ошибка авторизации
            $status = 0;
        endif;
    endif;
}
if($get=='exit') {
    unset($_SESSION['pechkinID']);
    unset($_SESSION['pechkinLogin']);
    unset($_SESSION['pechkinPass']);
}

if($get=='importshopusers') {
    if($_SESSION['pechkinLogin']!='') {

        $status_shopusers_array = explode(':', $_REQUEST['statususers']);
        $cou = 1;
        foreach ($status_shopusers_array as $status_id) {
            if($status_id!='') {
                if($cou==1) {
                    $where = 'WHERE status='.$status_id;
                    $cou++;
                }
                else {
                    $where .= ' OR status='.$status_id;
                }
            }
        }

        //Если нужно выполнить импорт пользователей с флажком subscribe
        if($subscribe==1) {
            if($where=='') {
                $where .= 'WHERE subscribe=1';
            }
            else {
                $where .= 'AND subscribe=1';
            }
        }
        

        if($limit_start=='') {
            $limit_start = 0;
        }

        //флажок по умолчанию
        $flagsql = 0;

        //Запрос пользователей
        $tt = 1;
        $select = "SELECT * FROM `phpshop_shopusers` ".$where." LIMIT ".$limit_start.",".$limit;
        //$select = "SELECT * FROM `phpshop_shopusers` ".$where;
        $query = mysql_query($select);
        $shopusers = mysql_fetch_array($query);
        do {
            //Дополнительные параметры
            $data_adres_ar = unserialize($shopusers['data_adres']);
            $data_adres = $data_adres_ar['list'][0];
            if(isset($data_adres)) {
                foreach ($data_adres as $key => $value) {
                    if($key==$merge1_new)
                        $par['merge_1'] = PHPShopString::win_utf8($value);
                    if($key==$merge2_new)
                        $par['merge_2'] = PHPShopString::win_utf8($value);
                    if($key==$merge3_new)
                        $par['merge_3'] = PHPShopString::win_utf8($value);
                    if($key==$merge4_new)
                        $par['merge_4'] = PHPShopString::win_utf8($value);
                    if($key==$merge5_new)
                        $par['merge_5'] = PHPShopString::win_utf8($value);
                }
            }
            //Основные параметры
            $pardop = array('name','datas');
            foreach ($pardop as $name_param) {
                //Формат даты
                if($name_param=='datas'):
                    $shopusers['datas'] = PHPShopDate::dataV($shopusers['datas']);
                endif;

                if($merge1_new==$name_param)
                    $par['merge_1'] = PHPShopString::win_utf8($shopusers[ $name_param ]);
                if($merge2_new==$name_param)
                    $par['merge_2'] = PHPShopString::win_utf8($shopusers[ $name_param ]);
                if($merge3_new==$name_param)
                    $par['merge_3'] = PHPShopString::win_utf8($shopusers[ $name_param ]);
                if($merge4_new==$name_param)
                    $par['merge_4'] = PHPShopString::win_utf8($shopusers[ $name_param ]);
                if($merge5_new==$name_param)
                    $par['merge_5'] = PHPShopString::win_utf8($shopusers[ $name_param ]);
            }

            //Собираем csv
            $csv .= $shopusers['mail'].",".$par['merge_1'].",".$par['merge_2'].",".$par['merge_3'].",".$par['merge_4'].",".$par['merge_5']."\n";

            unset($par['merge_1']);
            unset($par['merge_2']);
            unset($par['merge_3']);
            unset($par['merge_4']);
            unset($par['merge_5']);
            
            if($shopusers['id']!='') {
                //обноляем флажок о информации к базе
                $flagsql = 1;
                //счетчик обновим
                $add_users_count++;
            }

        } 
        while ($shopusers = mysql_fetch_array($query));

        if($flagsql==1) {
            //Соберем файл
            $sorce = "../csv/shopu.csv";
            PHPShopFile::write($sorce, $csv);

            //Параметры
            $param['merge_1'] = 1;
            $param['merge_2'] = 2;
            $param['merge_3'] = 3;
            $param['merge_4'] = 4;
            $param['merge_5'] = 5;
            $param['type'] = 'csv';

            //Печкин
            $PHPechkin = new PHPechkin();
            $PHPechkin->__construct($_SESSION['pechkinLogin'],$_SESSION['pechkinPass']);

            

            //Автозагрузка
            if($autoload==1) {
                //Если стоит галочка автозагрузки
                if($_SESSION['auload']!=1) {
                    //Узнаем имя
                    $lists_get_n = $PHPechkin->lists_get($list_id);
                    $base_name = PHPShopString::utf8_win1251($lists_get_n['row']['name']);

                    $sel_autoload = 'SELECT * FROM `phpshop_modules_pechkin_autoload` WHERE type='.$type.' AND list_id='.$list_id;
                    $qu_autoload = mysql_query($sel_autoload);
                    $auload = mysql_fetch_array($qu_autoload);
                    if($auload[0]=='') {
                        //Если еще нет, то создадим
                        mysql_query("INSERT INTO `phpshop_modules_pechkin_autoload` (`id` ,`list_id` , `base_name` , `where`,`param`,`login`,`pass`,`type`,`enabled`) VALUES (NULL ,  '".$list_id."', '".$base_name."',  '".$where."', '".$merge_ar_w."', '".$_SESSION['pechkinLogin']."', '".$_SESSION['pechkinPass']."', '".$type."', '1')");
                        $_SESSION['auload'] = '1';
                    }
                    else {
                        //А если есть, то обновим
                        mysql_query("UPDATE `phpshop_modules_pechkin_autoload` SET  `where` =  '".$where."', `base_name` =  '".$base_name."', `param` =  '".$merge_ar_w."', `login` =  '".$_SESSION['pechkinLogin']."', `pass` =  '".$_SESSION['pechkinPass']."' , `type` =  '".$type."',`enabled` =  '1' WHERE `list_id` =".$list_id);
                        $_SESSION['auload'] = '1';
                    }
                }
            }
            

            //Добавляем подписчиков
            $lists_lists_upload = $PHPechkin->lists_upload($list_id, "http://".$_SERVER['SERVER_NAME']."/phpshop/modules/pechkin/csv/shopu.csv", 0, $param);
            
            if($lists_lists_upload['text']) {
                $_SESSION['logtext'] .= PHPShopString::utf8_win1251($lists_lists_upload['text'])."\n";
            }
        }

        if($flagsql==1) {
            //Обновляем лимит на $limit
            $limit_start = $limit_start+$limit;
        }
        else {
            $limit_start = 'break';
            unset($_SESSION['auload']);
            //Когда завершаем, то делаем лог
            if($_SESSION['logtext']!='') {
                if($subscribe==1) {
                    $sorce = "../csv/log_subscribe.csv";
                    $link = "http://".$_SERVER['SERVER_NAME']."/phpshop/modules/pechkin/csv/log.csv";
                }
                else {
                    $sorce = "../csv/log.csv";
                    $link = "http://".$_SERVER['SERVER_NAME']."/phpshop/modules/pechkin/csv/log.csv";
                }
                PHPShopFile::write($sorce, $_SESSION['logtext']);
                unset($_SESSION['logtext']);
            }
        }
    }
}



// Результат
$_RESULT = array(
    'status' => $status,
    'add_users_count' => $add_users_count,
    'link' => $link,
    'limit_start' => $limit_start,
    'success' => 1
);


// JSON 
if($_REQUEST['type'] == 'json') {
    //$_RESULT['mes']=PHPShopString::win_utf8($_RESULT['mes']);
    //$_RESULT['mesclean']=  strip_tags($_RESULT['mes']);
}
    echo json_encode($_RESULT);
?>