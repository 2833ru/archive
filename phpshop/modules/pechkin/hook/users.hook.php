<?php
/**
 * Записываем пользователя
 * @param array $obj объект
 * @param array $data массив данных
 * @param string $rout роутер места вызовы модуля [START|MIDDLE|END]
 */
function add_hook($obj, $data, $rout) {
    //Автозагрузка в печкин
    include("phpshop/modules/pechkin/class/PHPechkin.php");

            if($_POST['subscribe_new']=='on') {
                //Подписчики
                $where = ' WHERE type=1';
            }
            else {
                //Пользователи
                $where = ' WHERE type=2';
            }

            $select = "SELECT * FROM `phpshop_modules_pechkin_autoload` ".$where;
            $query = mysql_query($select);
            $users = mysql_fetch_array($query);
            do {
                $PHPechkinG = new PHPechkin();
                $PHPechkinG->__construct($users['login'],$users['pass']);

                /*
                $param_ar = explode('::', $users['param']);
                if(isset($param_ar)) {
                    foreach ($param_ar as $idp => $param) {
                        $idp++;
                        if($param!='0') {
                            if($param=='datas') {
                                $params['merge_'.$idp] = date("d.m.Y H:i:s");
                            }
                            else {
                                $params['merge_'.$idp] = $_POST[$param.'_new'];
                            }
                        }
                    }
                }*/

                unset($params);
                unset($param_ar);
                unset($param);

                $param_ar = explode('::', $users['param']);
                if(isset($param_ar)) {
                    foreach ($param_ar as $idp => $param) {
                        $idp++;
                        if($param!='0') {
                            if($param=='datas') {
                                $params['merge_'.$idp] = date("d.m.Y H:i:s");
                            }
                            elseif($param=='name') {
                                $params['merge_'.$idp] = PHPShopString::win_utf8($_POST[$param.'_new']);
                            }
                            else {
                                $params['merge_'.$idp] = PHPShopString::win_utf8($_POST[$param]);
                            }
                        }
                        else {
                            $params['merge_'.$idp] = '';
                        }
                    }
                }

                $lists_add = $PHPechkinG->lists_add_member($users['list_id'], $_POST['login_new'], $params);
                    
            }
            while ($users = mysql_fetch_array($query));


}

$addHandler=array
(
'add' => 'add_hook'
);
?>