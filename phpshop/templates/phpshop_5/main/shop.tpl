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
<BODY onLoad="pressbutt_load('@thisCat@','@pathTemplate@','false','false');NavActive('@NavActive@');LoadPath('@ShopDir@');">
<div id="cartwindow" style="position:absolute;left:0px;top:0px;bottom:0px;right:0px;visibility:hidden;"> 
<table width="100%" height="100%">
<tr>
    <td width="40" vAlign=center>
    <img src="images/shop/i_commercemanager_med.gif" alt="" width="32" height="32" border="0" align="absmiddle">
    </td>
    <td><b>Внимание...</b><br>Товар добавлен в корзину</td>
</tr>
</table>
</div>
<div id="comparewindow" style="position:absolute;left:0px;top:0px;bottom:0px;right:0px;visibility:hidden;"> 
<table width="100%" height="100%">
<tr>
    <td width="40" vAlign=center>
    <img src="images/shop/i_compare_med.gif" alt="" width="32" height="32" border="0" align="absmiddle">
    </td>
    <td><b>Внимание...</b><br>Товар добавлен в сравнение</td>
</tr>
</table>
</div>
<div align="center">
  <div class="mainlayer">
	<div class="topimag">
<table width="977" height="155">
<tr>
        	  		<td width="208" height="43" valign="top">
<table width="100%">
                 	   <tr>
                    	<td width="51" height="30" align="right" valign="middle"><img src="images/icons/star.gif" alt="star" width="24" height="23" class="topIcons"></td>
                    	<td width="145" valign="middle">&nbsp;<a
onclick="this.style.behavior='url(#default#homepage)'; this.setHomePage('http://@serverName@/'); return false"
href="http://@serverName@"><u><span class="textTop">Сделать стартовой</span></u></a></td>			
              </tr></table>          </td>
    <td width="520" valign="top">
<table width="100%">
                 	   <tr>
               		   <td width="23" height="30" valign="middle"><img src="images/icons/phone.gif" width="21" height="24" class="topIcons"></td>
               		   <td width="123" valign="middle">&nbsp;&nbsp;<span class="textTopPhone">@telNum@</span></td>
                    	<td width="18" valign="middle"><img src="images/icons/email.gif" alt="email" width="18" height="19" class="topIcons"></td>
                    	<td width="336" valign="middle">&nbsp;&nbsp;<a href="mailto:@adminMail@"><u><span class="textTop">@adminMail@</span></u></a></td>			
              </tr></table>          </td>
<td width="233" valign="top">
                 	 <table width="100%">
                 	   <tr>
               		   <td width="85" height="30" valign="middle">
           		       <div class="textTopNaGlavnuyDiv"><a href="/"><span class="textTopNaGlavnuy">на главную</span></a></div>               		   </td>
                    	<td width="136" valign="middle"><a href="/map/"><u><span class="textTop">карта сайта</span></u></a>&nbsp;&nbsp;<a href="/page/page4.html"><u><span class="textTop">контакты</span></u></a></td>			
                      </tr></table>                  </td>
   		</tr>
            	<tr>
              		<td height="69" rowspan="2" align="center" valign="middle"><a href="/"><img src="../images/spacer.gif" width="200" height="70" border="0"></a></td>
                  <td height="69" align="left" valign="top">
<table width="100%"><tr>
                    	<td width="237" height="57" align="left" valign="bottom"><span class="textPokupki1">приятных</span>&nbsp;<span class="textPokupki2">покупок!</span></td>
               	<td width="271" valign="middle">Мой заказ:<br><a href="/users/"><u>Авторизация</u></a></td>			
                 		</tr></table>                    </td>
                  <td><form method="post" name="forma_search" action="/search/" onSubmit="return SearchChek()"><table width="100%">
                  	<tr>
                  	<td width="67%"><input type="text" value="" name="words" maxLength="30" onFocus="this.value=''" class="topInputText" style="width:170px" /></td>
               		<td width="33%"><input type="submit" value="Искать" name="submit" class="topInputSubmit" /></td>			
                 	</tr>
                    <tr>
                  	<td colspan="2" align="left" valign="middle">Логика поиска:&nbsp;и<input type="Radio" value="1" name="set" @searchSetA@>&nbsp;или&nbsp;<input type="Radio" value="2" name="set" @searchSetB@ ></td>
               		</tr>
                    </table></form></td>
            	</tr>
           		<tr>
               	  <td height="31">&nbsp;</td>
                	<td valign="bottom">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="textZagolovok">Корзина</span></td>
            	</tr>
        	</table>
    </div>
    	<div class="middlediv">
   		  <table width="984" height="214" border="0" class="middletable">
        		<tr>
            		<td width="206" valign="top">
                   	  <table width="206">
                        <tr>
                        	<td width="13%">&nbsp;</td>
                            <td width="87%" class="menuList2"><img src="images/icons/strelka.gif" alt="phpshop" align="bottom">&nbsp;&nbsp;<a href="/">Главная</a></td>
                        </tr>
                         @topMenu@
                         <tr>
                        	<td>&nbsp;</td>
                            <td class="menuList"><img src="images/icons/strelka.gif" alt="phpshop" align="bottom">&nbsp;&nbsp;<a href="/news/">Новости</a></td>
                        </tr>
                         <tr>
                        	<td>&nbsp;</td>
                            <td class="menuList"><img src="images/icons/strelka.gif" alt="phpshop" align="bottom">&nbsp;&nbsp;<a href="/price/">Прайс-лист</a></td>
                        </tr>
                        </table><br>
                      <div class="divtovarov">
                        	<table width="195">
                        	  <tr><td width="187" height="40" align="center" valign="bottom"><span class="textZagolovok">Каталог товаров</span></td>
                        	</tr></table>
                      </div>
        				<div class="divtovarov2">
                       	  @leftCatal@
                          @calendar@
                       	  <br></div><br>
                      	  @oprosDisp@ 
              		</td>
           		  <td width="526" valign="top">
                  <table width="100%">
                  	<tr>
                  	  <td height="75" valign="bottom">&nbsp;</td>
                  	</tr>
                  	<tr><td valign="top">
