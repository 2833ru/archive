<?
require("../connect.php");
@mysql_connect("$host", "$user_db", "$pass_db") or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase") or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

if (CheckedRules($UserStatus["cat_prod"], 2) == 1) {
    // Подключаем библиотеку поддержки.
    require_once "../../lib/Subsys/JsHttpRequest/Php.php";
    $JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");

    // Прием переменных
    $addit = htmlspecialchars(trim($_REQUEST['addit']), ENT_QUOTES, 'windows-1251');
    $seld = $_REQUEST['selopts'];

    if (strlen($addit)) {
        $sql = 'select * from ' . $SysValue['base']['table_name21'] . ' where ((`name` = "' . $addit . '") AND (category=' . $_REQUEST['num'] . '))';
        $result = mysql_query($sql);
        $numrows = mysql_num_rows($result);
    }

    if (!$numrows) {
        $sql = 'INSERT INTO ' . $SysValue['base']['table_name21'] . ' VALUES ("","' . $addit . '","' . $_REQUEST['num'] . '",0,"","")';
        $result = mysql_query($sql);
    }

    $sql = "select * from " . $SysValue['base']['table_name21'] . " where category=" . intval($_REQUEST['num']) . " order by num asc";
    $result = mysql_query($sql);




    $i = 0;
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        @$interfaces[$i]['id'] = $id;
        @$interfaces[$i]['name'] = $row['name'];

        if ((in_array($id, $seld)) || (trim($row['name']) == $addit)) {
            @$interfaces[$i]['selected'] = true;
        } else {
            @$interfaces[$i]['selected'] = false;
        }
        $i++;
    }

    $_RESULT = array(
        "interfaces" => @$interfaces
    );
}
?>