<h4 style="font-size: 15px;color: #000000">Подбор по параметрам</h4>

<div class="page_nava">
  <a href="/">Главная</a> / Подбор по параметрам
</div>

<div id="allspec" style="margin-top: 5px">
<form method="post" action="/shop/CID_@productId@.html" name="sort">
   <div>
   Сортировка по: <a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=@productSortNext@&s=@productSort@" title="Изменить направление"><img src="images/shop/@productSortImg@.gif" alt="Изменить направление" width="15" height="16" border="0" align="absmiddle"></a><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=@productSortTo@&s=1" class="@productSortA@">алфавиту</a> - <a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=@productSortTo@&s=2"  class="@productSortB@">цене</a> - <a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=@productSortTo@&s=3"  class="@productSortC@">популярности</a>
   </div>
   <div style="width: 100%">
     <div style="float:left;line-height: 17px;">Цена от:&nbsp;</div>
	 <div style="float:left;"><input type="text" size="5" name="priceOT" value="@productRriceOT@"></div>
     <div style="float:left;line-height: 17px;">&nbsp;до:&nbsp;</div>
	 <div style="float:left;"><input type="text" size="5" name="priceDO" value="@productRriceDO@"></div>
     <div>
	 <input type="submit" value="Применить" name="priceSearch" class="ok">
	 <input type="hidden" value="@productSort@" name="s">
	 </div>
   </div>


   </form>
</div>

<div style="padding:7px">@productPageNav@</div>

<!-- Вывод товаров -->
<table cellpadding="0" cellspacing="0" border="0" width="100%">
@productPageDis@
</table>
<!-- Вывод товаров -->

<div style="padding:7px">@productPageNav@</div>
