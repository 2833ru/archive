<div class="plashka_center">Прайс-лист</div>

<p><form>
@searchPageCategory@	<input type="button" value="Показать" onclick="DoPriceSort();" class="ok">
</form></p>

<div class="pod_cart">
<table class="standart" cellpadding="0" cellspacing="0">
<tr>
	<td width="150">
	<img src="images/shop/zoom.gif" alt="" border="0" align="absmiddle" hspace="5"><a href="/price/CAT_ALL.html" >Вывести все позиции</a>
	</td>
	<td width="150">
	<img src="images/shop/layout_content.gif" alt="" border="0" align="absmiddle" hspace="5"><a href="javascript:GetAllForma('@PageCategory@')">Форма с описанием</a>
	</td>
	<td width="120">
	<a href="#" onclick="window.open('phpshop/forms/priceprint/print.html?catId=@PageCategory@')" ><img border=0 align=absmiddle hspace=3 vspace=3 src="images/shop/action_print.gif">Печатная форма</a>
	</td>
</tr>
<tr>
	<td width="100">
	<img border=0 align=absmiddle hspace=3 vspace=3 src="images/shop/action_save.gif">
	<a href="#" onclick="window.open('/files/priceSave.php?catId=@PageCategory@')">Excel Форма</a>
	</td>
	<td >
	<img border=0 align=absmiddle hspace=3 vspace=3 src="images/shop/package.gif">
	<a href="#" onclick="window.open('/files/priceSave.php?catId=@PageCategory@&gzip=true')">Excel Форма GZIP</a>
	</td>
	<td></td>
</tr>
</table>
</div>

@productPageDis@