<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<TITLE>@pageTitl@</TITLE>
<META http-equiv="Content-Type" content="text-html; charset=windows-1251">
<META name="description" content="@pageDesc@">
<META name="keywords" content="@pageKeyw@">
<META name="copyright" content="@pageReg@">
<META name="engine-copyright" content="PHPSHOP.RU, @pageProduct@">
<META name="domen-copyright" content="@pageDomen@">
<META content="General" name="rating">
<META name="ROBOTS" content="ALL">
<LINK rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<LINK rel="icon" href="favicon.ico" type="image/x-icon">
<LINK href="@pageCss@" type="text/css" rel="stylesheet">
<SCRIPT language="JavaScript" type="text/javascript" src="java/java2.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="java/cartwindow.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
<SCRIPT language="JavaScript" src="java/swfobject.js"></SCRIPT>

<!--[if lt IE 7]>
<![if gte IE 5.5]>
<script type="text/javascript" src="java/fixpng.js"></script>
<style type="text/css"> 
.iePNG, IMG { filter:expression(fixPNG(this)); } 
.iePNG A { position: relative; }/* стиль для нормальной работы ссылок в элементах с PNG-фоном */
</style>
<![endif]>
<![endif]-->

</HEAD>
<BODY onload="default_load('false','false');NavActive('index');LoadPath('@ShopDir@');">
<table width="1000" align="center" cellpadding="0" cellspacing="0" border="1">
  <tr>
    <td><table width="1000" height="120" cellpadding="10" cellspacing="0" border="1">
        <tr>
          <td width="34%">
			<div style="padding-bottom: 10px"><a href="http://@serverName@" title="@name@">@name@</a></div>
            <div style="padding-bottom: 10px"><a href="http://@serverName@" title="@serverName@">@serverName@</a></div>
            <div style="padding-bottom: 10px"><a href="http://@serverName@" title="@descrip@">@descrip@</a></div>
            <div style="padding-bottom: 10px"><a href="mailto:@adminMail@" title="@adminMail@">Моя почта</a></div>
          </td>
          <td width="34%">
            <div style="padding-bottom: 10px">товаров в корзине: <span id="num" style="font-weight:600">@num@</span> шт.</div>
            <div style="padding-bottom: 10px">на сумму: <span id="sum" style="font-weight:600">@sum@</span> @productValutaName@</div>
			<!-- Вывод смены валюты [main/valuta_forma.tpl] -->
            @valutaDisp@
            <!-- Вывод смены валюты -->
            <div id="order" style="display:@orderEnabled@; padding-bottom: 10px"><a href="/order/" title="Оформить заказ">Оформить заказ</a></div>
            <div id="compare" style="display:@compareEnabled@; padding-bottom: 10px"><a href="/compare/" title="Сравнение товаров">Сравнение: <span id="numcompare">@numcompare@</span> шт.</a></div>
          </td>
          <td width="34%">
			<!-- Вывод авторизации пользователя [main/users_forma.tpl, main/users_forma_enter.tpl] -->
            @errorLogin@
            @usersDisp@
            <!-- Вывод авторизации пользователя -->
          </td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><table width="1000" cellpadding="0" cellspacing="0" border="1">
        <tr>
          <td>
            <table align="left" cellpadding="0" cellspacing="0" border="1" id="index">
              <tr>
                <td style="padding: 5px"><a href="/" title="Главная">Главная</a></td>
              </tr>
            </table>
			<!-- Вывод главного меню сайта [main/top_menu.tpl] -->
			@topMenu@
            <!-- Вывод главного меню сайта -->
          </td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><table width="1000" cellpadding="0" cellspacing="0" border="0">
        <tr>
		  <td width="10" bgcolor="#f0f0f0">&nbsp;</td>
          <td valign="top" width="215">
			<!-- Вывод смены дизайна [main/left_menu.tpl] -->
			@skinSelect@
            <!-- Вывод смены дизайна -->
            <h4 style="font-size: 15px;color: #000000">Поиск по сайту:</h4>
            <form method="post" name="forma_search" action="/search/" onsubmit="return SearchChek()">
              <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td>
					<input name="words" maxLength="30" onfocus="this.value=''" value="Я ищу..." style="margin-right: 6px; width: 120px">
                  </td>
                  <td>
					<input type="submit" value="Искать" name="submit">
                  </td>
                </tr>
              </table>
            </form>
            <div><a href="/search/">расширенный поиск</a></div>
            
            <h4 style="font-size: 15px;color: #000000">Каталог товаров</h4>
            
            <ul style="padding: 0px; margin: 0px; list-style-type: none;">
            <!-- Вывод каталога товаров [catalog/catalog_forma.tpl, catalog/catalog_forma_2.tpl, catalog/catalog_forma_3.tpl, catalog/podcatalog_forma.tpl] -->
			@leftCatal@
			<!-- Вывод каталога товаров -->
            </ul>
            
            <h4 style="font-size: 15px;color: #000000">Каталог статей</h4>
            
            <ul style="padding: 0px; margin: 0px; list-style-type: none;">
            <!-- Вывод каталога статей [catalog/catalog_page_forma.tpl, catalog/catalog_page_forma_2.tpl, catalog/podcatalog_page_forma.tpl] -->
            @pageCatal@
            <!-- Вывод каталога статей -->
            </ul>
            
            <h4 style="font-size: 15px;color: #000000">Навигация</h4>
            
            <ul style="padding: 0px; margin: 0px; list-style-type: none;">
              <li><a href="/price/" title="Прайс-лист">Прайс-лист</a></li>
              <li><a href="/news/" title="Новости">Новости</a></li>
              <li><a href="/gbook/" title="Новости">Отзывы</a></li>
              <li><a href="/opros/" title="Новости">Опрос</a></li>
              <li><a href="/links/" title="Полезные ссылки">Полезные ссылки</a></li>
              <li><a href="/map/" title="Карта сайта">Карта сайта</a></li>
              <li><a href="/forma/" title="Форма связи">Форма связи</a></li>
            </ul>
            
            <!-- Вывод левого текстового блока [main/left_menu.tpl] -->
            @leftMenu@
            <!-- Вывод левого текстового блока -->
            
            <!-- Вывод опроса [opros/opros_forma.tpl, opros/opros_list.tpl] -->
            @oprosDisp@
            <!-- Вывод опроса -->
            
            <!-- Вывод облака тегов [main/left_menu.tpl] -->
            @cloud@
            <!-- Вывод облака тегов -->
            </td>
  <td width="15" bgcolor="#f0f0f0">&nbsp;</td>
          <td valign="top" width="520">
          
