<h1 class="HTitle4">@catalogCategory@</h1>
<div class="page_nava">
  <div ><a href="/">Главная</a> / <span>@catalogCategory@</span></div>
</div>

<form method="post" action="/shop/CID_@productId@.html" name="sort">
<div id="allspec_page_list">
    <div style="width: 100%">
     <div style="float:left;line-height: 17px;">Цена от:&nbsp;</div>
	 <div style="float:left;"><input type="text" size="5" name="priceOT" value="@productRriceOT@" class="input_page_list"></div>
     <div style="float:left;line-height: 17px;">&nbsp;до:&nbsp;</div>
	 <div style="float:left;"><input type="text" size="5" name="priceDO" value="@productRriceDO@" class="input_page_list"></div>
     <div>
	 <input type="submit" value="Показать" name="priceSearch" class="ok">
	 <input type="hidden" value="@productSort@" name="s">
	 </div>
   </div>
</div>
<table cellpadding="0" cellspacing="0" border="0" class="catalogOptionTable1">
	<tbody>
		<tr>
		  <td align="left" valign="middle" class="catOptionTD"><div class="page_nav_top">@productPageNav@</div></td>
		  <td align="right" valign="middle"><div class="divSortPageList">Цена: &nbsp; <a href="./spec_@productPageThis@.html?v=@productVendor@&f=2&amp;s=2"><img src="images/furniture_24.gif" align="absmiddle" border="0"></a> <span class="spanSortPageList"><a href="./spec_@productPageThis@.html?v=@productVendor@&f=2&amp;s=2">возр</a> <a href="./spec_@productPageThis@.html?v=@productVendor@&f=1&amp;s=2"><img src="images/furniture_25.gif" align="absmiddle" border="0"></a> <a href="./spec_@productPageThis@.html?v=@productVendor@&f=1&amp;s=2">убыв</a></span></div></td>
		</tr>
	</tbody>
</table>
</form>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
  @productPageDis@
</table>

<form method="post" action="/shop/CID_@productId@.html" name="sort">
<table cellpadding="0" cellspacing="0" border="0" class="catalogOptionTable2">
	<tbody>
		<tr>
		  <td align="left" valign="middle" class="catOptionTD"><div class="page_nav_top">@productPageNav@</div></td>
          <td align="right" valign="middle"><div class="divSortPageList">Цена: &nbsp; <a href="./spec_@productPageThis@.html?v=@productVendor@&f=2&amp;s=2"><img src="images/furniture_24.gif" align="absmiddle" border="0"></a> <span class="spanSortPageList"><a href="./spec_@productPageThis@.html?v=@productVendor@&f=2&amp;s=2">возр</a> <a href="./spec_@productPageThis@.html?v=@productVendor@&f=1&amp;s=2"><img src="images/furniture_25.gif" align="absmiddle" border="0"></a> <a href="./spec_@productPageThis@.html?v=@productVendor@&f=1&amp;s=2">убыв</a></span></div></td>
		</tr>
	</tbody>
</table>
</form>