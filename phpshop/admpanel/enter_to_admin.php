<?php

// Portable PHP password hashing framework.
require_once dirname(__FILE__) . '/../lib/phpass/passwordhash.php';

// Настройка уровня оповещения отладчика
if (function_exists('error_reporting')) {
   error_reporting('E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT');
}

// Снимаем ограничения на выполнение
if ($SysValue['my']['time_limit_enabled'] == "true") {
    $is_safe_mode = @ini_get('safe_mode') == '1' ? 1 : 0;
    if (!$is_safe_mode)
        @set_time_limit(TIME_LIMIT);
}


// Тип оконного менеджера
$_COOKIE['winOpenType'] = 'default';

// класс проверки пользователя
class UserChek {

    var $logPHPSHOP;
    var $pasPHPSHOP;
    var $idPHPSHOP;
    var $statusPHPSHOP;
    var $mailPHPSHOP;
    var $OkFlag = 0;
    var $DIR = "";

    function ChekBase($table_name) {
        $sql = "select * from " . $table_name . " where enabled='1'";
        @$result = mysql_query(@$sql);

        $hasher = new PasswordHash(8, false);
        while (@$row = mysql_fetch_array(@$result)) {
            if ($this->logPHPSHOP == $row['login']) {
                $check = $hasher->CheckPassword($this->pasPHPSHOP, $row['password']);
                if ($check) {
                    $this->OkFlag = 1;
                    $this->idPHPSHOP = $row['id'];
                    $this->statusPHPSHOP = unserialize($row['status']);
                    $this->mailPHPSHOP = $row['mail'];
                }
            }
        }
    }

    function BadUser() {
        if ($this->OkFlag == 0) {
            header("Location: " . $this->DIR . "/phpshop/admpanel/");
            exit("Login Error");
        }
    }

    function UserChek($logPHPSHOP, $pasPHPSHOP, $table_name, $DIR) {
        $this->logPHPSHOP = $logPHPSHOP;
        $this->pasPHPSHOP = $pasPHPSHOP;
        $this->DIR = $DIR;
        $this->ChekBase($table_name);
        $this->BadUser();
    }

    function BadUserForma() {
        $disp = '
	  <table width="100%" height="100%" style="Z-INDEX:2;">
<tr>
	<td valign="middle" align="center">
		<div class="alert"  align="left">
<table width="100%" height="100%">
<tr>
	<td width="35" vAlign=center ><IMG 
            hspace=0 src="img/i_support_med[1].gif" align="absmiddle" 
            border=0 ></td>
	<td ><b>Внимание, ' . $this->logPHPSHOP . '!</b><br>У Вас недостаточно прав для выполнения данной операции.<br>Обратитесь к администратору сервера.</td>
</tr>
</table>

		</div>
</td>
</tr>
</table>
	  
	  ';
        return $disp;
    }

    function BadUserFormaWindow() {
        echo'
	  <script>
	  if(confirm("Внимание ' . $this->logPHPSHOP . '!\nУ Вас недостаточно прав для выполнения данной операции.\nЗакрыть это окно?"))
	  window.close();
	  </script>';
    }

}

session_start();
@mysql_query("SET NAMES 'cp1251'");


$UserChek = new UserChek($_SESSION['logPHPSHOP'], $_SESSION['pasPHPSHOP'], $GLOBALS['SysValue']['base']['table_name19'], $GLOBALS['SysValue']['dir']['dir']);
$UserStatus = $UserChek->statusPHPSHOP;

// Поддержка прав модулей.
if (@$_classPath == "../../../" and CheckedRules($UserStatus["option"], 1) == 1)
    $UserChek->statusPHPSHOP = 0;

// Проверка прав
function CheckedRules($a, $b) {
    $array = explode("-", $a);
    return $array[$b];
}

?>