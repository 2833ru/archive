<?
function Page_gbook()// Создание страниц
{
global $SysValue,$LoadItems;
$p=$SysValue['nav']['id']; if(@!$p) $p=1;
$num_row=$LoadItems['System']['num_row'];
$num_ot=0;
$q=0;
while($q<$p)
  {
  $sql="select * from ".$SysValue['base']['table_name7']." where flag='1' order by id desc LIMIT $num_ot, $num_row ";
  $q++;
  $num_ot=$num_ot+$num_row;
  }
return $sql;
}

function Nav_gbook()// Навигация 
{
global $SysValue,$LoadItems;
$p=$SysValue['nav']['id']; if(@!$p) $p=1;
$num_row=$LoadItems['System']['num_row'];
$num_page=NumFrom("table_name7","where flag='1'");
$i=1;
$num=$num_page/$num_row;
while ($i<$num+1)
    {
	if($i!=$p){
	
	if($i==1) $pageOt=$i+@$pageDo;
	 else $pageOt=$i+@$pageDo-$i;
	 
	$pageDo=$i*$num_row;
	@$navigat.="
	     <a href=\"./page_".$i.".html\">".$pageOt."-".$pageDo."</a> | ";
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
<a href=\"./page_".($p-1).".html\"><img src=\"images/shop/3.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\"></a>
$navigat&nbsp<a href=\"./page_".$p_to.".html\"><img src=\"images/shop/4.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" title=\"Вперед\"></a>
		</td>
       </tr>
        </table>
</td></tr>
		";
	}
return @$nava;
}
 
function DispGbook()
{
global $SysValue,$LoadItems,$p,$SERVER_NAME;
$sql=Page_gbook();
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$mail=$row['mail'];
	$otvet=$row['otvet'];
	if($mail)
	{
	$d_mail="
	<b>$name</b>
	";
	}
	else
	   {
	   $d_mail="<font class=style8><b>$name</b></font>";
	   }
	//$otsiv=eregi_replace ("\n", "<br>",@$otsiv);
	
	if(@$otvet!="")
	$otvet=$otvet;
	else $otvet="";

// Определяем переменые
$SysValue['other']['gbookData']= dataV($row['datas']);
$SysValue['other']['gbookName']= $row['name'];
$SysValue['other']['gbookTema']= $row['tema'];
$SysValue['other']['gbookMail']= $d_mail;
$SysValue['other']['gbookOtsiv']= $row['otsiv'];
$SysValue['other']['gbookOtvet']= $otvet;
$SysValue['other']['gbookAdmin']= $LoadItems['System']['name'];

// Подключаем шаблон
@$dis.=ParseTemplateReturn($SysValue['templates']['main_gbook_forma']);
	}

// Определяем переменые
$SysValue['other']['producFound']= $SysValue['lang']['found_of_products'];
$SysValue['other']['productNum']= $LoadItems['NumGbook'];
$SysValue['other']['productNumOnPage']=$SysValue['lang']['row_on_page'];
$SysValue['other']['productNumRow']=$LoadItems['System']['num_row'];
$SysValue['other']['productPage']=$SysValue['lang']['page_now'];
//$SysValue['other']['catalogCategory']=$SysValue['lang']['gbook'];
$SysValue['other']['productPageThis']=$p;
$SysValue['other']['productPageNav']=Nav_gbook();
$SysValue['other']['productPageDis']=@$dis;

// Подключаем шаблон
@$disp=ParseTemplateReturn($SysValue['templates']['gbook_page_list']);
return @$disp;
}

// Запись отзыва в базу
function WriteGbook()
{
global $_POST,$LoadItems,$SysValue,$REMOTE_ADDR,$SERVER_NAME;

if(isset($_POST['send_gb']))
{
if(!preg_match("/@/",$_POST['mail_new']))//проверка почты
  {
  $_POST['mail_new']="";
  }
  if(@$_POST['name_new']!="" and @$_POST['otsiv_new']!="" and @$_POST['tema_new']!="")
   {
   $name_new=TotalClean($_POST['name_new'],2);
   $otsiv_new=TotalClean($_POST['otsiv_new'],2);
   $tema_new=TotalClean($_POST['tema_new'],2);
   $mail_new=TotalClean($mail_new,3);

$date = date("U");
$ip=$REMOTE_ADDR;                        
$sql="INSERT INTO ".$SysValue['base']['table_name7']."
VALUES ('','$date','$name_new','".$_POST['mail_new']."','$tema_new','$otsiv_new','','0')";
mysql_query($sql)or @die("Невозможно добавить к базе");

	$codepage  = "windows-1251";              
    $header  = "MIME-Version: 1.0\n";
    $header .= "From: ".@$name_new." <".@$mail_new.">\n";
    $header .= "Content-Type: text/plain; charset=$codepage\n";
    $header .= "X-Mailer: PHP/";
	$date=date("d.m.y");
	$zag=$LoadItems['System']['name']." - Уведомление о добалении отзыва / ".date("d-m-y");
$message="
Доброго времени!
---------------

С сайта ".$LoadItems['System']['name']." пришло уведомление о добалении отзыва 
в гостевую книгу.

Данные о пользователе:
----------------------

Имя:                ".@$name_new."
E-mail:             ".$_POST['mail_new']."
Тема сообщения:     ".@$tema_new."
Сообщение:          ".@$otsiv_new."  
Дата:               ".date("d-m-y H:s a")."
IP:                 ".$REMOTE_ADDR."

---------------

С уважением,
Компания ".$LoadItems['System']['company']."
http://".$SERVER_NAME;

mail($LoadItems['System']['adminmail2'],$zag,$message,$header);
  }
}
}
?>
