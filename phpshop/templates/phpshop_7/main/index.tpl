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
        <SCRIPT language="JavaScript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/js.js"></SCRIPT>
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
    <BODY onload="default_load('false','false');LoadPath('@ShopDir@');">
        <table class="header1Table" border="0" cellpadding="0" cellspacing="0" align="center">
            <tbody>
                <tr>
                    <td width="337" rowspan="2" align="left" valign="top">
                        <div class="header_information">
                            <div class="header_infoTel"><a href="/">@name@</a></div>
                            <div class="header_infoWork"><a href="/">@serverName@</a></div>
                            <div class="header_infoAddress"><a href="/">@descrip@</a></div>
                        </div>
                    </td>
                    <td align="left" valign="top" width="3" rowspan="2">
                        <div class="userRoomHidden" id="userform" onMouseOver="avtorizationOn('userform','userform');" onMouseOut="avtorizationOff('userform','userform');"><div class="userRoomDiv">
                        <form method="post" name="user_forma" class="userRoomForm">
@php 
$browser = "<iframe id='layerIframe'></iframe>";
$user_agent = $_SERVER['HTTP_USER_AGENT'];
if ( stristr($user_agent, 'MSIE 6.0') ) echo "$browser"; // IE6
if ( stristr($user_agent, 'MSIE 5.0') ) echo "$browser"; // IE5
php@
<table cellpadding="0" cellspacing="0" border="0" height="159" width="282">
                            <tbody>
                                <tr>
                                    <td colspan="2" height="39" align="left" valign="top">
                                        <div class="linkUserRoom1">
                                            <span class="topUserSpan"><a href="javascript:avtorizationClickOff('userform');" title="Войти">Войти</a></span>
                                            <img src="images/spacer.gif" class="topMenuImg2" align="absmiddle" height="11" width="1">
                                            <span class="topMenuSpan2"><a href="/users/register.html" title="Регистрация">Регистрация</a></span>                </div>            </td>
                                </tr>
                              <tr>
                                <td align="left" valign="top" colspan="2" height="36">
                                <div class="userRoomLogin">
                                    <div class="divinput1"><img src="images/clothes_07.gif"></div>
                                    <div class="divinput2"><input type="text" class="input_user" name="login" value="@UserLogin@"></div>
                                    <div class="divinput3"><img src="images/clothes_09.gif"></div>
                                </div>        </td>
                              </tr>
                                <tr>
                                    <td align="left" valign="top" colspan="2" height="34">
                                    <div class="userRoomPassword">
                                        <div class="divinput1"><img src="images/clothes_07.gif"></div>
                                        <div class="divinput2"><input type="password" class="input_user" name="password" value="@UserPassword@"></div>
                                        <div class="divinput3"><img src="images/clothes_09.gif"></div>
                                    </div>            </td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top"><div class="linkUserRoom2"><a href="javascript:ChekUserForma();avtorizationOff('userform');"><img src="images/clothes_06.gif" border="0"></a></div></td>
                                    <td align="left" valign="top">
                                        <div class="linkUserRoom8">
                                            <table cellpadding="0" cellspacing="0" border="0" width="128">
                                                <tr>
                                                    <td align="left" valign="top"><div class="linkUserRoom3"><input type="checkbox" value="1" name="safe_users" @UserChecked@></div></td>
                                                    <td><div class="linkUserRoom4">запомнить</div><div class="linkUserRoom5"><a href="/users/sendpassword.html" title="Забыли пароль?">забыли пароль?</a></div></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="userRoomError"><span class="topUserSpan">@usersError@</span></div>
                                    </td>
                              </tr>
                            </tbody>
                        </table><input type="hidden" value="1" name="user_enter"></form></div></div>
                    </td>
                    <td width="600" align="left" valign="top" height="62">
                        <div class="topMenu">
                            <span class="topMenuSpan"><a href="/" title="Главная">Главная</a></span>
                            @topMenu@
                        </div>
                        <div class="topUser">
                            <span class="topUserSpan"><a href="javascript:avtorizationClickOn('userform');" title="Войти">Войти</a></span>
                            <img src="images/spacer.gif" class="topMenuImg2" align="absmiddle" height="11" width="1">
                            <span class="topMenuSpan2"><a href="/users/register.html" title="Регистрация">Регистрация</a></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top"><div>
                            <div class="topComparison" id="compare" style="display:@compareEnabled@"><span class="topOrderSpan1"><a href="/compare/" title="Сравнить товары">Сравнение</a></span> <span class="topOrderSpan2"><a href="/compare/" title="Сравнить товары">(<span id="numcompare">@numcompare@</span> шт.)</a></span></div>
                            <div class="topOrderText"><span class="topOrderSpan1"><a href="/order/" title="Оформить заказ">Корзина</a></span> <span class="topOrderSpan2"><a href="/order/" title="Оформить заказ">(<span id="num">@num@</span> шт.)</a></span></div>
                            <div class="topOrderImg" id="order" style="display:@orderEnabled@"><a href="/order/" title="Оформить заказ"><img src="images/clothes_03.gif" border="0"></a></div>
				@valutaDisp@
                        </div></td>
                </tr>
            </tbody>
        </table>
        @usersDisp@
        <table class="header2Table" border="0" cellpadding="0" cellspacing="0" align="center">
            <tbody>
                <tr>
                    <td align="left" valign="middle" width="100%" class="topCat">
				@leftCatal@
                    </td>
                    <td align="left" valign="top">
                        <div id="search">
                            <form method="post" name="forma_search" action="/search/" onSubmit="return SearchChek()">
                                <table cellpadding="0" cellspacing="0" border="0"><tr><td><input name="words" class="search" maxLength="30" onFocus="this.value=''"></td><td><input class="search_but" type="submit" value=" "></td></tr></table>
                            </form>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <script type="text/javascript" src="java/highslide/highslide-p.js"></script>
    <link rel="stylesheet" type="text/css" href="java/highslide/highslide.css"/>
    <script type="text/javascript">
        hs.registerOverlay({html: '<div class="closebutton" onclick="return hs.close(this)" title="Закрыть"></div>',position: 'top right',fade: 2});
        hs.graphicsDir = 'java/highslide/graphics/';
        hs.wrapperClassName = 'borderless';
    </script>
    <table class="contentTable" border="0" cellpadding="0" cellspacing="0" align="center">
        <tbody>
            <tr>
                <td align="left" valign="top">
                    <img src="images/spacer.gif" height="1" width="620"><div class="flashIndex"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="619" height="330" title="phpshop">
                            <param name="movie" value="images/indexFlash.swf">
                            <param name="quality" value="high">
                            <param name="wmode" value="transparent" />
                            <embed src="images/indexFlash.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="619" height="330" wmode="transparent"></embed>
                        </object></div>

                        <div class="specTitleDiv">
                        <div class="specTitDiv1">@mainContentTitle@</div>

                    </div>
                    @mainContent@

                    <div class="specTitleDiv" style="padding-top:10px">
                        <div class="specTitDiv1">спецпредложение</div>
                        <div class="specTitDiv3"><a href="/spec/"><img src="images/clothes_21.gif" border="0"></a></div>
                        <div class="specTitDiv2"><a href="/spec/">еще из спецпредложения</a></div>
                    </div>
                    @specMain@


                </td>
                <td align="left" valign="top"><div style="width:20px"></div></td>
                <td align="left" valign="top"><img src="images/spacer.gif" height="1" width="300"><div class="newsTitle">Новости</div>
                    @miniNews@
                    <table cellpadding="0" cellspacing="0" border="0" width="300">
                        <tbody>
                            <tr>
                                <td align="left" valign="middle"><a href="/" class="brendA1"></a></td>
                                <td align="center" valign="middle"><a href="/" class="brendA2"></a></td>
                                <td align="right" valign="middle"><a href="/" class="brendA3"></a></td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle" height="60"><a href="/" class="brendA4"></a></td>
                                <td align="center" valign="middle"><a href="/" class="brendA5"></a></td>
                                <td align="right" valign="middle"><a href="/" class="brendA6"></a></td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="height:25px"></div>
                    @skinSelect@
                    <div class="newtipTitleDiv2">
                        <div class="newtipTitDiv1">Новая коллекция</div>
                        <div class="newtipTitDiv2"><a href="/newtip/"><img src="images/clothes_23.gif" border="0"></a></div>
                    </div>
                    <div class="inIndexTip">@specMainIcon@</div>
                    <div class="rightBlokDiv">
                        <div class="rightBlokTitle">Навигация</div>
                        <div class="rightBlokContent">
                            <div class="divNavigationA"><a href="/price/">Прайс-лист</a></div>
                            <div class="divNavigationA"><a href="/news/">Новости</a></div>
                            @pageCatal@
                            <div class="divNavigationA"><a href="/links/">Полезные ссылки</a></div>
                            <div class="divNavigationA"><a href="/map/">Карта сайта</a></div>
                            <div class="divNavigationA"><a href="/forma/">Форма связи</a></div>
                        </div>
                    </div>
                    @rightMenu@
                </td>
            </tr>
        </tbody>
    </table>
    <table class="bottonTable" border="0" cellpadding="0" cellspacing="0" align="center">
        <tbody>
            <tr>
                <td align="center" valign="middle">
                    <div class="divBotContent">2010 &copy; @pageReg@@topMenu@</div>
                </td>
            </tr>
        </tbody>
    </table>
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