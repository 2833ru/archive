<div class="breadcrumb">
  <div> @breadCrumbs@ </div>
</div>
<div class="header_rel" style="padding-right:0px">
  <h1>@catalogCategory@</h1>
  <a href="/price/CAT_SORT_@pcatalogId@.html" title="Прайс-лист каталога @catalogCategory@" class="abs_link">Прайс-лист каталога</a> </div>
<div class="category-info" id="banfx"> @catalogContent@ </div>
<NOINDEX>
  <form method="post" action="/shop/CID_@productId@@nameLat@.html" name="sort">
    <div class="product-filter">
      <div class="display">Вывод товаров:&nbsp;&nbsp; <a href="/shop/CID_@productId@@nameLat@.html?@productVendor@&f=@php echo $_GET['f']; php@&s=@php echo $_GET['s']; php@&gridChange=1"><img src="images/icon_list.png" alt="Список" title="Список" /></a>&nbsp; <a href="/shop/CID_@productId@@nameLat@.html?@productVendor@&f=@php echo $_GET['f']; php@&s=@php echo $_GET['s']; php@&gridChange=2"><img src="images/icon_grid.png" alt="Сетка" title="Сетка" /></a> </div>
      <div class="product-compare" style="display:@compare Enabled@; "><a href="/compare/" id="compare-total">Сравнить товары (<span id="numcompare">@numcompare@</span> шт.)</a></div>
      <div class="sort"> Сортировать по
        <select onchange="location = this.value;">
          <option value="?">умолчанию</option>
          <option value="?@productVendor@&f=1&s=1">наименованию (возр)</option>
          <option value="?@productVendor@&f=2&s=1">наименованию (убыв)</option>
          <option value="?@productVendor@&f=1&s=2">цене (возр)</option>
          <option value="?@productVendor@&f=2&s=2">цене (убыв)</option>
        </select>
      </div>
      <div style="clear:both"></div>
      <table cellpadding="0" cellspacing="0" border="0" class="vendorDisp" >
        <tr>
          <td><table border="0" cellspacing="0" cellpadding="0">
              <tr> @vendorDisp@ </tr>
            </table></td>
          
        </tr>
		<tr><td >@vendorSelectDisp@</td></tr>
      </table>
    </div>
  </form>
</NOINDEX>
<div class="product-grid">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    @productPageDis@
  </table>
</div>
<div class="navi">@productPageNav@</div>
