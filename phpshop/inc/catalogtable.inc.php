<?

function RekursMainCatalogList($id){
global $SysValue,$LoadItems;
$podcatalog_id = array_keys($LoadItems['CatalogKeys'],$id);
      if(count($podcatalog_id)>0){
	  foreach($podcatalog_id as $key){
	  
	  @$dis.="<a href=\"/shop/CID_$key.html\" title=\"".$LoadItems['Catalog'][$key]['name']."\">".$LoadItems['Catalog'][$key]['name']."</a> | ";

	  @$dis.=RekursMainCatalogList($key);

	  
	  }
	  }
return $dis;
}

function Vivod_cat_table()// вывод каталогов таблицей
{
global $SysValue,$LoadItems;
$sql="select id,content from ".$SysValue['base']['table_name']." where parent_to=0 order by num";
$result=mysql_query($sql);

$i=0;

$SysValue['my']['setka_num']=$LoadItems['System']['num_row_adm'];
if($SysValue['my']['setka_num'] == 1) $j=0;
if($SysValue['my']['setka_num'] == 2) $j=0;
if($SysValue['my']['setka_num'] == 3) $j=1;
if($SysValue['my']['setka_num'] == 4) $j=1;



while($row = mysql_fetch_array($result))
    {
    $id=$row['id'];
	$content=$row['content'];
	@$dis_c="";
   // Определяем переменые
$SysValue['other']['catalogId']= $id;
$SysValue['other']['catalogI']= $i;
$SysValue['other']['catalogTemplates']=$SysValue['dir']['templates'].chr(47).$LoadItems['System']['skin'].chr(47);
$SysValue['other']['catalogTitle']= $LoadItems['Catalog'][$id]['name'];
$SysValue['other']['catalogName']= $LoadItems['Catalog'][$id]['name'];
$SysValue['other']['catalogContent']= $content;

$SysValue['other']['catalogPodcatalog']=RekursMainCatalogList($id);

// Подключаем шаблон
$dis=ParseTemplateReturn("catalog/catalog_table_forma.tpl");


// Сетка 1*1
if($SysValue['my']['setka_num'] == 1){

 $td="<tr><TD colspan=3 height=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD></tr>";
 $td.="<tr><td valign=\"top\">"; @$j++; $td2="</td>";

 @$disp.=$td.$dis;

}


// Сетка 2*2
if($SysValue['my']['setka_num'] == 2){

 if($j==1){ $td="<td valign=\"top\" class=\"panel_r\">"; $j=0; $td2="</td>";}
 else {
 $td="<tr><TD  colspan=3 height=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD></tr>";
 $td.="<tr><td valign=\"top\" class=\"panel_l\">"; $j++; $td2="</td>";
 $td2.="<TD width=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD>";
 }
 
 @$disp.=$td.$dis.$td2;
}


// Сетка 3*3
if($SysValue['my']['setka_num'] == 3){
 if($j==3){
$td="<td  valign=\"top\" class=\"panel_t\">"; $j++; $td2="</td></tr>";
@$disp.=$td.$dis.$td2;
}

if($j==2){
$td="<td  valign=\"top\" class=\"panel_t\">"; $j++; $td2="</td>";
$td2.="<TD width=1 ><IMG height=1 src=\"images/spacer.gif\" width=1></TD>";
@$disp.=$td.$dis.$td2;
}

if($j==1){
$td="<tr><TD width=100%  colspan=5 height=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD></tr>";
$td.="<tr><td  valign=\"top\" class=\"panel_t\">"; $j++; $td2="</td>";
$td2.="
<TD width=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD>";
@$disp.=$td.$dis.$td2;
}

if($j==4){
$j=1;
}
}


// Сетка 4*4
if($SysValue['my']['setka_num'] == 4){

if($j==4){
$td="<td  valign=\"top\" class=\"panel_f\">"; $j++; $td2="</td></tr>";
@$disp.=$td.$dis.$td2;
}

if($j==3){
$td="<td  valign=\"top\" class=\"panel_f\">"; $j++; $td2="</td>";
$td2.="<TD width=1 ><IMG height=1 src=\"images/spacer.gif\" width=1></TD>";
@$disp.=$td.$dis.$td2;
}

if($j==2){
$td="<td  valign=\"top\" class=\"panel_f\">"; $j++; $td2="</td>";
$td2.="<TD width=1 ><IMG height=1 src=\"images/spacer.gif\" width=1></TD>";
@$disp.=$td.$dis.$td2;
}

if($j==1){
$td="<tr><TD width=100%  colspan=5 height=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD></tr>";
$td.="<tr><td  valign=\"top\" class=\"panel_f\">"; $j++; $td2="</td>";
$td2.="
<TD width=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD>";
@$disp.=$td.$dis.$td2;
}



if($j==5){
$j=1;
}

}




}

$dis='
<table cellpadding="0" cellspacing="3">
'.$disp.'
</table>
';
return @$dis;
}

if($SysValue['nav']['truepath'] == "/")
 $SysValue['other']['leftCatalTable']= Vivod_cat_table();  // Генерация каталогов табл.
?>
