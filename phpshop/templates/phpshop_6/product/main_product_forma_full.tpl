<table cellpadding="0" cellspacing="0" border="0" width="100%" height="300"><tbody><tr>
            <td align="center" valign="top">
                <div id="fotoload">@productFotoList@</div></td>
            <td width="100%" align="left" valign="top"><div class="cartFull">@productPrice@ @productValutaName@</div><span class="cartFullOld">@productPriceRub@</span><div class="cartFullArt">Артикул № @productArt@</div><div class="cartFullLine"></div>@ComStart@<table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td width="150"><!-- Блок купить -->@ComStartCart@<a href="javascript:AddToCart(@productId@)" title="@productSale@" class="order_link_1"><div class="tovarThree_order_1">&nbsp;</div><div class="tovarThree_order_2"><div class="tovarThree_order_4">@productSale@</div></div><div class="tovarFull_order_3">&nbsp;</div></a>@ComEndCart@<!-- Блок купить --><!-- Блок уведомить -->@ComStartNotice@<a href="/users/notice.html?productId=@productId@" title="@productNotice@" class="order_link_1"><div class="tovarThree_order_1">&nbsp;</div><div class="tovarThree_order_2"><div class="tovarThree_order_4">@productNotice@</div></div><div class="tovarThree_order_3uved">&nbsp;</div></a>@ComEndNotice@<!-- Блок уведомить --></td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="padding-top:4px"><div class="tovarThree_price6" style="float:left"><a href="javascript:AddToCompare(@productUid@)" title="Сравнить @productName@">сравнить</a></div><div class="tovarThree_price7" style="float:left; padding-top:4px"><img src="images/sravnenie_6.gif"></div></td>
                    </tr>
                </table><div class="cartFullSklad">@productSklad@<br>@oneclick@</div><div class="cartFullFilter">@optionsDisp@</div>@ComEnd@@productParentList@
                <SCRIPT type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/links/links.js"></SCRIPT>
                <div class="full-links"><SCRIPT type="text/javascript">share42("","","@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@")</SCRIPT></div>
               <br>@promotionInfo@
                </td>
        </tr></tbody></table>

<div class="tabPaneFull" id="tabPaneFull">
    <div class="tabPaneFull_h_on" id="tabPaneFull_h1"><div class="tabPaneFull_h1"><div class="tabPaneFull_h2"><a href="javascript:tabPaneFull('tabPaneFull_h1');">Описание</a></div></div></div>
    <div class="tabPaneFull_h_off" id="tabPaneFull_h2"><div class="tabPaneFull_h1"><div class="tabPaneFull_h2"><a href="javascript:tabPaneFull('tabPaneFull_h2');">Характеристики</a></div></div></div>
    <div class="tabPaneFull_h_off" id="tabPaneFull_h3"><div class="tabPaneFull_h1"><div class="tabPaneFull_h2"><a href="javascript:tabPaneFull('tabPaneFull_h3');">Статьи</a></div></div></div>
</div>
<div class="tabPaneFull2"></div>
<div class="tabPaneFull_o_on" id="tabPaneFull_o1">@productDes@</div>
<div class="tabPaneFull_o_off" id="tabPaneFull_o2">@vendorDisp@</div>
<div class="tabPaneFull_o_off" id="tabPaneFull_o3">@pagetemaDisp@</div>
