<div class="siteMiddleDivContent">
<div id="bg_catalog_1">@catalogCategory@</div>
<div id="bglist"></div>

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	
	<td>

<table id="allspec" width="100%">
<tr>
	<td style=padding-left:10;padding-top:10px>
	Сортировка по: <a href="./spec_@productPageThis@.html?v=@productVendor@&f=@productSortNext@&s=@productSort@" title="Изменить направление"><img src="images/shop/@productSortImg@.gif" alt="Изменить направление" hspace="3" border="0" align="absmiddle"></a><a href="./spec_@productPageThis@.html?v=@productVendor@&f=@productSortTo@&s=1" class="@productSortA@">алфавиту</a> - <a href="./spec_@productPageThis@.html?v=@productVendor@&f=@productSortTo@&s=2"  class="@productSortB@">цене</a> - <a href="./spec_@productPageThis@.html?v=@productVendor@&f=@productSortTo@&s=3"  class="@productSortC@">популярности</a>
	</td>
</tr>
<form method="post" action="./">
<tr>
  <td style=padding-left:10;padding-top:10px>
	Цена от: <input type="text" size="5" name="priceOT" value="@productRriceOT@"> до: <input type="text" size="5" name="priceDO" value="@productRriceDO@">
	<input type="submit" value="Показать" name="priceSearch">
	<input type="hidden" value="@productSort@" name="s">
	</td>
</tr>
<tr>
 <td style=padding-left:10>
 <table cellspacing="3" cellpadding="3">
  <tr>
   @vendorDisp@
  </tr>
 </table>

 </td>
</tr>
<tr>
	<td class=style5 style=padding-left:10>
	@productPageNav@
	</td>
</tr>
</form>

</table>
</td>
	
</tr>
</table>
</div>
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
 <td>
   <table cellpadding="0" cellspacing="0" border="0" width="100%">
   <!-- main_product_forma -->
  @productPageDis@
 <!-- main_product_forma-->
   </table>
 </td>
</tr>
<tr>
<td width="100%" style=padding-left:10 class=style8 colspan="2">
	<div style="padding-top:15;padding-bottom:15" class=black>
	@productPageNav@
	</div>
	</td>
</tr>
</table>