<?

function OrderPayment()// вывод платежей
{
global $SysValue;
$sql="select * from ".$GLOBALS['SysValue']['base']['table_name48']." order by num desc";
@$result=mysql_query($sql);
while(@$row = mysql_fetch_array(@$result))
    {
	$id=$row['id'];
    $name=$row['name'];
	$path=$row['path'];
    $num=$row['num'];
	
	
	
    if($row['enabled']==1){
	$fl="<img src=\"img/icon-activate.gif\">";
	}else{
	$fl="<img src=\"img/icon-deactivate.gif\">";}

	@$disp.='
	<tr valign="top" bgcolor="ffffff" onmouseover="show_on(\'r'.$id.'\')" id="r'.$id.'" onmouseout="show_out(\'r'.$id.'\')"  onclick="miniWin(\'payment/adm_paymentID.php?id='.$id.'\',650,500)" class="row">
    <td align="center" class=forma>
	'.$fl.'
	</td>
    <td class=forma valign="middle" >'.$name.'</td>
	<td class=forma valign="middle" >'.TipPayment($path).' </td>
</tr>
	';
    @$i++;
    }
if($i>30)$razmer="height:600;";
$_Return=('
<div id=interfacesWin name=interfacesWin align="left" style="width:100%;'.@$razmer.';overflow:auto"> 
<table width="50%"  cellpadding="0" cellspacing="0" style="border: 1px;
	border-style: inset;" class=row>
<tr>
	<td valign="top" style="padding-left:5px">

<table cellpadding="0" cellspacing="1" width="100%" border="0" bgcolor="#808080" class="sortable" id="sort">
<tr>
    <td width=\"5%\" id=pane align=center>+/-</td>
    <td width=\"50%\" id=pane align=center>Название</td>
	<td width=\"40%\" id=pane align=center>Тит оплаты</td>
</tr>
'.@$disp.'
</table>

</table>
<div align="right" style="width:50%;padding:10"><BUTTON style="width: 15em; height: 2.2em; margin-left:5"  onclick="miniWin(\'payment/adm_payment_new.php\',630,580)">
<img src="icon/page_new.gif" width="16" height="16" border="0" align=absmiddle hspace="5">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div>
</div>
</div>
 ');
return $_Return;
}
?>