<!-- Вывод подключения выплывающих картинок -->
<script type="text/javascript" src="java/highslide/highslide-p.js"></script>
<link rel="stylesheet" type="text/css" href="java/highslide/highslide.css"/>
<script type="text/javascript">
hs.registerOverlay({html: '<div class="closebutton" onclick="return hs.close(this)" title="Закрыть"></div>',position: 'top right',fade: 2});
hs.graphicsDir = 'java/highslide/graphics/';
hs.wrapperClassName = 'borderless';
</script>
<!-- Вывод подключения выплывающих картинок -->

          <h4 style="font-size: 15px;color: #000000">Витрина</h4>
          
            <!-- Вывод flash витрины -->
            <div id="flashban" align="center">загрузка флеш...</div>
<script type="text/javascript">
var dd=new Date(); 
var so = new SWFObject("/stockgallery/banner.swf?rnd="+dd.getTime(), "banner", "480", "150", "9", "#ffffff");
so.addParam("flashvars", "itempath=/stockgallery/item.swf&xmlpath=/stockgallery/banner.xml.php");
so.addParam("quality", "best");
so.addParam("scale", "noscale");
so.addParam("wmode", "opaque");
so.write("flashban");
</script>
            <!-- Вывод flash витрины -->

          <!-- Вывод названия начальной страницы -->
          <h4 style="font-size: 15px;color: #000000">@mainContentTitle@</h4>
          <!-- Вывод названия начальной страницы -->
            
          <!-- Вывод содержимого начальной страницы -->
          <div style="padding:10px">@mainContent@</div>
          <!-- Вывод содержимого начальной страницы -->
          
          <h4 style="font-size: 15px;color: #000000">Каталог продукции</h4>
          
		  <!-- Вывод каталогов для витрины главной страницы [catalog/catalog_table_forma.tpl] -->
          @leftCatalTable@
          <!-- Вывод каталогов для витрины главной страницы -->
          
          <h4 style="font-size: 15px;color: #000000">Сейчас покупают</h4>
          
          <!-- Вывод списка покупаемых товаров -->
          @nowBuy@
          <!-- Вывод списка покупаемых товаров -->
            
          <h4 style="font-size: 15px;color: #000000">Спецпредложения</h4>
          
		  <!-- Вывод спецпредложений [product/main_product_forma_1.tpl, product/main_product_forma_2.tpl, product/main_product_forma_3.tpl] -->
          @specMain@
          <!-- Вывод спецпредложений -->
          
          <h4 style="font-size: 15px;color: #000000">Последние новости</h4>
          
		  <!-- Вывод новостей [news/news_main_mini.tpl] -->
          @miniNews@
          <!-- Вывод новостей -->
          
		  <!-- Вывод баннера [banner/baner_list_forma.tpl] -->
          @banersDisp@
          <!-- Вывод баннера -->
            
          </td>
		  <td width="15" bgcolor="#f0f0f0">&nbsp;</td>
          <td valign="top" width="215">
          
			<!-- Вывод заголовка новинок -->
			<h4 style="font-size: 15px;color: #000000">@specMainTitle@</h4>
			<!-- Вывод заголовка новинок -->
            
			<!-- Вывод новинок [product/main_spec_forma_icon.tpl] -->
            @specMainIcon@
			<!-- Вывод новинок -->
            
			<!-- Вывод новинок [main/right_menu.tpl] -->
            @rightMenu@
			<!-- Вывод новинок -->
            
          </td>
		  <td width="10" bgcolor="#f0f0f0">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><table width="1000" height="100" cellpadding="10" cellspacing="0" border="1">
        <tr>
          <td>
          	<p>
              Copyright &copy; @pageReg@.<br>
              Телефон: @telNum@<br>
              <a href="/rss/" title="RSS">RSS</a> | 
              <a href="/pda/" title="PDA" target="_blank">PDA</a>
            </p>
          </td>
        </tr>
      </table></td>
  </tr>
</table>

<div id="cartwindow" style="position:absolute;left:0px;top:0px;bottom:0px;right:0px;visibility:hidden;">
  <table width="100%" height="100%">
    <tr>
      <td width="40" vAlign=center><img src="images/shop/i_commercemanager_med.gif" alt="" width="32" height="32" border="0" align="absmiddle"> </td>
      <td><b>Внимание...</b><br>
        Товар добавлен в корзину</td>
    </tr>
  </table>
</div>

<div id="comparewindow" style="position:absolute;left:0px;top:0px;bottom:0px;right:0px;visibility:hidden;">
  <table width="100%" height="100%">
    <tr>
      <td width="40" vAlign=center><img src="images/shop/i_compare_med.gif" alt="" width="32" height="32" border="0" align="absmiddle"> </td>
      <td><b>Внимание...</b><br>
        Товар добавлен в сравнение</td>
    </tr>
  </table>
</div>
