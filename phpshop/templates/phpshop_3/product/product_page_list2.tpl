<table width="100%" style="margin-top:5px;" cellpadding="0" cellspacing="0">
<tr>
	
	<td width="100%"  id=allspec >

<table cellpadding="0" width="100%" cellspacing="0" >
<tr>
	<td style="padding:5px" class="black">
	Сортировка по: <a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=@productSortNext@&s=@productSort@" title="Изменить направление"><img src="images/shop/@productSortImg@.gif" alt="Изменить направление" width="15" height="16" border="0" align="absmiddle"></a><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=@productSortTo@&s=1" class="@productSortA@">алфавиту</a> - <a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=@productSortTo@&s=2"  class="@productSortB@">цене</a> - <a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=@productSortTo@&s=3"  class="@productSortC@">популярности</a>
	</td>
</tr>
<form method="post" action="./CID_@productId@.html" name="sort">
<tr>
  <td style=padding-left:10;padding-top:10px>
	Цена от: <input type="text" size="5" name="priceOT" value="@productRriceOT@"> до: <input type="text" size="5" name="priceDO" value="@productRriceDO@">
	<input type="submit" value="Показать" name="priceSearch">
	<input type="hidden" value="@productSort@" name="s">
	</td>
</tr>
<tr>
 <td>
 @vendorDispTitle@
 <table cellspacing="0" cellpadding="0">
  <tr>
   @vendorDisp@
   <td> @vendorSelectDisp@</td>
  </tr>
 </table>

 </td>
</tr>
<tr>
	<td style="padding-left:10;padding-top:5px">
	@productPageNav@
	</td>
</tr>
</form>

</table>
</td>
	
</tr>
</table>
<div align="right" style="padding:10px">
<img src="images/shop/page_excel.gif" alt="" border="0" align="absmiddle" hspace="5"><a href="/price/CAT_SORT_@pcatalogId@.html" title="Прайс-лист каталога @catalogCategory@">Прайс-лист каталога</a>
</div>
<table width="100%" style="margin-bottom:15px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="sp_top"><img src="images/spacer.gif" alt="" width="1" height="10"></td>
  </tr>
  <tr>
    <td class="sp_mid" valign="top">
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
<td width="100%" style=padding-left:10;color:#fff class=black colspan="2">
	<div style="padding-top:15;padding-bottom:15" >
	@productPageNav@
	</div>
	</td>
</tr>
</table>


</div>
</td>
  </tr>
  <tr>
    <td class="sp_bot"><img src="images/spacer.gif" alt="" width="1" height="10"></td>
  </tr>
</table>

