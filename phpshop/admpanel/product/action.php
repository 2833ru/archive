<?php

$_classPath = "../../";
require("../connect.php");
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");

// Подключение к БД
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

// Подключаем библиотеку поддержки.
require_once "../../lib/Subsys/JsHttpRequest/Php.php";

$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");

// Характеристики
function Sorts() {
    global $SysValue;
    $sql = "select * from " . $SysValue['base']['table_name21'];
    $result = mysql_query($sql);
    $Sorts = '';
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $name = $row['name'];
        $page = $row['page'];
        $array = array(
            "id" => $id,
            "page" => $page,
            "name" => $name
        );
        $Sorts[$id] = $array;
    }
    return $Sorts;
}

// Каталоги Характеристик
function CatalogSorts() {
    global $SysValue;
    $sql = "select * from " . $SysValue['base']['table_name20'];
    @$result = mysql_query($sql);
    $Sorts = '';
    while (@$row = mysql_fetch_array(@$result)) {
        $id = $row['id'];
        $name = $row['name'];
        $category = $row['category'];
        $filtr = $row['filtr'];
        $flag = $row['flag'];
        $goodoption = $row['goodoption'];
        $page = $row['page'];
        $array = array(
            "id" => $id,
            "name" => $name,
            "category" => $category,
            "filtr" => $filtr,
            "page" => $page,
            "flag" => $flag,
            "goodoption" => $goodoption
        );
        $Sorts[$id] = $array;
    }
    @$SysValue['sql']['num']++;
    return $Sorts;
}

function SortDisp($vendor_array) {

    $dis = null;
    $numRows = 0;

    $Sort = Sorts();
    $CatalogSort = CatalogSorts();
    $vendor_array = unserialize($vendor_array);
    foreach ($vendor_array as $key => $val)
        foreach ($val as $v) {

            // Выделение четных строк
            $numRows++;
            if ($numRows % 2 == 0) {
                $style_r = ' line2';
            } else {
                $style_r = null;
            }

            $dis.='<tr class="row ' . $style_r . '" id="r' . $numRows . '" onmouseover="PHPShopJS.rowshow_on(this)" onmouseout="PHPShopJS.rowshow_out(this,\'' . $style_r . '\')" onclick="miniWin(\'./sort/adm_sortID.php?id=' . $key . '\',500,600)">';


            $dis.='
	      <td>' . $CatalogSort[$key]['name'] . '</td>
		  <td>' . $Sort[$v]['name'] . '</td>
	   </tr>
	   ';
        }

    $disp = "<table cellpadding=0 border=0 cellspacing=1 width=100%>
	   <tr>
	      <td id=pane>Характеристика</td>
	      <td id=pane>Значение</td>
	   </tr>
$dis
</table>
";
    return $disp;
}

