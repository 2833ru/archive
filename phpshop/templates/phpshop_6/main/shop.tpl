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
    <BODY onload="pressbutt_load('@thisCat@', '@pathTemplate@', 'false', 'false');
            LoadPath('@ShopDir@');">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tbody>
                <tr>
                    <td>
                        <div id="cartwindow" class="message">
                            <div class="ico_add_to_card"><b>Внимание...</b><br>Товар добавлен в корзину</div>
                        </div>
                        <div id="comparewindow" class="message">
                            <div class="ico_add_to_compare"><b>Внимание...</b><br>Товар добавлен в сравнение</div>
                        </div>
                        <div class="headerfone_1">
                            <div class="headerfone_2">
                                <div class="headerfone_3">
                                    <div class="headertable"><table cellpadding="0" cellspacing="0" border="0" width="100%" height="125">
                                            <tbody>
                                                <tr>
                                                    <td align="left" valign="top" height="59"><div class="headerdiv_left"></div></td>
                                                    <td align="left" valign="top" width="23%"></td>
                                                    <td align="left" valign="middle" width="77%"><div class="userroom_div_hidden" id="userform"><div class="userroom_div"><form method="post" name="user_forma" class="userroom_form"><table cellpadding="0" cellspacing="0" border="0" height="150" width="266">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td colspan="3" height="24" align="left" valign="top"><div class="link_userroom_1"><a href="javascript:avtorizationOff('userform');">авторизация</a></div><div class="link_userroom_6">/</div><div class="link_userroom_7"><a href="/users/register.html" title="регистрация">регистрация</a></div></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left" valign="top" colspan="3" height="48">
                                                                                    <div class="userroom_error">@usersError@</div>
                                                                                    <div class="userroom_login">
                                                                                        <div class="divinput4">
                                                                                            логин
                                                                                        </div>
                                                                                        <div class="input_user">
                                                                                            <input type="text"  name="login" value="@UserLogin@">
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left" valign="top" colspan="3" height="26">
                                                                                    <div class="userroom_password">
                                                                                        <div class="divinput4">пароль</div>
                                                                                        <div class="input_user">
                                                                                            <input type="password"  name="password" value="@UserPassword@">
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="40" align="left" valign="top"><div class="link_userroom_5"><input type="checkbox" value="1" name="safe_users" @UserChecked@></div></td>
                                                                                <td width="158" align="left" valign="top"><div class="link_userroom_8">запомнить</div><div class="link_userroom_3"><a href="/users/sendpassword.html" title="Забыли пароль?">забыли пароль?</a></div></td>
                                                                                <td width="68" align="left" valign="top"><div class="link_userroom_4"><a href="javascript:ChekUserForma();avtorizationOff('userform');"><img src="images/bt-template_69.gif" width="44" height="28" border="0"></a></div></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table><input type="hidden" value="1" name="user_enter"></form></div></div><table cellpadding="0" cellspacing="0" border="0" width="100%" height="59">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="30%" align="left" valign="middle"><div class="user_div_1" id="topUser"><span class="user_text_1"><a href="javascript:avtorizationOn('userform');">авторизация</a></span><span class="user_text_2">&nbsp; / &nbsp;</span><span class="user_text_1"><a href="/users/register.html" title="регистрация">регистрация</a></span></div></td>
                                                                    <td width="70%" align="right" valign="middle"><div>
                                                                            <div class="order_div_5"><a href="/order/" class="order_link_1"><img src="images/bt-template_05.png" width="27" height="28" border="0" class="iePNG"></a></div>
                                                                            <div class="order_div_4"><div class="order_div_6" ><a href="/order/" class="order_link_1"><span class="user_order_2"id="sum">@sum@</span>&nbsp;<a href="/order/" class="order_link_1"><span class="user_order_2">@productValutaName@</span></a></div></div>
                                                                            <div class="order_div_3"><a href="/order/" class="order_link_1"><img src="images/bt-template_03.png" width="12" height="28" border="0" class="iePNG"></a></div>
                                                                            <div class="order_div_2"><span class="user_order_1"><a href="/order/">В Вашей корзине <span id="num">@num@</span> шт.</a></span></div>
                                                                            <div class="order_div_1"><a href="/order/" style="cursor:pointer"><img src="images/bt-template_02.png" width="25" height="14" border="0" class="iePNG"></a></div>
                                                                            <div class="order_div_7"><a href="/compare/" class="order_link_1"><img src="images/bt-template_05.png" width="27" height="28" border="0" class="iePNG"></a></div>
                                                                            <div class="order_div_4"><div class="order_div_6"><a href="/compare/" class="order_link_1"><span class="user_order_2" id="numcompare">@numcompare@</span></a></div></div>
                                                                            <div class="order_div_3"><a href="/compare/" class="order_link_1"><img src="images/bt-template_03.png" width="12" height="28" border="0" class="iePNG"></a></div>
                                                                            <div class="divcompare"><span class="user_order_1"><a href="/compare/" title="Сравнение товаров">Товаров в сравнении</a></span></div>
                                                                        </div></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>@php
                                                        if(isset($_SESSION['UsersId'])){
                                                        echo "<script type='text/javascript'>var topUser = document.getElementById('topUser');
        topUser.style.display = 'none';</script>";
                                                        }
                                                        php@</td>
                                                    <td align="left" valign="top"><div class="headerdiv_right"></div></td>
                                                </tr>
                                                <tr>
                                                    <td align="left" valign="top"></td>
                                                    <td align="left" valign="top"><div class="headerdiv_logotip">
                                                            <a href="http://@serverName@" title="@serverName@">
                                                                <img src="@logo@" alt="@name@" border="0" alt="@name@"></a>
                                                        </div></td>
                                                    <td align="left" valign="top"><table cellpadding="0" cellspacing="0" border="0" width="100%" height="66">
                                                            <tbody>
                                                                <tr>
                                                                    <td align="left" valign="top"><img src="images/bt-template_06.png" width="4" height="66" class="iePNG"></td>
                                                                    <td class="menutable"><div class="menudiv_1"></div></td>
                                                                    <td align="left" valign="middle" class="menutable" width="69%"><div class="menu_name_index"><a href="/">Главная</a></div>@topMenu@</td>
                                                                    <td align="left" valign="middle" class="menutable" width="9%"><div class="valutaDispClass">@valutaDisp@</div></td>
                                                                    <td align="left" valign="middle" class="menutable" width="31%"><div><form method="post" name="forma_search" action="/search/" onSubmit="return SearchChek()"><table cellpadding="0" cellspacing="0" border="0" width="100%"><tbody><tr><td width="100%"><input name="words" class="search" maxlength=30 onFocus="this.value = ''" value="Я ищу..."></td><td><input id="search_but" type="image" src="images/bt-template_15.gif" title="Искать"></td></tr></tbody></table></form></div></td>
                                                                    <td align="left" valign="top"><img src="images/bt-template_07.png" width="4" height="66" class="iePNG"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table></td>
                                                    <td align="left" valign="top"></td>
                                                </tr>
                                            </tbody>
                                        </table></div>@usersDisp@<div class="tablenewtip"><table cellpadding="0" cellspacing="0" border="0" width="100%" height="130">
                                            <tbody>
                                                <tr>
                                                    <td><div class="headerdiv_left"></div></td>
                                                    <td width="50%" align="left" valign="top"><div class="header_topTextBlok_10"><table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                                <tbody>
                                                                    <tr>
                                                                        <td><img src="images/bt-template_60.gif" width="5" height="5"></td>
                                                                        <td align="right" valign="top" class="header_topTextBlok_1"><img src="images/bt-template_62.jpg" width="450" height="5"></td>
                                                                        <td><img src="images/bt-template_57.gif" width="5" height="5"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="left" valign="top" class="header_topTextBlok_8"><img src="images/bt-template_67.gif" width="5" height="106"></td>
                                                                        <td width="100%" align="left" valign="top" class="header_topTextBlok_3"><div class="header_topTextBlok_6"><div class="header_topTextBlok_4"><table cellpadding="0" cellspacing="0" border="0" width="100%" class="header_topTextBlok_9">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td align="left" valign="top" height="111"><div class="header_topTextBlok_11"><img src="images/bt-template_68.png" width="243" height="123" border="0" class="iePNG"></div>
                                                                                                    <div class="header_topTextBlok_12"></div></td>
                                                                                                <td align="left" valign="top" height="111" width="99%"><div class="header_topTextBlok_14"><strong>@name@</strong></div>
                                                                                                    <div class="header_topTextBlok_15"><a href="/">@serverName@</a></div>
                                                                                                    <div class="header_topTextBlok_13">@descrip@</div></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table></div></div></td>
                                                                        <td align="left" valign="top" class="header_topTextBlok_2"><img src="images/bt-template_61.gif" width="5" height="106"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><img src="images/bt-template_59.gif" width="5" height="5"></td>
                                                                        <td align="right"class="header_topTextBlok_5"><img src="images/bt-template_65.gif" width="450" height="5"></td>
                                                                        <td><img src="images/bt-template_58.gif" width="5" height="5"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table></div></td>
                                                    <td width="50%" align="left" valign="top" class="newTipTable">
                                                        @showcase@</td>
                                                    <td><div class="headerdiv_right"></div></td>
                                                </tr>
                                            </tbody>
                                        </table></div><table cellpadding="0" cellspacing="0" border="0" width="100%" height="300">
                                        <tbody>
                                            <tr>
                                                <td><div class="centerdiv_left"></div></td>
                                                <td align="left" valign="top"><table cellpadding="0" cellspacing="0" border="0" height="80" width="220">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" valign="top"><div class="catalog_div"><div class="catalog_div_1"><div class="catalog_div_2"><div class="catalog_div_3"><div class="catalog_div_4"><div class="catalog_div_5"><div class="catalog_div_6"><div class="catalog_div_7"><table cellpadding="1" cellspacing="0" border="0" height="80" width="220">
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <td align="left" valign="top">@leftCatal@</td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table></div></div></div></div></div></div></div></div></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>@skinSelect@<table cellpadding="0" cellspacing="0" border="0" height="80" width="220" class="textBlokRight">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" valign="top"><div class="catalog_div"><div class="catalog_div_1"><div class="catalog_div_2"><div class="catalog_div_3"><div class="catalog_div_4"><div class="catalog_div_5"><div class="catalog_div_6"><div class="catalog_div_7"><table cellpadding="1" cellspacing="0" border="0" height="80" width="220">
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <td align="left" valign="top"><div class="TOPcatalog_div"><span class="TOPcatalog_text2"><a href="/price/" title="Прайс-лист">Прайс-лист</a></span></div><div class="TOPcatalog_div"><span class="TOPcatalog_text2"><a href="/news/" title="Новости">Новости</a></span></div><div class="TOPcatalog_div"><span class="TOPcatalog_text2"><a href="/gbook/" title="Новости">Отзывы</a></span></div>@pageCatal@<div class="TOPcatalog_div"><span class="TOPcatalog_text2"><a href="/links/" title="Полезные ссылки">Полезные ссылки</a></span></div><div class="TOPcatalog_div"><span class="TOPcatalog_text2"><a href="/map/" title="Карта сайта">Карта сайта</a></span></div><div class="TOPcatalog_div"><span class="TOPcatalog_text2"><a href="/forma/" title="Форма связи">Форма связи</a></span></div></td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table></div></div></div></div></div></div></div></div></td>
                                                            </tr>
                                                        </tbody>
                                                    </table><table cellpadding="0" cellspacing="0" border="0" width="220" height="105" class="textBlokRight">
                                                        <tbody>
                                                            <tr>
                                                                <td width="60" align="left" valign="top"><div class="textBlokRight_div1"></div></td>
                                                                <td width="160" align="left" valign="top"><div class="textBlokRight_div2"><div class="textBlokRight_div3"><strong>Круглосуточная</strong> поддержка</div><div class="textBlokRight_div4">@telNum@<br>
                                                                            @adminMail@</div></div></td>
                                                            </tr>
                                                        </tbody>
                                                    </table><div class="textBlokLeft_1">Последние новости
                                                    </div><div class="textBlokLeft_2">@miniNews@</div>
                                                    @leftMenu@
                                                    @oprosDisp@
                                                    @calendar@
                                                    @cloud@</td>
                                                <td><div class="centerdiv_center"></div></td>
                                                <td width="99%" align="left" valign="top">
                                                    <script type="text/javascript" src="java/highslide/highslide-p.js"></script>
                                        <link rel="stylesheet" type="text/css" href="java/highslide/highslide.css"/>
                                        <script type="text/javascript">
        hs.registerOverlay({html: '<div class="closebutton" onclick="return hs.close(this)" title="Закрыть"></div>', position: 'top right', fade: 2});
        hs.graphicsDir = 'java/highslide/graphics/';
        hs.wrapperClassName = 'borderless';
                                        </script>
                                        @DispShop@@banersDisp@</td>
                                        <td><div class="centerdiv_right"></div></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%" height="161">
                                        <tbody>
                                            <tr>
                                                <td height="11" colspan="5">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td height="62" colspan="5">

                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="0%" height="88"><div class="bottomdiv_left"></div></td>
                                                <td width="25%" align="left" valign="top"><div class="bottomdiv_1">&copy; @pageReg@.<br>Все права защищены.</div></td>
                                                <td width="49%" align="left" valign="top"><div class="bottomdiv_1">@button@</div></td>
                                                <td width="26%" align="left" valign="top">
                                                    <div class="bottomdiv_3">@telNum@</div>
                                                    <div class="bottomdiv_2">горячая линия</div></td>
                                                <td width="0%"><div class="bottomdiv_right"></div></td>
                                            </tr>
                                        </tbody>
                                    </table></div></div></div><img src="images/spacer.gif" width="1000" height="1" title="phpshop" alt="phpshop"></td>
                </tr>
            </tbody>
        </table>