<?
function Nav_links()// Навигация 
{
global $SysValue,$LoadItems;
$p=$SysValue['nav']['id']; if(@!$p) $p=1;
$num_row=$LoadItems['System']['num_row'];
$num_page=NumFrom("table_name17"," where enabled='1' ");
$i=1;
$num=$num_page/$num_row;
while ($i<$num+1)
    {
	if($i!=$p){
	
	if($i==1) $pageOt=$i+@$pageDo;
	 else $pageOt=$i+@$pageDo-$i;
	 
	$pageDo=$i*$num_row;
	@$navigat.="
	     <a href=\"./links_".$i.".html\">".$pageOt."-".$pageDo."</a> | ";
	}
	else{
	
     if($i==1) $pageOt=$i+@$pageDo;
	 else $pageOt=$i+@$pageDo-$i;
	 
	$pageDo=$i*$num_row;
	 @$navigat.="
	     <b>".$pageOt."-".$pageDo."</b> | ";
	}
	$i++;
	}
 if($num>1)
  {
 if($p>$num){$p_to=$i-1;}else{$p_to=$p+1;}
 $nava="
 <tr class=tip1><td>
 <table cellpadding=\"0\" cellpadding=\"0\" border=\"0\">
        <tr >
	     <td class=style5>
".$SysValue['lang']['page_now'].": 
<a href=\"./links_".($p-1).".html\"><img src=\"images/shop/3.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\"></a>
$navigat&nbsp<a href=\"./links_".$p_to.".html\"><img src=\"images/shop/4.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" title=\"Вперед\"></a>
		</td>
       </tr>
        </table>
</td></tr>
		";
	}
return @$nava;
}


function PageLinks()// Создание страниц
{
global $SysValue,$LoadItems;
$p=$SysValue['nav']['id']; if(@!$p) $p=1;
$num_row=$LoadItems['System']['num_row'];
$num_ot=0;
$q=0;
while($q<$p)
  {
  $sql="select * from ".$SysValue['base']['table_name17']." where enabled='1' order by num desc LIMIT $num_ot, $num_row ";
  $q++;
  $num_ot=$num_ot+$num_row;
  }
return $sql;
}


function DispLinks($n)// выбор товаров из данного подкатолога
{
global $SysValue,$LoadItems,$cat,$p;
if(@!$p)
 {
 $p=1;
 }
$n=TotalClean($n,1);
$cat=TotalClean($cat,1);
$p=TotalClean($p,1);
$sql=PageLinks($n);
$result=mysql_query($sql);
$i=0;
while($row = mysql_fetch_array($result))
    {
    $id=$row['id'];
	$image=$row['image'];
    $name=$row['name'];
	$opis=$row['opis'];
	$link=$row['link'];


// Определяем переменые
$SysValue['other']['linksImage']= $image;
$SysValue['other']['linksName']= $name;
$SysValue['other']['linksOpis']= $opis;
$SysValue['other']['linksLink']= $link;


// Подключаем шаблон
@$dis=ParseTemplateReturn($SysValue['templates']['main_links_forma']);

 @$disp.="
	<tr>
	<td>
	".$dis."
	  </td>
	  </tr>
	";
	$i++;
	}

// Определяем переменые
@$SysValue['other']['catalog']= $SysValue['lang']['catalog'];
@$SysValue['other']['producFound']= $SysValue['lang']['found_of_products'];
@$SysValue['other']['productNumOnPage']=$SysValue['lang']['row_on_page'];
@$SysValue['other']['productNumRow']=$LoadItems['System']['num_row'];
@$SysValue['other']['productPageNav']=Nav_links();
@$SysValue['other']['productPageThis']=$p;
@$SysValue['other']['productPageDis']=@$disp;

// Подключаем шаблон
@$disp=ParseTemplateReturn($SysValue['templates']['links_page_list']);

return @$disp;
}
?>