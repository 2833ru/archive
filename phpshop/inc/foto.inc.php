<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль Вывода Фотогалереи          |
+-------------------------------------+
*/

// Вывод случайной картинки для краткого описания
function getFotoIconKratko($n){
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name35']." where parent=$n order by RAND()";
$result=mysql_query($sql);
$num=mysql_num_rows($result);
$row = mysql_fetch_array($result);
	$name=str_replace(".","s.",$row['name']);
	$array=array($name,$num);
return $array;
}

// Вывод картинки для подробного описания
function getFotoIconPodrobno($n,$pic_big,$name_foto){
global $SysValue,$LoadItems;
$sql="select * from ".$SysValue['base']['table_name35']." where parent=$n order by num";
$result=mysql_query($sql);
@$num=mysql_num_rows(@$result);
if($num !=0){
$j=1;
while(@$row = mysql_fetch_array(@$result))
    {
	$name=$row['name'];
	$name_s=str_replace(".","s.",$name);
	$name_bigstr=str_replace(".","_big.",$pic_big);
	$name_big="http://".$_SERVER['HTTP_HOST'].$name_bigstr;
	if (@fopen($name_big, "r")) {
		$name_b = str_replace(".","_big.",$pic_big);
	} else {
		$name_b = str_replace(".",".",$pic_big);;
	}
	$id=$row['id'];
	$info=$row['info'];
	$FotoArray[]=array(
	"id"=>$id,
	"name"=>$name,
	"name_s"=>$name_s,
        "name_b"=>$name_b,
	"info"=>$info
	);
}

if(is_array($FotoArray))
    $dBig='<div align="center" id="IMGloader" style="padding-bottom: 10px">
<a class=highslide onclick="return hs.expand(this)" href="'.$name_b.'" target=_blank getParams="null"><img src="'.$pic_big.'" border="1" class="imgOn" alt="'.$LoadItems['Product'][$n]['name'].'"
    onerror="NoFoto2(this)"></a><div class=highslide-caption>'.$name_foto.'</div><br>'.$FotoArray[0]["info"].'
</div>';
	

	
if(is_array($FotoArray[0]) and count($FotoArray)>1)
@$disp.='
  <td align="center">
  <a href="javascript:fotoload('.$n.',0);"><img src="'.$FotoArray[0]["name_s"].'" alt="'.$FotoArray[0]["info"].'" border="1" class="imgOn" onerror="NoFoto2(this)"></a></td>';
  
if(is_array(@$FotoArray[1]))
@$disp.='
  <td align="center">
    <a href="javascript:fotoload('.$n.',1);"><img src="'.$FotoArray[1]["name_s"].'" alt="'.$FotoArray[1]["info"].'" border="1" class="imgOff" onmouseover="ButOn(this)" onmouseout="ButOff(this)" onerror="NoFoto2(this)"></a>
	</td>';
  
if(is_array(@$FotoArray[2]))
@$disp.='
  <td align="center">
     <a href="javascript:fotoload('.$n.',2);"><img src="'.$FotoArray[2]["name_s"].'" alt="'.$FotoArray[2]["info"].'" border="1" class="imgOff" onmouseover="ButOn(this)" onmouseout="ButOff(this)" onerror="NoFoto2(this)"></td><td>
<a href="javascript:fotoload('.$n.',2);">&raquo;</a>
  </td>

';

$d=$dBig;
if($num>1) $d.='<table class="foto">
<tr>
'.$disp.'
</tr>
</table>
<div style="color: #5A6666;font-size: 11px">Доступно изображений: <strong>'.$num.'</strong> </div>
';
}
else {

$d='
 <div align="center" id="IMGloader" style="padding-bottom: 10px">
 <img src="'.$pic_big.'" border="1" class="imgOn" alt="'.$f.'" onerror="NoFoto2(this)">
</div>';

}
return $d;
}
?>
