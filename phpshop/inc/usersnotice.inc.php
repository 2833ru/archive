<?

function UsersNoticeList($UsersId){
global $SysValue,$LoadItems,$noticeId;
$n = CleanSearch($n);


// Удаляем
if(isset($noticeId)){
$noticeId = CleanSearch($noticeId);
$sql="delete from ".$SysValue['base']['table_name34']." where id=$noticeId";
@$result=mysql_query(@$sql);
header("Location: /users/notice.html");
}


$sql="select * from ".$SysValue['base']['table_name34']." where user_id='$UsersId' order by datas desc";
$result=mysql_query($sql);
while(@$row = mysql_fetch_array(@$result))
    {
	$id=$row['id'];
	$datas_start=$row['datas_start'];
    $datas=$row['datas'];
	$user_id=$row['user_id'];
    $product_id=$row['product_id'];
	$enabled=$row['enabled'];
    $LoadItems['Product'][$product_id]=ReturnProductData($product_id,2);
	
	
	if($enabled == 0)
@$dis.='
<tr>
	<td id=allspecwhite>
	<a href="/shop/UID_'.$product_id.'.html" class="b" title="'.$LoadItems['Product'][$product_id]['name'].'"><img src="images/shop/icon-setup.gif" alt="" width="16" height="16" border="0" align="absmiddle" hspace="5">'.$LoadItems['Product'][$product_id]['name'].'</a>
	</td>
	<td id=allspecwhite>
	'.dataV($datas_start).' - '.dataV($datas).'
	</td>
	<td id=allspecwhite>
	<img src="images/shop/icon-deactivate.gif" alt=""  border="0" align="absmiddle"><a href="javascript:void(0);" onclick="NoticeDel('.$id.')">удалить</a>
	</td>
</tr>
';
 else 
@$dis_arhiv.='
<tr>
	<td id=allspecwhite>
	<a href="/shop/UID_'.$product_id.'.html" class="b" title="'.$LoadItems['Product'][$product_id]['name'].'"><img src="images/shop/icon-setup.gif" alt="" width="16" height="16" border="0" align="absmiddle" hspace="5">'.$LoadItems['Product'][$product_id]['name'].'</a>
	</td>
	<td id=allspecwhite>
	'.dataV($datas_start).' - '.dataV($datas).'
	</td>
	<td id=allspecwhite>
	<img src="images/shop/icon-activate.gif" alt=""  border="0" align="absmiddle">выполнено
	</td>
</tr>
';

}
$disp='
<DIV id=allspec><IMG height=16 alt="" hspace=5 src="images/shop/date.gif" width=16 align=absMiddle border=0><B>Текущие заявки</B> </DIV>
<table  id=allspecwhite cellpadding=3>
<tr>
	<td id=allspec>
	<b>Наименование</b>
	</td>
	<td id=allspec>
	<b>Период</b>
	</td>
	<td id=allspec>
	<b>Статус</b>
	</td>
</tr>
'.$dis.'
</table>

<DIV id=allspec><IMG height=16 alt="" hspace=5 src="images/shop/date.gif" width=16 align=absMiddle border=0><B>Архив выполненых заявок</B> </DIV>
<table  id=allspecwhite cellpadding=3>
<tr>
	<td id=allspec>
	<b>Наименование</b>
	</td>
	<td id=allspec>
	<b>Период</b>
	</td>
	<td id=allspec>
	<b>Статус</b>
	</td>
</tr>
'.$dis_arhiv.'
</table>

';
return $disp;
}