<script type="text/javascript" src="java/highslide/highslide-p.js"></script>
<link rel="stylesheet" type="text/css" href="java/highslide/highslide.css"/>
<script type="text/javascript">
hs.registerOverlay({html: '<div class="closebutton" onclick="return hs.close(this)" title="Закрыть"></div>',position: 'top right',fade: 2});
hs.graphicsDir = 'java/highslide/graphics/';
hs.wrapperClassName = 'borderless';
</script>
                    <table style="padding-left: 13px;padding-right: 0px;" cellpadding="0" cellspacing="0" border="0" width="98%"><tr><td>
                    @DispShop@
                    </td></tr></table>
                    	</td>
                  	</tr>
                  </table>
                  </td>
              		<td width="238" valign="top">
           			  <div class="divkorzina">
                      <table width="100%">
                        <tr>
                        	<td width="11%">&nbsp;</td>
                            <td width="54%">Товаров в корзине:</td>
                            <td width="35%"><div id="num">@num@</div></td>
                        </tr>
                        <tr>
                        	<td width="11%">&nbsp;</td>
                            <td width="54%">Сумма заказа (руб.):</td>
                          <td width="35%"><div id="sum"><span style="font-size:14px" class="rightMiddleText">@sum@</span> <span class="rightMiddleText">@productValutaName@</span></div></td>
                        </tr>
                        <tr>
                        	<td width="11%">&nbsp;</td>
                            <td width="54%" colspan="2"><div id="compare" style="display:@compareEnabled@"><a href="/compare/" title="Сравнение товаров"  style="color: white; font-weight: bold;"><span style="font-size:14px" class="rightMiddleText"><b>Сравнить товары</b></span></A></div></td>
                        </tr>
                        <tr>
                        	<td width="11%">&nbsp;</td>
                            <td width="54%" colspan="2"><div  id="order" style="display:@orderEnabled@"  ><A href="/order/"><span style="font-size:14px" class="rightMiddleText"><b>Оформить заказ</b></span></A></div></td>
                        </tr>
                        @valutaDisp@
                        </table>
                      </div><br>
						@usersDisp@
                       <br>
                        <table width="238" border="0" cellpadding="0" cellspacing="0">
                      <tr><td width="27">&nbsp;</td>
                      <td width="211"><span class="textZagolovok">Сменить дизайн</span></td>
                      </tr>
                      <tr><td colspan="2"><div class="skinSelect" style="padding-left:18px">@skinSelect@</div></td></tr>
                      </table>
                      <br>
<div class="divks">
                        	<table width="100%" border="0"><tr><td height="37" valign="bottom">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="textZagolovok">Каталог статей</span></td>
                        	</tr></table>
                        </div>
        				<div class="divks2">
                        	<table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                        	<td width="7%" height="10" align="right"></td>
                          <td width="93%"><u>@pageCatal@</u></td>
                        </tr>
                        </table><br>
                      </div><br>
                      <table width="238">
                      <tr><td width="18">&nbsp;</td>
                      <td width="208"><span class="textZagolovok">Новости</span></td></tr>
                      @miniNews@
                      </table>
                      <br>
				<table width="238" border="0">
					<tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td colspan="2"><a href="/news/">Все новости</a></td>
					</tr>
                    <tr>
                    <td>&nbsp;</td>
                    <td width="29"><img src="images/icons/rss.gif" alt="rss" width="27" height="26" align="middle"></td>
                    <td width="199">&nbsp;<a href="/rss/" title="RSS"><u>Новости в формате RSS</u></a></td>
					</tr>
                 </table>
       			  </td>
       		</tr>
       	  </table>
        </div>
        <div class="nizGreen">
        	<table width="976" height="62" class="nizGreenTable">
        		<tr>
        			<td>&nbsp;</td><td align="center" valign="top">@banersDisp@</td>
                  <td>&nbsp;</td>
        		</tr>
       		</table>
   	</div>
	</div>
	<div class="bottomlayer">
	  <table border="0" width="100%">
    		<tr>
       		  <td align="left" valign="middle"><span class="bottomlayerText">Copyright © @pageReg@<br>Все права защищены.</span><br><a href="/map/"><span class="bottomlayerText">Карта сайта</span></a> <span class="bottomlayerText">|</span> <a href="/links/"><span class="bottomlayerText">Полезные ссылки</span></a></td>
				<td align="center" valign="bottom">&nbsp;</td>
			  <td align="right" valign="middle"><span class="bottomlayerText">&nbsp;</span></td>
			</tr>
   	  </table>
	</div>
</div>