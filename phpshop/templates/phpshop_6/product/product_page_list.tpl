<div class="page_navigation"><div><a href="/">Главная</a> <span class="page_navigation_slesh">/</span> <A href="/shop/CID_@catalogId@.html" title="@catalogCat@">@catalogCat@</A> <span class="page_navigation_slesh">/</span> @catalogCategory@</div></div>	

<div class="plashka_center"><table cellpadding="0" cellspacing="0" border="0" width="100%"><tbody><tr><td><div class="plashka_zag">@catalogCat@ / @catalogCategory@</div></td><td><div align="right"><img src="images/shop/page_excel.gif" alt="" border="0" align="absmiddle" hspace="5"><a href="/price/CAT_SORT_@pcatalogId@.html" title="Прайс-лист каталога @catalogCategory@" class="link">Прайс-лист каталога</a></div></td></tr></tbody></table></div>

<a name="up"></a>

<div>
@catalogContent@
</div>


<div class="filters_line"><form method="post" action="/shop/CID_@productId@.html" name="sort">
	<div id="allspec_sort"><div style="float:left;line-height: 17px;">Цена от:&nbsp;</div>
	<div style="float:left;"><input type="text" size="5" name="priceOT" value="@productRriceOT@"></div>
    <div style="float:left;line-height: 17px;">&nbsp;до:&nbsp;</div>
	<div style="float:left;"><input type="text" size="5" name="priceDO" value="@productRriceDO@"></div>
    <div class="div_ok1"><input type="submit" value="" name="priceSearch" class="ok_price">
    <input type="hidden" value="@productSort@" name="s"></div></div>
<div class="divFboth"><table cellspacing="0" cellpadding="0" border="0" class="filters_table">
   	<tr>
    	<td><table cellspacing="0" cellpadding="0" border="0"><tr>@vendorDisp@</tr></table></td>
    	<td align="left">@vendorSelectDisp@</td>
	</tr>
</table></div>
<div class="break_filters">@vendorDispTitle@</div>
</form></div>

<div class="page_nava"><div>@productPageNav@</div></div>

<div class="IndexSpecMainDiv"><table cellpadding="0" cellspacing="0" border="0">
@productPageDis@
</table></div>
 
<div class="page_nava" style="margin-top:20px;">
	<div>@productPageNav@</div>
</div>
<a name="down"></a>


