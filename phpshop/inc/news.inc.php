<?
function Page_news()// Создание страниц
{
global $SysValue,$LoadItems,$timestamp;
$p=$SysValue['nav']['id']; if(@!$p) $p=1;
$num_row=$LoadItems['System']['num_row'];
$num_ot=0;
$q=0;
$sorttime="";

if(!empty($timestamp)){
$year=date("Y",$timestamp);
$month=date("m",$timestamp);
$day=date("d",$timestamp);
$timestampstart=$timestamp;
$timestampend=mktime(23,59,59,$month,$day,$year);
$sorttime=' where datau>='.$timestampstart.' AND datau<='.$timestampend;
}


while($q<$p)
  {
  $sql="select * from ".$SysValue['base']['table_name8']." $sorttime order by datau desc LIMIT $num_ot, $num_row ";
  $q++;
  $num_ot=$num_ot+$num_row;
  }
return $sql;
}

function Nav_news()// Навигация 
{
global $SysValue,$LoadItems,$timestamp;
$p=$SysValue['nav']['id']; if(@!$p) $p=1;
$num_row=$LoadItems['System']['num_row'];

if(empty($timestamp))
  $num_page=NumFrom("table_name8","");
  else $num_page=$num_row;

$i=1;
$num=$num_page/$num_row;
while ($i<$num+1)
    {
	if($i!=$p){
	
	if($i==1) $pageOt=$i+@$pageDo;
	 else $pageOt=$i+@$pageDo-$i;
	 
	$pageDo=$i*$num_row;
	@$navigat.="
	     <a href=\"./news_".$i.".html\">".$pageOt."-".$pageDo."</a> | ";
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
 <table cellpadding=\"0\" cellpadding=\"0\" border=\"0\">
        <tr >
	     <td class=style5>
".$SysValue['lang']['page_now'].": 
<a href=\"./news_".($p-1).".html\"><img src=\"images/shop/3.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\"></a>
$navigat&nbsp<a href=\"./news_".$p_to.".html\"><img src=\"images/shop/4.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" title=\"Вперед\"></a>
		</td>
       </tr>
        </table>
		";
	}
return @$nava;
}

function News()
{
global $SysValue,$LoadItems,$p;

$sql=Page_news();
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$podrob=stripslashes($row['podrob']);
	if($podrob!="")
	  {
	  $all="
	  <tr>
    <td align=right>
	<a href=\"./ID_".$id.".html\" class=2 title=\"".$row['zag']."\">Читать далее...</a>
	</td>
      </tr>
	  ";
	  }
	  else
	     {
		 $all="";
		 }
	
// Определяем переменые
$SysValue['other']['newsData']= $row['datas'];
$SysValue['other']['newsZag']= $row['zag'];
$SysValue['other']['newsKratko']= stripslashes($row['kratko']);
$SysValue['other']['newsAll']= $all;
$SysValue['other']['newsId']= $id;
//@$SysValue['other']['catalogCat']= "Дополнительно";

// Подключаем шаблон
@$dis.=ParseTemplateReturn($SysValue['templates']['main_news_forma']);
	}

// Определяем переменые
$SysValue['other']['productFound']= $SysValue['lang']['found_of_products'];
$SysValue['other']['productNum']= $LoadItems['NumNews'];
$SysValue['other']['productNumOnPage']=$SysValue['lang']['row_on_page'];
$SysValue['other']['productNumRow']=$LoadItems['System']['num_row'];
$SysValue['other']['productPage']=$SysValue['lang']['page_now'];
//$SysValue['other']['catalogCategory']=$SysValue['lang']['news'];
$SysValue['other']['productPageThis']=$p;
$SysValue['other']['productPageNav']=Nav_news();
$SysValue['other']['productPageDis']=@$dis;

// Подключаем шаблон
@$disp=ParseTemplateReturn($SysValue['templates']['news_page_list']);
return @$disp;
}


function NewsPodrob($id)
{
global $system,$PHP_SELF,$SysValue;
$id=TotalClean($id,1);
$sql="select * from ".$SysValue['base']['table_name8']." where id=$id";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);

