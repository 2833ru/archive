<?
function Vivod_baner()// выводит банеры
{
global $SysValue,$system,$SERVER_NAME,$IDbaner,$REQUEST_URI;
$IDbaner=TotalClean($IDbaner,5);
if(isset($IDbaner))
{
$sql="select * from ".$SysValue['base']['table_name15']." where flag='1' and id!='$IDbaner' order by RAND() LIMIT 0, 1";
$result=mysql_query($sql);
$num=mysql_num_rows($result);
}
if(!$IDbaner or $num<1)
   {
   $sql="select * from ".$SysValue['base']['table_name15']." where flag='1' order by RAND() LIMIT 0, 1";
   $result=mysql_query($sql);
   }

while($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$content=stripslashes($row['content']);
	$count_all=$row['count_all'];
	$count_today=$row['count_today'];
	$datas=$row['datas'];
	$limit_all=$row['limit_all'];
	$IDbaner=$id;
	session_register('IDbaner');
	if($datas!=date("d.m.y"))
	  {
      $count_today=0;
	  $datas=date("d.m.y");
	  }
	  else
	     {
		 $count_today++;
		 }
	if($count_all>$limit_all)
	  {
	  $sql3="UPDATE ".$SysValue['base']['table_name15']."
SET
flag = '0'
where id='$id'";
$result3=mysql_query($sql3)or @die("Невозможно изменить запись");

$codepage  = "windows-1251";              
$header  = "MIME-Version: 1.0\n";
$header .= "From:   <".$SysValue['my']['mail_to_order'].">\n";
$header .= "Content-Type: text/plain; charset=$codepage\n";
$header .= "X-Mailer: PHP/";
$zag="Закончились показы у банера ".$name."";

$contentmail="
Закончились показы у банера ".$name.".
Для редактирования состояния банерной сети перейдите в панель
администрирования магазина http://$SERVER_NAME/phpshop/admpanel/

Характеристики банера
---------------------------------------------------------

Название: $name
ID банера: $id
Лимит: $limit_all
Дата отключения: ".date("d.m.y")."
---------------------------------------------------------

Powered & Developed by www.PHPShop.ru
".$SysValue['license']['product_name'];

mail($LoadItems['System']['adminmail'],$zag,$contentmail,$header);
	  }

if(empty($row['dir'])){
// Определяем переменые
$SysValue['other']['banerContent']= $content;
$SysValue['other']['banerTitle']= $name;
// Подключаем шаблон
$dis=ParseTemplateReturn($SysValue['templates']['baner_list_forma']);
}
else{
$dirs= explode(",",$row['dir']);
if(is_array($dirs))
foreach($dirs as $dir)
if(@strpos($REQUEST_URI, $dir) or $REQUEST_URI==$dir){
$SysValue['other']['banerContent']= $content;
$SysValue['other']['banerTitle']= $name;
// Подключаем шаблон
$dis=ParseTemplateReturn($SysValue['templates']['baner_list_forma']);
}
}

	
$count_all++;
$sql2="UPDATE ".$SysValue['base']['table_name15']."
SET
count_all='$count_all',
count_today='$count_today',
datas='$datas'
where id='$id'";
$result2=mysql_query($sql2)or @die("Невозможно изменить запись");
	}
return @$dis;
}
?>