switch ($_REQUEST['do']) {

    case("info"):
        $sql = "select * from  " . $SysValue['base']['table_name2'] . " where id=" . intval($_REQUEST['xid']);
        $result = mysql_query(@$sql);
        $num = mysql_num_rows($result);
        @$row = @mysql_fetch_array(@$result);
        $id = $row['id'];
        $parent_enabled = $row['parent_enabled'];
        if (($row['enabled']) == "1") {
            $checked = "<td width=100 align=center><img src=./img/icon-activate.gif name=imgLang  alt=\"В наличии\" align=\"absmiddle\"> В наличии</td><td width=1></td>";
        } else {
            $checked = "<td width=100><img src=./img/icon-deactivate.gif name=imgLang  alt=\"Отсутствует\" align=\"absmiddle\"> Отсутствует</td><td width=1></td>";
        };


        if (($row['spec']) == "1") {
            $checked.="<td width=100 align=center><img name=imgLang src=./img/icon-duplicate-acl.gif align=\"absmiddle\" alt=\"Спецпредложение\"> Спец</td><td width=1></td>";
        }
        else
            $checked.="<td  width=100></td><td width=1></td>";

        if (($row['yml']) == "1") {
            $checked.="<td  width=100 align=center><img name=imgLang src=./img/icon-duplicate-banner.gif align=\"absmiddle\"  alt=\"YML прайс\"> Маркет</td><td width=1></td>";
        }
        else
            $checked.="<td  width=100></td><td width=1></td>";

        if (($row['newtip']) == "1")
            $checked.="<td  width=100 align=center><img name=imgLang src=./img/icon-move-banner.gif  align=\"absmiddle\" alt=\"Новинка\"> Новинка</td><td width=1></td>";
        else
            $checked.="<td width=100></td><td width=1></td>";

        if (($row['sklad']) == "1")
            $checked.="<td width=100 align=center><img name=imgLang src=./icon/cart_error.gif align=\"absmiddle\"  alt=\"Уведомление, под заказ\"> Под заказ</td><td width=1></td>";
        else
            $checked.="<td  width=100></td><td width=1></td>";

        if ($parent_enabled == 1)
            $checked.="<td  width=100 align=center><img name=imgLang src=./icon/plugin.gif align=\"absmiddle\"   alt=\"Подтип товара\"> Подтип товара</td><td width=1></td>";
        else
            $checked.="<td  width=100></td><td width=1></td>";



        $interfaces = '
	  <table width="100%"  cellpadding="0" cellspacing="0">
    <tr class=row>
	' . $checked . '
	   </tr>
      </table>
	  ';
        break;

    case("prev"):
        $pic_small = "img/icon_non.gif";
        $sql = "select * from  " . $SysValue['base']['table_name2'] . " where id=" . intval($_REQUEST['xid']);
        $result = mysql_query(@$sql);
        $num = mysql_num_rows($result);
        @$row = @mysql_fetch_array(@$result);
        $id = $row['id'];
        if (!empty($row['pic_small']))
            $pic_small = $row['pic_small'];
        $vendor_array = $row['vendor_array'];
        $description = stripslashes($row['description']);

        if ($num > 0) {

            $interfaces = '
      <table cellpadding="0" cellspacing="1"  border="0">
	  <tr>
      <td valign="top" align="center" width="150" style="cursor: pointer;" bgcolor="#ffffff">
	  ';
            if (!empty($pic_small))
                $interfaces.='
	  <table width="100%"  cellpadding="0" cellspacing="0">
<tr>
	<td valign="top">
	  <a href="javascript:miniWin(\'./product/adm_productID.php?productID=' . $id . '\',650,630)"><iframe src="' . $pic_small . '" width="200" height="150" scrolling="Yes" frameborder="0"></iframe></a> </td>
	   </tr>
      </table>';

            $interfaces.='
	  </td>
	  <td valign="top" width="40%" bgcolor="#ffffff">
	  <div style="height:150;overflow:auto">
	 ' . SortDisp($vendor_array) . '
	  </div>
	  </td>
	  <td valign="top" width="40%">
	  <table cellpadding=0 border=0 cellspacing=1 width=100%>
	   <tr>
	      <td id=pane>Описание</td>
	   </tr>
      <tr class=row3>
	      <td class=Nws>
		  <div style="height:120;overflow:auto;padding:5px">
		  ' . $description . '
		  </div>
		  </td>
	   </tr>
      </table>
	  </td>
      </tr>
     </table>
	 ';
        }
        else
            $interfaces = "";
        break;

    case("del"):
        $sql = "delete from " . $SysValue['base']['table_name35'] . " where id=" . intval($_REQUEST['xid']);
        mysql_query($sql);

        // Удаляем изображение с сервера
        $name = $_REQUEST['img'];
        $s_name = str_replace(".", "s.", $name);
        $big_name = str_replace(".", "_big.", $name);
        unlink($_SERVER['DOCUMENT_ROOT'] . $name);
        unlink($_SERVER['DOCUMENT_ROOT'] . $s_name);
        unlink($_SERVER['DOCUMENT_ROOT'] . $big_name);

        $tMass = updateFotoList();
        $interfaces = $tMass['interfaces'];
        break;


    case("update"):

        $tMass = updateFotoList();
        $interfaces = $tMass['interfaces'];
        break;


    case("num"):

        mysql_query("update " . $SysValue['base']['table_name35'] . " set num='" . intval($_REQUEST['num']) . "', info='" . $_REQUEST['info'] . "' where id=" . $_REQUEST['xid']);

        $tMass = updateFotoList();
        $interfaces = $tMass['interfaces'];
        break;
}




