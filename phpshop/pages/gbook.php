<?
// Определяем переменые
if(@$error == "ok")
$SysValue['other']['Error']=" / <font color=\"red\"><strong>Ваш отзыв записан...</strong></font>";


	 @$disp.=DispGbook().'
	 <div align="center" style="padding:20">
 <a href="/gbook_forma/">
 [Оставить отзыв]</a>
 </div>
	 ';


$SysValue['other']['DispShop']=@$disp;
  
// Подключаем шаблон 
@ParseTemplate($SysValue['templates']['shop']);
	?>
