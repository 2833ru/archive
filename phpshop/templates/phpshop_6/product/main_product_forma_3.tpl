<table cellpadding="10" cellspacing="0" border="0"><tr><td><div class="tovarThree_img"><table cellpadding="0" cellspacing="0" border="0" width="100%" height="120">
	<tbody>
    	<tr>
        	<td align="left" valign="bottom"><a class="highslide" onclick="return hs.expand(this)" href="@productImgBigFoto@" target="_blank" getParams="null" title="@productName@"><img src="@productImg@" alt="@productName@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')" title="@productName@" border="0"></a><div class=highslide-caption>@productName@</div></td>
        </tr>
    </tbody>
</table></div><div class="tovarThree_name"><a href="/shop/UID_@productUid@_@productNameLat@.html" title="@productName@">@productName@</a></div><div class="tovarThree_content">@productDes@</div><table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tbody>
    	@ComStart@<tr>
        	<td align="left" valign="top" style="padding-bottom:4px"><div class="tovarThree_price5">@productPriceRub@</div><div class="tovarThree_price4">@productPrice@ @productValutaName@</div></td>
        </tr>@ComEnd@
        <tr>
        	<td colspan="2" style="padding-top:4px"><div class="tovarThree_price6"><a href="javascript:AddToCompare(@productUid@)" title="Сравнить @productName@">сравнить</a><img src="images/sravnenie_6.gif"></div><!-- Блок купить -->@ComStartCart@<a href="javascript:AddToCart(@productUid@)" title="Купить @productName@" class="order_link_1"><div class="tovarThree_order_1">&nbsp;</div><div class="tovarThree_order_2"><div class="tovarThree_order_4">@productSale@</div></div><div class="tovarThree_order_3">&nbsp;</div></a>@ComEndCart@<!-- Блок купить --><!-- Блок уведомить -->@ComStartNotice@<a href="/users/notice.html?productId=@productUid@" title="@productNotice@" class="order_link_1"><div class="tovarThree_order_1">&nbsp;</div><div class="tovarThree_order_2"><div class="tovarThree_order_4">@productNotice@</div></div><div class="tovarThree_order_3uved">&nbsp;</div></a>@ComEndNotice@<!-- Блок уведомить --></td>
        </tr>
    </tbody>
</table></div></td></tr></table>