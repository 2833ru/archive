<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
    <HEAD>
        <TITLE>@pageTitl@</TITLE>
        <META http-equiv="Content-Type" content="text-html; charset=windows-1251">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
        <SCRIPT language="JavaScript" type="text/javascript" src="java/phpshop.js"></SCRIPT>
        <SCRIPT language="JavaScript" type="text/javascript" src="phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <SCRIPT language="JavaScript" src="java/swfobject.js"></SCRIPT>
        <SCRIPT language="JavaScript" type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/js.js"></SCRIPT>
    </HEAD>
    <BODY onload="default_load('false', 'false');
            LoadPath('@ShopDir@');">
        <table cellpadding="0" cellspacing="0" border="0" id="TRoot" align="center">
            <tbody>
                <tr>
                    <td class="leftLine"><div class="rightLine">
                            <div id="cartwindow" class="message">
                                <div class="ico_add_to_card"><b>Внимание...</b><br>Товар добавлен в корзину</div>
                            </div>
                            <div id="comparewindow" class="message">
                                <div class="ico_add_to_compare"><b>Внимание...</b><br>Товар добавлен в сравнение</div>
                            </div>
                            <table cellpadding="0" cellspacing="0" border="0" class="topMenu">
                                <tr>
                                    <td><div class="space2"></div></td>
                                    <td align="right" valign="middle" width="100%">@topMenu@</td>
                                    <td><div class="space2"></div></td>
                                </tr>
                            </table>
                            <div class="r">
                                <table cellpadding="0" cellspacing="0" border="0" id="THeader">
                                    <tbody>
                                        <tr>
                                            <td valign="top" width="40%"><div id="logoName"><a href="http://@serverName@" title="@name@">@name@</a></div>
                                                <div id="logoServer"><a href="http://@serverName@" title="@serverName@">@serverName@</a></div>
                                                <div id="logoDescrip"><a href="http://@serverName@" title="@descrip@">@descrip@</a></div></td>
                                            <td valign="top" width="60%"><div id="HeaderDiv">
                                                    <div class="enter"></div>
                                                    <div class="divider"><img src="images/furniture_08.gif"></div>
                                                    <div class="currency"><div>@valutaDisp@</div></div>
                                                    <div class="divider"><img src="images/furniture_08.gif"></div>
                                                    <div id="compare" style="display:@compareEnabled@">
                                                        <div class="purchase2"><span class="linkNone"><a href="/compare/">сравнение (<span id="numcompare">@numcompare@</span>)</a></span></div>
                                                        <div class="divider"><img src="images/furniture_08.gif"></div>
                                                    </div>
                                                    <div class="purchase2"><span class="linkNone"><a href="/order/">(<span id="num">@num@</span>) шт </a></span><span class="linkNone"><a href="/order/">(<span id="sum">@sum@</span>) сумма</a></span></div>
                                                    <div class="purchase" id="order" style="display:@orderEnabled@"><a href="/order/" title="оформить заказ"><img src="images/furniture_07.gif" border="0"></a></div>
                                                </div>
                                                @errorLogin@
                                                @usersDisp@
                                                <form class="Search" method="post" name="forma_search" action="/search/" onsubmit="return SearchChek()">
                                                    <div class="Search1R">
                                                        <input type="image" src="images/furniture_10.gif">
                                                    </div>
                                                    <div class="Search2R">
                                                        <div class="Search3R">
                                                            <input type="text" class="search" name="words" maxLength="30" onfocus="this.value = ''" value="Я ищу...">
                                                        </div>
                                                    </div>
                                                </form></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table cellpadding="0" cellspacing="0" border="0" id="TMenu">
                                    <tbody>
                                        <tr>
                                            <td><div class="space2"></div></td>
                                            <td align="left" valign="middle" class="topCat">@leftCatal@</td>
                                            <td><div class="space1"></div></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <script type="text/javascript" src="java/highslide/highslide-p.js"></script>
                                <link rel="stylesheet" type="text/css" href="java/highslide/highslide.css"/>
                                <script type="text/javascript">
        hs.registerOverlay({html: '<div class="closebutton" onclick="return hs.close(this)" title="Закрыть"></div>', position: 'top right', fade: 2});
        hs.graphicsDir = 'java/highslide/graphics/';
        hs.wrapperClassName = 'borderless';
                                </script>
                                <table cellpadding="0" cellspacing="0" border="0" id="TContent">
                                    <tbody>
                                        <tr>
                                            <td><div class="space2"></div></td>
                                            <td align="left" valign="top" width="100%">

                                                <script language="javascript" type="text/javascript">
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

                                                <div style="display:none;">
                                                    <img alt="" src="images/furniture_16.jpg"/>
                                                    <img alt="" src="images/furniture_17.jpg"/>
                                                    <img alt="" src="images/furniture_18.jpg"/>
                                                </div>

                                                <script language="javascript" type="text/javascript" src="phpshop/templates/phpshop_8/javascript/fader.js"></script>

                                                <div style="height:255px; width:557px; margin-top: 16px; overflow:hidden;">
                                                    <div id="s5_wrapper_if" style="position:absolute;z-index:1;overflow:hidden;height:255px; width:557px;">
                                                        <div style="background-image: url('images/furniture_16.jpg');width: 557px; height:255px;" id="blenddiv">
                                                            <img src="images/furniture_16.jpg" style="border: 0 none;  opacity: 0;" id="blendimage" alt="" class="reflect"></img>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script type="text/javascript">
                                                    function picture1_next(id) {
                                                        if (thumbchangeon_php == 0) {
                                                            picture2('picture2');
                                                        }
                                                    }
                                                    function picture2_next(id) {
                                                        if (thumbchangeon_php == 0) {
                                                            picture3('picture3');
                                                        }
                                                    }
                                                    function picture3_next(id) {
                                                        if (thumbchangeon_php == 0) {
                                                            picture1('picture1');
                                                        }
                                                    }
                                                    picture1('picture1');
                                                </script>


                                                <h1 class="HTitle1">@mainContentTitle@</h1>
                                                @mainContent@
                                                <h1 class="HTitle1">Спецпредложения</h1>
                                                @specMain@
                                                <h1 class="HTitle3">Новости</h1>
                                                @miniNews@
                                                @rightMenu@
                                                @banersDisp@
                                                <div class="spaceH20"></div></td>
                                            <td><div class="space3"></div></td>
                                            <td align="left" valign="top"><div class="space4">
                                                    <h1 class="HTitle2">Навигация</h1>
                                                    <div class="divNavigationA"><a href="/price/">Прайс-лист</a></div>
                                                    <div class="divNavigationA"><a href="/news/">Новости</a></div>
                                                    <div style="clear:both; float:left">@pageCatal@</div>
                                                    <div class="divNavigationA"><a href="/links/">Полезные ссылки</a></div>
                                                    <div class="divNavigationA"><a href="/gbook/">Отзывы</a></div>
                                                    <div class="divNavigationA"><a href="/map/">Карта сайта</a></div>
                                                    <div class="divNavigationA"><a href="/forma/">Форма связи</a></div>
                                                    <div style="clear:both;"></div>
                                                    @oprosDisp@
                                                    @skinSelect@
                                                    @leftMenu@
                                                    <div class="spaceH20"></div>
                                                    <h1 class="HTitle2-1">@specMainTitle@</h1>
                                                    @specMainIcon@
                                                    @cloud@
                                                    <div class="spaceH30"></div>
                                                </div></td>
                                            <td><div class="space2"></div></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div id="DBotoom-t"><div id="DBotoom-b">
                                        <p class="PBotoom">Copyright &copy; Интернет-магазин «@pageReg@» Тел: @telNum@<span></span>Все права защищены.</p>
                                    </div>
                                    <img src="images/spacer.gif" height="1" width="996"></div></div>
                        </div></td>
                </tr>
            </tbody>
        </table><br><div align="center">@button@</div>
