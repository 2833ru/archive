<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль Сортировки                  |
+-------------------------------------+
*/

// Вывод сортировок таблицей
function DispCatSortTable($category,$vendor_array){ 
global $SysValue,$LoadItems;

// Выводить имя набора характеристики
$categorySort = false;

// Оптимизация для больших баз
if(empty($LoadItems['Sort'])) $optimize = true;


$sort=unserialize($LoadItems['Podcatalog'][$category]['sort']);
$vendor_array=unserialize($vendor_array);
$categoryControl="";
if(is_array($sort))
foreach($sort as $v){
$sql="select * from ".$SysValue['base']['table_name20']." where (id=$v and goodoption!='1') order by num";
$result=mysql_query($sql);
$SysValue['sql']['num']++;
while (@$row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$category=$row['category'];
	$clean="false";
	$page=$row['page'];
	
  if($categoryControl!=$category and $categorySort)
  @$dis.= '
<tr>
   <td colspan="2"><b>'.$LoadItems['CatalogSort'][$category]['name'].'</b></td>
</tr>';

$count=count($vendor_array[$id]);
$srt_value="";
if( $count> 1)
  $zpt=", ";
  else $zpt="";

if(is_array($vendor_array[$id]))
foreach($vendor_array[$id] as $p){

    // Оптимизированный вывод
    if($optimize) $LoadItems['Sort']=Sorts(' where id='.$p);


     if(!@$LoadItems['Sort'][$p]['name']) {
	   @$srt_value="-   ";
	   $clean="true";
	   }
	  else {
	       if(empty($LoadItems['Sort'][$p]['page'])) 
             @$srt_value.=$LoadItems['Sort'][$p]['name'].$zpt;
			   else @$srt_value.= '<a href="/page/'.$LoadItems['Sort'][$p]['page'].'.html">'.$LoadItems['Sort'][$p]['name'].'</a>'.$zpt;
		   }
	 }
	 
if( $count> 1){
$leng=strlen($srt_value);
if(empty($LoadItems['Sort'][$p]['page'])) @$srt_value= substr($srt_value,0,$leng-2);
  else @$srt_value= '<a href="/page/'.$LoadItems['Sort'][$p]['page'].'.html">'.substr($srt_value,0,$leng-2).'</a>';
}

if( $count == 0  ) $clean="true";


if( $clean!="true"){
@$dis.= '
<tr>
  <td class="sort_name_bg">';
  if(empty($page)) @$dis.=$name;
   else @$dis.='<a href="/page/'.$page.'.html">'.$name.'</a>';
  $dis.= ':</td>
  <td>'.$srt_value.'</td>
</tr>
';}
$categoryControl=$category;
}
}

$disp="<table class=sort_table cellpadding=3>$dis</table>";
return @$disp;
}


function dispValue($n,$title){ // вывод характеристик
global $SysValue;
if(empty($_POST['v'])) $vendor=$SysValue['nav']['query']['v'];
 else $vendor = $_POST['v'];
$sql="select * from ".$SysValue['base']['table_name21']." where category='$n' order by num";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
    {
    $id=$row['id'];
    $name=substr($row['name'],0,35);
	$sel="";
	if(is_array($vendor))
	foreach($vendor as $k=>$v){
	if ($id == $v) $sel="selected";
	}
    @$dis.="<option value=".$id." ".$sel." >".$name."</option>\n";
	}
$SysValue['sort'][]=$n;
$SysValue['sql']['debug']=$SysValue['sort'];
@$disp="
<select name=v[$n] size=1 id=$n>
<option value='' selected>-- все $title --</option>
$dis
</select>
";
@$SysValue['sql']['num']++;

return @$disp;
}


function DispCatSort($category){ // Вывод сортировок
global $SysValue,$LoadItems;

$sort=unserialize($LoadItems['Catalog'][$category]['sort']);

foreach($sort as $v){
@$SysValue['sql']['num']++;
$sql="select * from ".$SysValue['base']['table_name20']." where id=$v and filtr='1' and goodoption!='1' order by num";
$result=mysql_query($sql);
while (@$row = mysql_fetch_array($result)){
	$id=$row['id'];
	$name=$row['name'];
    @$disp.=dispValue($id,$name);
}

$dis="<td>".$disp."</td>";

}
return @$dis;
}



function DispCatOptionsTest($category){ // проверка на сортировки
global $SysValue,$LoadItems;
$sort=unserialize($LoadItems['Podcatalog'][$category]['sort']);
if(is_array($sort))
foreach($sort as $v)
       if($LoadItems['CatalogSort'][$v]['goodoption'] == 1)
	     return 1;
return 0;
}


function DispCatOptions($category,$xid){ // Вывод сортировок
global $SysValue,$LoadItems;
$sort=unserialize($LoadItems['Podcatalog'][$category]['sort']);
if(is_array($sort))
foreach($sort as $v){
	$sql="select * from ".$SysValue['base']['table_name20']." where id=$v and goodoption='1' order by num";
	$result=mysql_query($sql);
	@$SysValue['sql']['num']++;
	$num=mysql_num_rows($result);
//	$numel=0;
//	$adder='';
	while (@$row = mysql_fetch_array($result)) {
		$id=$row['id'];
		$name=$row['name'];
		@$disp.= '<TR><TD>
		'.dispOption($id,$name,$numel,$xid,$row['optionname']).'
		</TD></TR>
		';
		$adder.='+document.getElementById("opt'.$numel.$xid.'").value';
		$numel++;
	} //Конец перебора while
} //Конец перебора foreach
@$SysValue['sql']['num']++;

$disp='
<SCRIPT>
function alloptions'.$xid.'() {
var optsvalue=""'.$adder.'
document.getElementById("allOptionsSet'.$xid.'").value=optsvalue;
}
</SCRIPT>
<TABLE>'.$disp.'</TABLE><INPUT TYPE=HIDDEN id="allOptionsSet'.$xid.'" value="">';

return @$disp;
}

function dispOption($n,$title,$numel,$xid,$optionname){ // вывод характеристик
global $SysValue;
$vendor=$SysValue['nav']['query']['v'];
$sql="select * from ".$SysValue['base']['table_name21']." where category='$n' order by num";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=substr($row['name'],0,35);
	if ($optionname) {$ct=$title.':';} else {$ct="";}
	$sel="";
	if(is_array($vendor))
	foreach($vendor as $k=>$v){if ($id == $v) $sel="selected";}
	@$dis.='<option value="['.$ct.$name.']" '.$sel.' >'.$name.'</option>'."\n";
}
//$SysValue['sort'][]=$n;
//$SysValue['sql']['debug']=$SysValue['sort'];
@$disp='
<select name=v['.$n.'] size=1 id="opt'.$numel.$xid.'" onChange="alloptions'.$xid.'()">
<option value="" selected>-- любой '.$title.' --</option>
'.$dis.'
</select>
';
@$SysValue['sql']['num']++;
return @$disp;
}

function dispBrend($n){ // вывод брендов
global $SysValue;
@$vendor=$SysValue['nav']['query']['v'];
$sql="select * from ".$SysValue['base']['table_name21']." where category='$n' order by num";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
    {
    $id=$row['id'];
    $name=$row['name'];
	// Вывод на выборку товаров
    //@$dis.="<a href='/selection/?v[$n]=$id'>$name</a><br>";
	// Вывод на описание бренда с сылкой на категории с товарами сортировки
	@$dis.="<a href='/selectioncat/?v[$n]=$id'>$name</a><br>";
	}
//$SysValue['sort'][]=$n;
@$SysValue['sql']['num']++;
return @$dis;
}


// Подключаем поиск брендов
//$SysValue['other']['vendorDispA'] = dispBrend(1);
?>
