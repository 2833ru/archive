<?php

// UTF-8 Env Fix
if (ini_get("mbstring.func_overload") > 0) {
    ini_set("mbstring.internal_encoding", null);
}

$_classPath = "../phpshop/";
include($_classPath . "class/obj.class.php");
include($_classPath . "lib/phpass/passwordhash.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("math");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("security");

// Подключение к БД
$PHPShopBase = new PHPShopBase($_classPath . "/inc/config.ini");

// Подключение хука
function loadHooks() {
    
    // ShopBuilder Lite
    if(empty($GLOBALS['option']['shopbuilder']))
        $path = "hook";
    else  $path = "../phpshop/templates/1cManager/hook";
    
    if (@$dh = opendir('hook')) {
        while (($file = readdir($dh)) !== false) {
            $fstat = explode(".", $file);
            if ($fstat[1] == "php" and !strstr($fstat[0], '#'))
                include_once($path.'/' . $file);
        }
        closedir($dh);
    }
}

// Подключение хука
loadHooks();

/**
 * Авторизация пользователей
 * @package PHPShopExchange
 * @author PHPShop Software
 * @version 1.1
 */
class UserChek {

    var $logPHPSHOP;
    var $pasPHPSHOP;
    var $idPHPSHOP;
    var $statusPHPSHOP;
    var $mailPHPSHOP;
    var $OkFlag = 0;

    function ChekBase($table_name) {
        global $link_db;

        $hasher = new PasswordHash(8, false);

        $sql = "select * from " . $table_name . " where enabled='1'";
        @$result = mysqli_query($link_db,$sql);
        while (@$row = mysqli_fetch_array(@$result)) {
            if ($this->logPHPSHOP == $row['login']) {
                if ($hasher->CheckPassword($this->pasPHPSHOP, $row['password'])) {
                    $this->OkFlag = 1;
                }
            }
        }
    }

    function myDecode($disp) {
        $decode = substr($disp, 0, strlen($disp) - 4);
        $decode = str_replace("I", 11, $decode);
        $decode = explode("O", $decode);
        $disp_pass = "";
        for ($i = 0; $i < (count($decode) - 1); $i++)
            $disp_pass.=chr($decode[$i]);
        return $disp_pass;
    }

    function BadUser() {
        if ($this->OkFlag == 0)
            exit("Login Error");
    }

    function UserChek($logPHPSHOP, $pasPHPSHOP, $table_name) {
        $this->logPHPSHOP = $logPHPSHOP;
        $this->pasPHPSHOP = $this->myDecode($pasPHPSHOP);
        $this->ChekBase($table_name);
        $this->BadUser();
    }

}

// Проверка авторизации
$UserChek = new UserChek($_GET['log'], $_GET['pas'], $PHPShopBase->getParam("base.table_name19"));
?>