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
        <LINK rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <LINK rel="icon" href="favicon.ico" type="image/x-icon">
        <LINK href="@pageCss@" type="text/css" rel="stylesheet">
        <SCRIPT language="JavaScript" src="java/phpshop.js"></SCRIPT>
        <SCRIPT language="JavaScript" src="phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <SCRIPT language="JavaScript" type="text/javascript" src="java/tabpane.js"></SCRIPT>
        <SCRIPT language="JavaScript" src="java/swfobject.js"></SCRIPT>
    </HEAD>
    <BODY onload="pressbutt_load('@thisCat@', '@pathTemplate@', 'false', 'false');
            NavActive('@NavActive@');
            LoadPath('@ShopDir@');">
        
        <table width="1004" cellpadding="0" cellspacing="0" align="center">
            <tr>
                <td>
                    <div id="cartwindow" class="message">
            <div class="ico_add_to_card"><b>Внимание...</b><br>Товар добавлен в корзину</div>
        </div>
        <div id="comparewindow" class="message">
            <div class="ico_add_to_compare"><b>Внимание...</b><br>Товар добавлен в сравнение</div>
        </div>
                    <TABLE WIDTH="1004" BORDER="0" CELLPADDING="0" CELLSPACING="0">
                        <TR>
                            <TD COLSPAN="2" id="header_1">
                                <a title="@name@" href="/"><img src="@logo@" alt="@name@" border="0"></a>
                            </TD>
                            <TD COLSPAN="2" id="header_2" valign="top">

                            </TD>
                        </TR>
                        <TR>
                            <TD COLSPAN="4" id="header_3">

                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td><a href="/" class="navigation">Главная</a></td>
                                        <td style="padding-top:1px;padding-left:5px;padding-right:5px" width="30" align="center"><img src="images/menu_spek.gif" alt="" width="5" height="20" border="0" align="absmiddle"></td>

                                        @topMenu@
                                    </tr>
                                </table>
                            </TD>
                        </TR>
                        <TR>
                            <TD id="header_4">
                                <div style="padding-top:5px;padding-left:10px">
                                    <table >
                                        <FORM method="post" name="forma_search" action="/search/" onsubmit="return SearchChek()">
                                            <tr>
                                                <td colspan="2"><b class="zagb">Поиск по каталогу</b></td>
                                            </tr>
                                            <tr>
                                                <td><input name="words" class="search" maxLength=30 onfocus="this.value = ''" value="Я ищу..."></td>
                                                <td><input type="submit" value="Искать" class="but"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <a href="/search/" style="color:white">+ расширенный поиск</a>
                                                </td>
                                            </tr>
                                        </FORM>
                                    </table>
                                </div>
                            </TD>
                            <TD COLSPAN="2" id="header_5">

                                <div style="padding-bottom:5px" class="zagb">Корзина</div>
                                <TABLE cellspacing="0" cellpadding="0">
                                    <TR>
                                        <TD class="white">Товаров :  

                                        </TD>
                                        <td class="white">
                                            <DIV class="style2" id="num" style="DISPLAY: inline; FONT-WEIGHT: bold">    @num@
                                            </DIV> шт. 
                                        </td>


                                    </TR>
                                    <TR>
                                        <TD class="white">Сумма заказа :  

                                        </TD>
                                        <td class="white">
                                            <DIV class="style2" id="sum" style="DISPLAY: inline; FONT-WEIGHT: bold">    @sum@ @productValutaName@
                                            </DIV>
                                        </td>

                                    </TR>
                                    <TR>
                                        <TD class="white">Сравнение :  

                                        </TD>
                                        <td class="white">
                                            <DIV class="style2" id="numcompare" style="DISPLAY: inline; FONT-WEIGHT: bold"> @numcompare@
                                            </DIV> шт. 
                                        </td>

                                    </TR>
                                </TABLE>
                                <table>
                                    <tr>
                                        <td>@valutaDisp@</td>
                                        <td>  <div id="order" style="display:@orderEnabled@; padding-left:20px"><A href="/order/" style="color: white; font-weight: bold;">Оформить заказ</A></div>
                                            <div id="compare" style="padding-left:20px;padding-top:5px;display:@compareEnabled@" class="header_bg_2_up_compare"><a href="/compare/" title="Сравнение товаров"  style="color: white; font-weight: bold;">Сравнить товары</div>

                                        </td>
                                    </tr>
                                </table>


                            </TD>
                            <TD id="header_6">
                                <a id="userPage"></a>
                                <div style="padding-bottom:5px" class="zagb">Пользователям</div>
                                @usersDisp@

                            </TD>
                        </TR>
                        <TR>
                            <TD>
                                <IMG SRC="images/spacer.gif" WIDTH=408 HEIGHT=1 ALT=""></TD>
                            <TD>
                                <IMG SRC="images/spacer.gif" WIDTH=181 HEIGHT=1 ALT=""></TD>
                            <TD>
                                <IMG SRC="images/spacer.gif" WIDTH=94 HEIGHT=1 ALT=""></TD>
                            <TD>
                                <IMG SRC="images/spacer.gif" WIDTH=321 HEIGHT=1 ALT=""></TD>
                        </TR>
                    </TABLE>
                    <table width="1004" BORDER="0" CELLPADDING="0" CELLSPACING="0" style="margin-top:20px">
                        <tr>
                            <td width="2"><img src="images/spacer.gif" alt="" width="10" height="1" border="0"></td>
                            <td width="275"  valign="top" class="left_block"><img src="images/spacer.gif" alt="" width="275" height="1" border="0">@skinSelect@
                                <div id="bg_catalog_1">Каталог продукции</div>
                                @leftCatal@
                                <a href="/news/"><DIV class=catalog_forma style="CURSOR: pointer" onclick="javascript:location.replace('/news/')"><TABLE cellSpacing=0 cellPadding=0 width=275 border=0>
                                            <TBODY>
                                                <TR>
                                                    <TD style="PADDING-LEFT: 62px; FONT-WEIGHT: bold; COLOR: #ffffff; PADDING-TOP: 15px">Новости</TD></TR></TBODY></TABLE></DIV></a>
                                <a href="/gbook/"><DIV class=catalog_forma style="CURSOR: pointer" onclick="javascript:location.replace('/gbook/')"><TABLE cellSpacing=0 cellPadding=0 width=275 border=0>
                                            <TBODY>
                                                <TR>
                                                    <TD style="PADDING-LEFT: 62px; FONT-WEIGHT: bold; COLOR: #ffffff; PADDING-TOP: 15px">Отзывы</TD></TR></TBODY></TABLE></DIV></a>
                                <a href="/price/"><DIV class=catalog_forma style="CURSOR: pointer" onclick="javascript:location.replace('/price/')"><TABLE cellSpacing=0 cellPadding=0 width=275 border=0>
                                            <TBODY>
                                                <TR>
                                                    <TD style="PADDING-LEFT: 62px; FONT-WEIGHT: bold; COLOR: #ffffff; PADDING-TOP: 15px">Прайс-лист</TD></TR></TBODY></TABLE></DIV></a>
                                @pageCatal@

                                @leftMenu@
                                @calendar@ 
                                @oprosDisp@
                                @cloud@
                            </td>
                            <td width="10"><img src="images/spacer.gif" alt="" width="10" height="1" border="0"></td>
                            <td width="100%" valign="top">

                                @DispShop@
                                @banersDisp@
                            </td>
                        </tr>
                        <tr>
                            <td height="30" colspan="3"></td>
                        </tr>
                    </table>
                    <table width="1004" BORDER="0" CELLPADDING="0" CELLSPACING="0">
                        <tr>
                            <td id="bg_footer_2" width="100%">
                                Copyright © @pageReg@.<br>
                                Все права защищены. Тел. @telNum@<br>
                                <img src="images/feed.gif" alt="" width="16" height="16" border="0" align="absmiddle"> <a href="/rss/" title="RSS">RSS</a> | 
                                <img src="images/shop/sitemap.gif" alt="" width="16" height="16" border="0" align="absmiddle">
                                <a href="/map/" title="Карта сайта">Карта сайта</a> 
                            </td>
                            <td id="bg_footer_3" width="174">

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
</table><br><div align="center">@button@</div>