// Определяем переменые
$SysValue['other']['newsData']= $row['datas'];
$SysValue['other']['newsZag']= $row['zag'];
$SysValue['other']['newsKratko']= stripslashes($row['kratko']);
$SysValue['other']['newsPodrob']= stripslashes($row['podrob']);
//@$SysValue['other']['catalogCat']= "Дополнительно";

// Подключаем шаблон
@$dis.=ParseTemplateReturn($SysValue['templates']['main_news_forma_full']);

// Определяем переменые
//$SysValue['other']['catalogCategory']=$SysValue['lang']['news'];
$SysValue['other']['productPageDis']=@$dis;

// Подключаем шаблон
if(!empty($row['zag']))
 @$disp=ParseTemplateReturn($SysValue['templates']['news_page_full']);
 else @$disp=404;
return @$disp;
}

function Vivod_mini_news()// Последние новости на главную страницу
{
global $SysValue,$PHP_SELF;
$sql="select * from ".$SysValue['base']['table_name8']." order by id desc LIMIT 0, 3";
$result=mysql_query($sql);
$i=0;
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$data=$row['datas'];
	$zag=$row['zag'];
	$kratko=stripslashes($row['kratko']);
	$podrob=$row['podrob'];
	if($podrob!="")
	  {
	  $all="
	  <tr>
    <td align=right colspan=2>
<img src=\"images/shop/arr2.gif\"   border=\"0\" hspace=\"3\" alt=\"".$zag."\">
<a href=\"../news/ID_".$id.".html\" class=2 title=\"".$zag."\">Читать далее...</a>
	</td>
      </tr>
	  ";
	  }
	  else
	     {
		 $all="";
		 }
// Определяем переменые
$SysValue['other']['newsZag']=$zag;
$SysValue['other']['newsData']=$data;
$SysValue['other']['newsKratko']=$kratko;
$SysValue['other']['newsLinkAll']=$all;
$SysValue['other']['newsId']=$id;

// Подключаем шаблон
@$dis.=ParseTemplateReturn($SysValue['templates']['news_main_mini']);
	}
return @$dis;
}

function Mini_news_main()
{
global $system,$SysValue,$news_ogr,$PHP_SELF;
$max_news=$system['num_cow'];
if(isset($news_ogr))
  {
  $max_news=$news_ogr;
  }
$num=Systems_all($SysValue['base']['table_name8']);
if($num==0){exit;}
if($max_news>$num){$max_news=$num;}
$n=0;
$sql="select * from ".$SysValue['base']['table_name8']." order by id desc LIMIT 0, $max_news";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$data=$row['datas'];
	$zag=$row['zag'];
	$kratko=stripslashes($row['kratko']);
	$podrob=$row['podrob'];
	if($n==0)
	 {
	 $td="<td width=\"10\"></td><td background=\"images/punkt_content.gif\" width=\"1\"></td><td width=\"10\"></td>";
     }
	 else
	     {
		 $td="";
		 }
	if($podrob!="")
	  {
	  $all="
	  <tr>
    <td align=right>
	<img src=\"images/but_5_3.gif\"  width=\"11\" height=\"9\" border=\"0\">
	<a href=\"../news/ID_".$id."\" style=\"font-size:10;font-weight:normal;\" class=price>
подробно</a>
	</td>
      </tr>
	  ";
	  }
	  else
	     {
		 $all="";
		 }
	@$display_news.="
	<td width=\"190\">
	<table class=tah11 cellpadding=\"5\" width=\"100%\">
	<td>
	<strong style=\"color:000000\">data</strong><br>
	</td>
</tr>
<tr>
    <td>
	<strong><u>$zag</u></strong><br>
	$kratko
	</td>
</tr>
<tr>
    <td align=right>
	$all
	</td>
</tr>

<tr>

	</table>
	</td>
	".$td."
	";
	$n++;
	}
$dis="
<table class=tah11 cellpadding=\"0\" cellspacing=\"0\">
<tr>
$display_news
</tr>
</table>
";
return $dis;
}
?>
