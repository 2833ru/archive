<?
session_start();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>PHPShop Debuger</title>
<style>
html, body {
	border:		0;
}
body{
FONT-SIZE: 12px;
background: ButtonFace;
	font:		MessageBox;
	font:		Message-Box;
}
input{
FONT-SIZE: 12px;
}
#allspec{
   margin-top:10px;
   background-color: white;
   PADDING: 5px; 
   FONT-SIZE: 12px;
   overflow: auto;
   width:100%;
   height:70%;
}
</style>
<style media="print" type="text/css">
<!-- 
.nonprint {
	display: none;
}
 -->
</style>
</head>

<body>
<h4>PHPShop Debuger 1.21</h4>
<div id="allspec">

<? 

switch($forma){

     case 1:
	 echo "<pre>";
     print_r ($_SESSION['LoadItems']['Product']);
     echo"</pre>";
	 break;

     case 2:
	 if(is_array($_SESSION['Debug']))
     foreach($_SESSION['Debug'] as $k=>$v){
     echo "<pre>";
     print_r ($k."=".$v);
     echo"</pre>";
	 }
	 break;
	 
	 case 3:
     echo "<pre>";
     print_r($_SESSION['Debug']);
     echo"</pre>";
	 break;
}
?>
</div>
<div class="nonprint">
<br>
<b>Примечание:</b> для отладки переменной внесети ее в массив отладки.<br>
<b>Пример:</b> переменная $test=123, тогда $SysValue['sql']['debug']['test']=123;
</div>
<hr style="margin-top:15px" class="nonprint">
<div align="right" class="nonprint">
<input type="button" value="Печать" onclick="window.print()">
<input type="button" value="Закрыть" onclick="window.close()">
</div>
</body>
</html>
