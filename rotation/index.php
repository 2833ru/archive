<?
//Управление
$addimageurl=""; //Дополнительный путь от сервера к картинкам, если есть
$my_limit=5; //Количество товаров
//Управление

function Chek($stroka)// проверка вводимых цифр
{
if (!ereg ("([0-9])", $stroka)) $stroka=$my_limit;
return abs($stroka);
}


if($limit<20 and !empty($limit)) 
$limit=Chek($limit);
  else $limit=$my_limit;
  

//Инициализация
$SysValue=parse_ini_file("../phpshop/inc/config.ini",1);
$SERVER=$SysValue['connect']['host']; 
$USERDB=$SysValue['connect']['user_db']; 
$PASS=$SysValue['connect']['pass_db']; 
$BASENTRG=$SysValue['connect']['dbase'];  //offline //Данные для доступа к MYSQL
$server='http://'.$_SERVER['HTTP_HOST'].'/';
//Инициализация

//Соединение с базой данных
@mysql_connect($SERVER,$USERDB,$PASS) or die ('<P class=text>Приносим свои извинения, но по техническим причинам сайт на данный момент не доступен.</P>Причина: Невозможно соединиться с сервером базы данных.<BR>Пожалуйста, нажмите по <A href="mailto:'.$devmail.'?Subject=DB_CONNECT_ERROR">этой ссылке</A>, чтобы написать администратору сайта о данной неисправности. Тогда возможно уже сегодня сайт снова начнет функционировать.</CENTER>'.mysql_errno().mysql_error());
@mysql_select_db ($BASENTRG) or die ('<P class=text>Приносим свои извинения, но по техническим причинам сайт на данный момент не доступен.</P>Причина: Невозможно выбрать базу данных.<BR>Пожалуйста, нажмите по <A href="mailto:'.$devmail.'?Subject=DB_CHOISE_ERROR">этой ссылке</A>, чтобы написать администратору сайта о данной неисправности. Тогда возможно уже сегодня сайт снова начнет функционировать.</CENTER>');
@mysql_query( 'set names cp1251' );
//Соединение с базой данных

//Получаем валюту
$sql='SELECT * FROM '.$SysValue['base']['table_name24'].' WHERE enabled="1" and kurs="1"  LIMIT 1';
$result=mysql_query($sql);
$rows=mysql_num_rows($result);
$row=mysql_fetch_array($result);
$valuta=$row['code'];
//Получаем валюту

//Получаем и выводим товары
$sql='SELECT * FROM `'.$SysValue['base']['table_name2'].'` WHERE (pic_small!="" AND enabled="1") ORDER BY RAND() LIMIT '.$limit;
$result=mysql_query($sql);
$rows=mysql_num_rows($result);

if(!empty($Psize)) $Psize=" width=".$Psize." ";
 
while ($row=mysql_fetch_array($result)) {
  $id=$row['id'];
  $name=$row['name'];
  $pic=$row['pic_small'];
  $price=$row['price'];

  //Вывод
  @$dis.='<A href="'.$server.'shop/UID_'.$id.'.html" title="'.$name.'"  target="_blank">'.$name.' - '.$price.$valuta.'<BR>
        <IMG TITLE="'.$name.'" '.$Psize.' BORDER=0 SRC="'.$server.$addimageurl.$pic.'"></A><BR><BR>
        ';
  //Вывод
}

// Выводим товары
if(empty($color)) $style="0089C0";
if(empty($Fsize)) $Fsize="12";


echo "
<html>
<head>
	<title>Rotation</title>
<style>

body{
font-family: Tahoma;
}

a {
color: $color;
font-size: ".$Fsize."px;
text-decoration: underline;
}

a:hover{
text-decoration: none;
}
</style>
</head>

<body>
".$dis."
<div align=center><a href='/' target='_blank'>Все товары &raquo;</a></div>"
;
?>