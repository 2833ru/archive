 <div class="plashka_center">
				<div class="plashka_zag">@catalogCategory@</div>
			</div>

<a name="up"></a>
<div class="page_nava">
  <div ><a href="/">Главная</a> /  @catalogCategory@</div>
</div>

<div id="allspec" style="margin-top: 5px">
<form method="post" action="/shop/CID_@productId@.html" name="sort">
   <div>
   Сортировка по: <a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=@productSortNext@&s=@productSort@" title="Изменить направление"><img src="images/shop/@productSortImg@.gif" alt="Изменить направление" hspace="2" border="0" align="absmiddle"></a><a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=@productSortTo@&s=1" class="@productSortA@">алфавиту</a> - <a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=@productSortTo@&s=2"  class="@productSortB@">цене</a> - <a href="./CID_@productId@_@productPageThis@.html?v=@productVendor@&f=@productSortTo@&s=3"  class="@productSortC@">популярности</a>
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
<div class="page_nava" style="margin-top:3px;">
<div style="float:right;"><a href="#down" style="color:AC8694"><img src="images/shop/2.gif" alt="" hspace="2" border="0" align="absmiddle">Вниз</a></div>
	<div>@productPageNav@</div>
	
</div>




<table cellpadding="0" cellspacing="0" border="0">
@productPageDis@
</table>
 <a name="down"></a>
<div class="page_nava" style="margin-top:20px;">
<div style="float:right;"><a href="#up" style="color:AC8694"><img src="images/shop/1.gif" alt="" hspace="2" border="0" align="absmiddle">Наверх</a></div>
	<div>@productPageNav@</div>
	
</div>

