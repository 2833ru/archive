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
<SCRIPT language="JavaScript" src="java/java2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="java/cartwindow.js"></SCRIPT>
<SCRIPT language="JavaScript" src="phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="java/tabpane.js"></SCRIPT>
</HEAD>
<BODY onLoad="pressbutt_load('@thisCat@','@pathTemplate@','false','false');NavActive('@NavActive@');LoadPath('@ShopDir@');" class="bg_all">


<div id="bg_header">

		
<div id="cartwindow" style="position:absolute;left:0px;top:0px;bottom:0px;right:0px;visibility:hidden;"> 
<table width="100%" height="100%">
<tr>
    <td width="40" vAlign=center>
    <img src="images/shop/i_commercemanager_med.gif" alt="" width="32" height="32" border="0" align="absmiddle">    </td>
    <td><b>Внимание...</b><br>Товар добавлен в корзину</td>
</tr>
</table>
</div> 

<div id="comparewindow" style="position:absolute;left:0px;top:0px;bottom:0px;right:0px;visibility:hidden;"> 
<table width="100%" height="100%">
<tr>
    <td width="40" vAlign=center>
    <img src="images/shop/i_compare_med.gif" alt="" width="32" height="32" border="0" align="absmiddle">    </td>
    <td><b>Внимание...</b><br>Товар добавлен в сравнение</td>
</tr>
</table>
</div>

	<div id="all">
	
		<div id="topmenu">
			<div id="foot_navi">
			<div class="topmenu_pad">
<div>
		<div id="act"><img class="act_1" src="images/act_left.gif"></div>
		<div id="act" class="act_2"><a href="/" class="navigation" >Главная</a></div>
		<div id="act"><img class="act_1" src="images/act_right.gif"></div>
	</div>
</div>
				@topMenu@
			</div>
		</div>
		
		<div id="header">
			<div id="leftmenu">
				<div class="name"><a href="/">@name@</a></div>
				<div><a href="/">@serverName@</a></div>
				<div class="descript">@descrip@</div>
			</div>
				<div id="search">
					<FORM method="post" name="forma_search" action="/search/" onSubmit="return SearchChek()">						
						<input name="words" class="search" maxLength=30 onFocus="this.value=''">
						<input id="search_but" type="image" src="images/but_search.gif" title="Искать">
					</FORM>
					<div><a href="/search/">расширенный поиск</a></div>
				</div>
		</div>


		<div id="content">
			
			<div id="left">	
				<div class="plashka_zag">Каталог</div>
					<ul class="catalog">
						@leftCatal@
					</ul>
				<div id="bg_nav_1">
					<div class="bg_nav_1">
						<div class="plashka_zag_2">Навигация</div>
						<ul class="catalog">
							<li class="catalog_page"><a href="/price/">Прайс-лист</a></li>
							<li class="catalog_page"><a href="/news/">Новости</a></li>
                        </ul>
						<ul class="catalog">@pageCatal@</ul>
                        <ul class="catalog">
							<li class="catalog_page"><a href="/links/">Полезные ссылки</a></li>
							<li class="catalog_page"><a href="/map/">Карта сайта</a></li>
							<li class="catalog_page"><a href="/forma/">Форма связи</a></li>
						</ul>
					</div>
				</div>
				<div id="bg_nav_2"></div>
				@calendar@
				@oprosDisp@
				@leftMenu@
				<div class="cloud">@cloud@</div>	
			</div>
			
<div id="right">
	<div class="plashka_zag_3">Корзина</div>
		<div id="cart">
			<div class="line_2">
				<div class="left">товаров в корзине:</div>
				<div class="num"><span id="num">@num@</span> шт.</div>
			</div>
			<div class="line_2">
			<div class="left">сумма заказа:</div>
			<div id="sum" class="sum">@sum@ @productValutaName@</div>
			</div>
			<div class="line_2">
				<div class="left">товаров в сравнении:</div>
				<div class="num"><span id="numcompare">@numcompare@</span> шт.</div>
			</div>
			@valutaDisp@
			<div id="order" style="display:@orderEnabled@"><A href="/order/"><img src="images/but_order.gif" border="0" vspace="5" title="Оформить заказ"></A></div>
			<div id="compare" style="display:@compareEnabled@"><A href="/compare/"><img src="images/but_compare.gif" border="0" title="Сравнить товары"></A></div>
		</div>
		@skinSelect@
		@usersDisp@
		<div class="pad_10">@rightMenu@</div>
		<div class="specmain">
			<div id="bg_nav_1_2">
				<div class="bg_nav_1_2">
					<div class="plashka_zag_4">@specMainTitle@</div>
					@specMainIcon@
					<div class="pad_4"></div>
				</div>
			</div>
			<div id="bg_nav_2_2"></div>
			<div id="product_cart_1_2">
				<div id="product_cart_3">
					<A href="/newtip/" title="Все новинки"><img src="images/but_newtip.gif" border="0"></A>

				</div>
			</div>
		</div>
</div>
<script type="text/javascript" src="java/highslide/highslide-p.js"></script>
<link rel="stylesheet" type="text/css" href="java/highslide/highslide.css"/>
<script type="text/javascript">
hs.registerOverlay({html: '<div class="closebutton" onclick="return hs.close(this)" title="Закрыть"></div>',position: 'top right',fade: 2});
hs.graphicsDir = 'java/highslide/graphics/';
hs.wrapperClassName = 'borderless';
</script>
<div id="center">
	@DispShop@
	@banersDisp@
</div>
			
		</div>
		
		<div id="content_2"></div>


<div id="footer">
	<div>&copy; @pageReg@. Все права защищены.<br>Телефон: @telNum@</div>
	<div class="pad_5"><img src="images/feed.gif" alt="" width="16" height="16" border="0" align="absmiddle"> <a href="/rss/" title="RSS">RSS</a><span>|</span><a href="/map/">Карта сайта</a></div>
</div>		
			
	</div>
	
	</div>

</div>
<br>