$_RESULT = array(
    "interfaces" => @$interfaces
);

if (is_array($tMass) AND isset($tMass['openerWindowInsImg']))
    $_RESULT['openerWindowInsImg'] = $tMass['openerWindowInsImg'];

function updateFotoList() {
    global $SysValue;
    if ($_REQUEST['main']) {
        $sql = "select name from " . $SysValue['base']['table_name35'] . " where parent=" . intval($_REQUEST['uid']) . " AND id=" . $_REQUEST['xid'] . " LIMIT 1";
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        $name = $row['name'];
        $s_name = str_replace(".", "s.", $name);
        mysql_query("UPDATE  " . $SysValue['base']['table_name2'] . " SET pic_big='$name', pic_small='$s_name' WHERE id = " . intval($_REQUEST['uid']));
    }

    $result = mysql_query("SELECT pic_big FROM  " . $SysValue['base']['table_name2'] . " WHERE id=" . intval($_REQUEST['uid']) . " LIMIT 1");
    $row = mysql_fetch_array($result);
    $pic_big = $row['pic_big'];

    $sql = "select * from " . $SysValue['base']['table_name35'] . " where parent=" . intval($_REQUEST['uid']) . " order by id";

    $result = mysql_query($sql);
    $i = 1;

    // перебираем предварительно, чтобы узнать, есть ли назначенное изображение из галереи.
    while ($row = mysql_fetch_array($result)) {
        if ($row['name'] == $pic_big)
            $flag = 1; // если в таблице товара прописано какое либо изображение из галереи.
    }

    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $name = $row['name'];
        $s_name = str_replace(".", "s.", $name);

        $id = $row['id'];
        $num = $row['num'];

        if ((($name == $pic_big AND !$_REQUEST['main']) OR ($_REQUEST['xid'] == $row['id'] AND $_REQUEST['main']))) {
            $main = "+";
        }
        else
            $main = "";

        if (!$imgSave AND !$flag AND !$_REQUEST['main']) { // сохраняем путь к первому изображения из списка, чтобы назначить его главным, если не назначено или удалено назначенное как главное.
            $imgSave = $name;
            $s_imgSave = str_replace(".", "s.", $imgSave);
            $main = "+";
            // назначаем главное изображение если не было назначено или удалено назначенное.
            mysql_query("UPDATE  " . $SysValue['base']['table_name2'] . " SET pic_big='$imgSave', pic_small='$s_imgSave' WHERE id = " . intval($_REQUEST['uid']));
        }

        if ($main == "+") {
            $openerWindowInsImg['pic_big'] = $name;
            $openerWindowInsImg['pic_small'] = $s_name;
        }

        @$dis.="
	<tr onmouseover=\"show_on('r" . $id . "')\" id=\"r" . $id . "\" onmouseout=\"show_out('r" . $id . "')\" class=row onclick=\"miniWin('adm_galeryID.php?id=$id',650,500)\">
	  <td align=center>$i</td>
	   <td>$name</td>
           <td><img src='$name' height='50' alt='' title='' border='0' align='absmiddle' hspace='5' style='' onclick=''></td>
           <td>$main</td>
           <td>$num</td>
	</tr>
	";
        $i++;
    }

    // назначаем главное изображение если не было назначено или удалено назначенное.
    if (!$flag)
        mysql_query("UPDATE  " . $SysValue['base']['table_name2'] . " SET pic_big='$imgSave', pic_small='$s_imgSave' WHERE id = " . intval($_REQUEST['uid']));

    $interfaces = '
<table cellpadding="0" cellspacing="1"  border="0"  width="100%">
<tr>
     <td width="10%" id="pane"><span>№</span></td>
     
     <td width="50%" id="pane"><span>Размещение</span></td>
     
     <td width="20%" id="pane"><span>Превью</span></td>
     
     <td width="10%" id="pane"><span>Главное</span></td>
     <td width="10%" id="pane"><span>№ п/п</span></td>
</tr>
    ' . $dis . '
    </table>
';

    return array("interfaces" => $interfaces, "openerWindowInsImg" => $openerWindowInsImg);
}

?>