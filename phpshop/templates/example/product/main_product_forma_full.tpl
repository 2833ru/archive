<div align="center">
  <h2>@productName@</h2>
</div>

<div align="center">
  <strong>@productPrice@</strong> @productValutaName@
</div>

<div align="center">
  @productPriceRub@
</div>

<div style="height:20px"></div>

<!-- Вывод фото-галереи -->
<div id="allspec">
  <div id="fotoload" align="center">
	@productFotoList@
  </div>
</div>
<!-- Вывод фото-галереи -->

@ComStart@
<div align="center">@optionsDisp@</div>
<div align="center">@productSklad@</div>

<table width="100%">
  <tr>
	<td width="23%" align="center">
	
	<!-- Блок уведомить -->
	@ComStartNotice@
    <img src="images/icon_email.gif" alt="" border="0" align="absmiddle" hspace="5">
	<a href="/users/notice.html?productId=@productId@" title="@productNotice@">@productNotice@</a>
	@ComEndNotice@
	<!-- Блок уведомить -->
	
	<!-- Блок корзина -->
	@ComStartCart@
    <img src="images/shop/basket_put.gif" alt="" border="0" align="absmiddle" hspace="5">
	<a href="javascript:AddToCart(@productId@)" title="@productSale@">@productSale@</a>
	@ComEndCart@
	<!-- Блок корзина -->
	
	</td>
	<td width="23%" align="center">
	<!-- Блок сравнить -->
	<img src="images/shop/application_view_tile.gif" alt="Сравнить" border="0" align="absmiddle" hspace="5">
	<a href="javascript:AddToCompare(@productUid@)" title="Сравнить @productName@">Сравнить</a>
	<!-- Блок сравнить -->
	</td>
	<td width="30%" align="center">
	<!-- Блок пожаловаться на цену -->
	@ComStartCart@
	<img src="images/shop/comment.gif" alt="" border="0" align="absmiddle" hspace="5">
	<a href="/pricemail/UID_@productId@.html" title="Пожаловаться на цену">Пожаловаться на цену</a>
    @ComEndCart@
	<!-- Блок пожаловаться на цену -->
	</td>
	<td width="23%" align="center">
    <img src="images/shop/icon-setup2.gif" alt="" border="0" align="absmiddle" hspace="5">
	<a href="javascript:history.back(1)" title="@productBack@">@productBack@</a>
    </td>
  </tr>
</table>
@ComEnd@

<!-- Вывод подтипов товара [product/product_odnotip_product_parent.tpl] -->
@productParentList@
<!-- Вывод подтипов товара -->

</div>

<div style="margin-top: 20px">

<h4 style="font-size: 15px;color: #000000">Описание</h4>

<!-- Вывод описания -->
@productDes@
<!-- Вывод описания -->

<h4 style="font-size: 15px;color: #000000">Файлы</h4>

<!-- Вывод файлов -->
@productFiles@
<!-- Вывод файлов -->

<h4 style="font-size: 15px;color: #000000">Характеристики</h4>

<!-- Вывод характеристик -->
@vendorDisp@
<!-- Вывод характеристик -->

<h4 style="font-size: 15px;color: #000000">Рейтинг</h4>

<!-- Вывод рейтинга -->
@ratingfull@
<!-- Вывод рейтинга -->

<h4 style="font-size: 15px;color: #000000">Отзывы</h4>

<!-- Вывод отзывов -->
<div id="bg_catalog_1" style="margin-top:10px">Комментарии пользователей</div>
<TEXTAREA id="message" style="WIDTH: 340px" rows="5" onkeyup="return countSymb();"></TEXTAREA>
<DIV style="FONT-SIZE: 10px; MARGIN-BOTTOM: 5px">Максимальное количество символов: <SPAN id="count" style="WIDTH: 30px; COLOR: green; TEXT-ALIGN: center">0</SPAN>/&nbsp;&nbsp;&nbsp;500 </DIV>
<div style="padding:5px" id="commentButtonAdd">
<input type="button"  value="Добавить комментарий" onclick="commentList('@productUid@','add',1)" >
</div>
<div  id="commentButtonEdit" style="padding:5px;visibility:hidden;display:none">
<input type="button"  value="Добавить комментарий" onclick="commentList('@productUid@','add',1)" >
<input type="button"  value="Править комментарий" onclick="commentList('@productUid@','edit_add','1')" >
<input type="button"  value="Удалить" onclick="commentList('@productUid@','dell','1')" >
<input type="hidden" id="commentEditId">
</div>
<div id="commentList" style="padding-top: 10px">
</div>
<script>
setTimeout("commentList('@productUid@','list')",500);
</script>
<!-- Вывод отзывов -->

<h4 style="font-size: 15px;color: #000000">Статьи</h4>

<!-- Вывод статей -->
@pagetemaDisp@
<!-- Вывод статей -->

</div>