function UsersNotice($UsersId,$productId){
global $SysValue,$_POST,$LoadItems,$REMOTE_ADDR,$SERVER_NAME;

// Ресайз
$Options=unserialize($LoadItems['System']['admoption']);

$sql="select * from ".$SysValue['base']['table_name27']." where id='$UsersId' LIMIT 0, 1";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
      $id=$row['id'];
      $login=$row['login'];
	  $password=$row['password'];
	  $status=$row['status'];
	  $mail=$row['mail'];
	  $name=$row['name'];
	  $company=$row['company'];
	  $inn=$row['inn'];
	  $tel=$row['tel'];
	  $adres=$row['adres'];
	  $LoadItems['Product'][$productId]=ReturnProductData($productId,2);
	  
// Шлем мыло менеджеру
if(@$_POST['notice']){
$codepage  = "windows-1251";     
$header_adm  = "MIME-Version: 1.0\n";
$header_adm .= "From:   <".$mail.">\n";
$header_adm .= "Content-Type: text/plain; charset=$codepage\n";
$header_adm .= "X-Mailer: PHP/";
$zag_adm=$LoadItems['System']['name']." - Поступила заявка на уведомление о товаре ".$LoadItems['Product'][$_POST['id']]['name'];

$active=date("U")+($_POST['date']*60*60*24*30);

$content_adm="
Доброго времени!
--------------------------------------------------------

Поступила заявка на уведомление о товаре с интернет-магазина '".$LoadItems['System']['name']."'
от пользователя ".$name."

Товар: ".$LoadItems['Product'][$_POST['id']]['name']."
АРТ: ".$LoadItems['Product'][$_POST['id']]['uid']."
Линк: http://".$SERVER_NAME."/shop/UID_".$_POST['id'].".html
Дата поступления: ".date("d-m-y H:i a")."
Активность заявки до: ".dataV($active)."

Пользователь: $name
Компания: $company
E-mail: $mail
Тел.: $tel
---------------------------------------------------------

".TotalClean($_POST['message'],2)."

IP:".$REMOTE_ADDR."
---------------------------------------------------------


Powered & Developed by www.PHPShop.ru
".$SysValue['license']['product_name'];
mail($LoadItems['System']['adminmail2'],$zag_adm, $content_adm, $header_adm);

// Проверка сущ записей
$sql="select id from ".$SysValue['base']['table_name34']." where user_id='$UsersId' and product_id='".$_POST['id']."' and enabled='0'  LIMIT 0, 1";
$result=mysql_query($sql);
$num=mysql_numrows($result);

if($num == 0){

// Пишем в базу
$sql="INSERT INTO ".$SysValue['base']['table_name34']."
   VALUES ('','$UsersId','".$_POST['id']."','".date("U")."','$active','0')";
   $result=mysql_query($sql);
   header("Location: /users/notice.html");
   $statusMail='
<div id=allspecwhite>
<img src="images/shop/comment.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><font color="#008000"><b>Заявка менеджеру отправлена</b></font></div>
';
 }
 else 
  $statusMail='
<div id=allspecwhite>
<img src="images/shop/comment.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><font color="red"><b>Заявка для данного товара уже имеется в базе, удалите предыдущую заявку из базы для создания новой.</b></font></div>
';


}


$disp='
<p><br></p>

<div id=allspec>
<img src="images/shop/icon_info.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><b>Уведомить при появлении товара в продаже</b> 
</div>
<p>
<table>
<tr>
   <td>
   <div align="center" style="padding:10px"><table>
   <table>
<tr>
	<td><a href="/shop/UID_'.$productId.'.html"><img src="'.$LoadItems['Product'][$productId]['pic_small'].'" alt="'.$LoadItems['Product'][$productId]['name'].'" border="0"  onload="EditFoto(this,'.$Options['width_kratko'].')" onerror="NoFoto(this,\''.$SysValue['other']['pathTemplate'].'\')"></a></td>
	<td style="padding:10px"><h1>'.$LoadItems['Product'][$productId]['name'].'</h1></td>
</tr>
<tr>
  <td colspan="2" align="right">
 
  <img src="images/shop/icon-setup2.gif" alt="" border="0" align="absmiddle"> <a href="javascript:history.back(1)">Вернуться</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
 <img src="images/shop/interface_dialog.gif" alt="" width="16" height="16" border="0" align="absmiddle"> <a href="./notice.html">Архив заявок</a>
</td>
</tr>
</table>
</div>
   </td>
</tr>
<tr>
   <td>
   <div id="allspecwhite">
   Заявка записывается в базу. Уведомление придет автоматически при появлении товара в продаже или по решению менеджера по продажам.
   </div>
   </td>
</tr>
<tr>
  <td>
  <form method="post" name="forma_message">
  Дополнительная информация:<br>
  <textarea style="width:100%;height:100px;" name="message" id="message"></textarea>
  '.@$statusMail.'<br>
  <div style="float: right"><img src="images/shop/date.gif" alt="" width="16" height="16" border="0" align="absmiddle" hspace="3">Не позднее: <select name="date">
			<option value="1" SELECTED>1 месяца</option>
			<option value="2">2 месяцев</option>
			<option value="3">3 месяцев</option>
			<option value="4">4 месяцев</option>
</select></div>
  <div>
  <input type="submit" value="Отправить заявку" name="notice">
  <input type="hidden" value="'.$productId.'" name="id">
  </div>
  </form>
  </td>
</tr>
</table>

</p>
<p><br></p>
';
return $disp;
}
?>