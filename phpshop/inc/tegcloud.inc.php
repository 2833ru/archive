<?
/*
+-------------------------------------+
|  PHPShop 2.1  Enterprise            |
|  Модуль Облака Тегов                |
+-------------------------------------+
*/

// Вычисляем размер ссылки
function FontSize($s){
if($s>20) $a=20;
else $a = 11+$s/2;
return $a;
}



function  TegCloud($n)
{
global $SysValue,$LoadItems;

$n = CleanSearch($n);
$my_limit = 100; // задается кол-во тегов для сравнения
switch($SysValue['nav']['nav']){
     
	 case(""):
	 $str=" where enabled='1' and keywords!='' order by RAND() LIMIT 0, $my_limit";
	 $tip="serach";
	 break;
	 
	 case("CID"):
	 $tip="words";
	 if($SysValue['nav']['path'] == "shop")
	 $str=" where category=$n and enabled='1'";
	    else $str="none";
	 break;
	 
	 case("UID"):
	 $tip="words";
	 $str=" where id=$n and enabled='1'";
	 break;
	 
	 default: $str=" where enabled='1' and keywords!='' order by RAND() LIMIT 0, $my_limit";
	 $tip="serach";
	 break;
}


if($str!="none"){
$sql="select keywords from ".$SysValue['base']['table_name2']." $str";
$result=mysql_query($sql);

while (@$row = mysql_fetch_array($result))
    {
	$keywords=$row['keywords'];
	$explode=explode(", ",$keywords);
	foreach($explode as $ev)
	if(!empty($ev)) $ArrayWords[]=$ev;
	}

if(!is_array($ArrayWords)) { // Если пусто
     $str=" where enabled='1' and keywords!='' order by RAND() LIMIT 0, $my_limit";
	 $tip="serach";
     $sql="select keywords from ".$SysValue['base']['table_name2']." $str";
     $result=mysql_query($sql);

    while (@$row = mysql_fetch_array($result))
    {
	$keywords=$row['keywords'];
	$explode=explode(", ",$keywords);
	foreach($explode as $ev)
	if(!empty($ev)) $ArrayWords[]=$ev;
	}


}	



if(is_array($ArrayWords))
foreach($ArrayWords as $v){
$count=array_keys($ArrayWords,$v);
$CloudCount[$v]['size']=count($count);
}


if(is_array($CloudCount))
foreach($CloudCount as $key=>$val)
  if($tip=="serach") @$disp.="<a href='/search/?words=$key' style='font-size:".FontSize($val['size'])."pt;'>$key</a>";
    else @$disp.="$key ";


if($tip=="serach")
$disp='<div id="wpcumuluscontent">загрузка флеш...</div><script type="text/javascript">
		   var dd=new Date(); 
		   var so = new SWFObject("/stockgallery/tagcloud.swf?rnd="+dd.getTime(), "tagcloudflash", "180", "180", "9", "#518EAD");
so.addParam("wmode", "transparent");
so.addParam("allowScriptAccess", "always");
so.addVariable("tcolor", "0x518EAD");
so.addVariable("tspeed", "150");
so.addVariable("distr", "true");
so.addVariable("mode", "tags");
so.addVariable("tagcloud", "<tags>'.$disp.'</tags>");
so.write("wpcumuluscontent");</script>';
  else $disp='<H1>'.$disp.'</H1>';
@$SysValue['sql']['num']++;

$SysValue['other']['leftMenuName']= $SysValue['lang']['tagcloud_name'];
$SysValue['other']['leftMenuContent']= $disp;
$dis=ParseTemplateReturn($SysValue['templates']['left_menu']);

}
return @$dis;
}

	
$admoption=unserialize($LoadItems['System']['admoption']);
if($admoption['cloud_enabled']==1)
$SysValue['other']['cloud']= TegCloud($SysValue['nav']['id']);
?>
