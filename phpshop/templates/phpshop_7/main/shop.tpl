<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
    <HEAD>
        <TITLE>@pageTitl@</TITLE>
        <META http-equiv="Content-Type" content="text-html; charset=windows-1251">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <META name="description" content="@pageDesc@">
        <META name="keywords" content="@pageKeyw@">@seourl_canonical@
        <META name="copyright" content="@pageReg@">
        <META name="engine-copyright" content="PHPSHOP.RU, @pageProduct@">
        <META name="domen-copyright" content="@pageDomen@">
        <META content="General" name="rating">
        <META name="ROBOTS" content="ALL">
        <LINK rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <LINK rel="icon" href="/favicon.ico" type="image/x-icon">
        <LINK href="@pageCss@" type="text/css" rel="stylesheet">
        <SCRIPT language="JavaScript" type="text/javascript" src="java/phpshop.js"></SCRIPT>
        <SCRIPT language="JavaScript" type="text/javascript" src="java/tabpane.js"></SCRIPT>
        <SCRIPT language="JavaScript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/js.js"></SCRIPT>
        <SCRIPT language="JavaScript" type="text/javascript" src="phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <SCRIPT language="JavaScript" src="java/swfobject.js"></SCRIPT>

    </HEAD>
    <BODY onload="pressbutt_load('@thisCat@', '@pathTemplate@', 'false', 'false'); LoadPath('@ShopDir@');">
        <table cellpadding="0" cellspacing="0" align="center" class="body">
            <tr>
                <td>
                    <div id="cartwindow" class="message">
                        <div class="ico_add_to_card"><b>Внимание...</b><br>Товар добавлен в корзину</div>
                    </div>
                    <div id="comparewindow" class="message">
                        <div class="ico_add_to_compare"><b>Внимание...</b><br>Товар добавлен в сравнение</div>
                    </div>
                    <table class="header1Table radius_up" border="0" cellpadding="0" cellspacing="0" align="center">
                        <tbody>
                            <tr>
                                <td width="297" rowspan="2" align="left" valign="top">
                                    <div class="header_information">
                                        <div class="header_infoTel"><a href="/">@name@</a></div>
                                        <div class="header_infoWork"><a href="/">@serverName@</a></div>
                                        <div class="header_infoAddress"><a href="/">@descrip@</a></div>
                                    </div>
                                </td>
                                <td align="left" valign="top" width="3" rowspan="2">
                                    <div class="userRoomHidden" id="userform" onMouseOver="avtorizationOn('userform', 'userform');" onMouseOut="avtorizationOff('userform', 'userform');"><div class="userRoomDiv">
                                            <form method="post" name="user_forma" class="userRoomForm">
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
                                                                    <div class="divinput2"><input type="text" class="input_user" name="login" placeholder="E-mail"  value="@UserLogin@"></div>
                                                                    <div class="divinput3"><img src="images/clothes_09.gif"></div>
                                                                </div>        </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="top" colspan="2" height="34">
                                                                <div class="userRoomPassword">
                                                                    <div class="divinput1"><img src="images/clothes_07.gif"></div>
                                                                    <div class="divinput2"><input type="password" class="input_user" placeholder="Пароль" name="password" value="@UserPassword@"></div>
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
                                <td width="640" align="left" valign="top" height="62">
                                    <div class="topMenu">
                                        <table cellpadding="0" cellspacing="0" border="0"><tr>
                                                <td>
                                                    <span class="topMenuSpan"><a href="/" title="Главная">Главная</a></span>
                                                </td>
                                                @topMenu@
                                            </tr></table>
                                    </div>
                                    <div class="topUser" id="topUser">
                                        <span class="topUserSpan"><a href="javascript:avtorizationClickOn('userform');" title="Войти">Войти</a></span>
                                        <img src="images/spacer.gif" class="topMenuImg2" align="absmiddle" height="11" width="1">
                                        <span class="topMenuSpan2"><a href="/users/register.html" title="Регистрация">Регистрация</a></span>
                                    </div>
                                    @php
                                    if(isset($_SESSION['UsersId'])){
                                    echo "<script type='text/javascript'>var topUser = document.getElementById('topUser');
                topUser.style.display = 'none';</script>";
                                    }
                                    php@    

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
                                            <table cellpadding="0" cellspacing="0" border="0"><tr><td><input name="words" class="search" maxLength="30" onFocus="this.value = ''"></td><td><input class="search_but" type="submit" value=" "></td></tr></table>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="contentTable" border="0" cellpadding="0" cellspacing="0" align="center">
                        <tbody>
                            <tr>
                                <td align="left" valign="top" class="leftCat"><img src="images/spacer.gif" height="1" width="220">
                                    <script type="text/javascript">
                <!--
                                        catalogAktiv('divCatId@thisCat@');
                catalogAktiv('divCatIdBot@thisCat@');
                -- ></script>
                                    <div class="leftBlokDiv">						
                                        @skinSelect@
                                        <div class="leftBlokTitle">Навигация</div>
                                        <div class="leftBlokContent">
                                            <div class="divNavigationA"><a href="/price/">Прайс-лист</a></div>
                                            <div class="divNavigationA"><a href="/news/">Новости</a></div>
                                            @pageCatal@
                                            <div class="divNavigationA"><a href="/links/">Полезные ссылки</a></div>
                                            <div class="divNavigationA"><a href="/map/">Карта сайта</a></div>
                                            <div class="divNavigationA"><a href="/forma/">Форма связи</a></div>
                                            <div class="divNavigationA"><a href="/gbook/">Отзывы</a></div>
                                        </div>
                                    </div>
                                    @oprosDisp@
                                    @leftMenu@
                                    @calendar@
                                    @cloud@
                                </td>
                                <td align="left" valign="top"><div style="width:20px"></div></td>
                                <td align="left" valign="top">
                                    <script type="text/javascript" src="java/highslide/highslide-p.js"></script>
                        <link rel="stylesheet" type="text/css" href="java/highslide/highslide.css"/>
                        <script type="text/javascript">
                                    hs.registerOverlay({html: '<div class="closebutton" onclick="return hs.close(this)" title="Закрыть"></div>', position: 'top right', fade: 2});
                                    hs.graphicsDir = 'java/highslide/graphics/';
                                    hs.wrapperClassName = 'borderless';
                        </script>
                        <img src="images/spacer.gif" height="1" width="700">@DispShop@@banersDisp@
                        </td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="bottonTable radius_down" border="0" cellpadding="0" cellspacing="0" align="center">
                        <tbody>
                            <tr>
                                <td align="center" valign="middle"><table cellpadding="0" cellspacing="0" border="0" class="divBotContent"><tr><td>
                                                &copy; @pageReg@
                                            </td>@topMenu@</tr></table></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table><div align="center">@button@</div>