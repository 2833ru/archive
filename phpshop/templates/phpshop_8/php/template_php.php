<?php
//дублируем и заменяем каталог
function shopLeftCat_replace(){
	$content = Vivod_cats(); 
	$content1 = str_replace("podCatTiTOut","TiTOut",$content);
	$content2 = str_replace("podCatTiTOver","TiTOver",$content1);
	$content3 = str_replace("divCatId","divCatIdBot",$content2);
	
	return $content3;
}
//подключаем и выводим смену картинок
function imagefader(){
$msg = "
<script language=\"javascript\" type=\"text/javascript\">
//<![CDATA[
var picture1_php = 'images/furniture_16.jpg';
var picture2_php = 'images/furniture_17.jpg';
var picture3_php = 'images/furniture_18.jpg';

//установка ссылки при клике на картинку
var picture1link = '/';
var picture1target = '_top';
var picture2link = '/';
var picture2target = '_top';
var picture3link = '/';
var picture3target = '_top';

//параметры для смены картинок
var display_time = 7000;
var tween_time = 750;
//]]>
</script>

<div style=\"display:none;\">
	<img alt=\"\" src=\"images/furniture_16.jpg\"/>
	<img alt=\"\" src=\"images/furniture_17.jpg\"/>
	<img alt=\"\" src=\"images/furniture_18.jpg\"/>
</div>

<script language=\"javascript\" type=\"text/javascript\" src=\"phpshop/templates/phpshop_8/javascript/fader.js\"></script>

<div style=\"height:255px; width:557px; margin-top: 16px; overflow:hidden; background:#\">
  <div id=\"s5_wrapper_if\" style=\"position:absolute;z-index:1;overflow:hidden;height:255px; width:557px;\">
	<div style=\"background-image: url('images/furniture_16.jpg');width: 557px; height:255px;\" id=\"blenddiv\">
	  <img src=\"images/furniture_16.jpg\" style=\"border: 0 none;  opacity: 0;\" id=\"blendimage\" alt=\"\" class=\"reflect\"></img>
	</div>
  </div>
</div>

<script type=\"text/javascript\"> 
function picture1_next(id) {if (thumbchangeon_php == 0) {picture2('picture2');}}
function picture2_next(id) {if (thumbchangeon_php == 0) {picture3('picture3');}}
function picture3_next(id) {if (thumbchangeon_php == 0) {picture1('picture1');}}
picture1('picture1');
</script>";
return $msg;
}
?>