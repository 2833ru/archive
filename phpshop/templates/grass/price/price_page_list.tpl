<div class="plashka_center">
				<div class="plashka_zag" >Прайс-лист</div>
				
			</div>

<p><form>
@searchPageCategory@	<input type="button" value="Показать" onclick="DoPriceSort();" class="ok">
</form></p>


	


<div class="pod_cart">
<table class="standart">
<tr>
	<td width="150">
	<img src="images/shop/zoom.gif" alt="" border="0" align="absmiddle" hspace="5">
    <a href="/price/CAT_ALL.html" title="Вывести все позиции">Вывести все позиции</a>
	</td>
	<td width="150">
	<img src="images/shop/layout_content.gif" alt="" border="0" align="absmiddle" hspace="5">
    <a href="javascript:GetAllForma('@PageCategory@')" title="Форма с описанием">Форма с описанием</a>
	</td>
	<td>
    <img border=0 align=absmiddle hspace=3 vspace=3 src="images/shop/action_print.gif">
	<a href="#" onclick="window.open('phpshop/forms/priceprint/print.html?catId=@PageCategory@')" title="Печатная форма">Печатная форма</a>
	</td>
</tr>
<tr>
	<td width="100">
	<img border=0 align=absmiddle hspace=3 vspace=3 src="images/shop/action_save.gif">
	<a href="#" onclick="window.open('/files/priceSave.php?catId=@PageCategory@')" title="Excel Форма">Excel Форма</a>
	</td>
	<td>
	<img border=0 align=absmiddle hspace=3 vspace=3 src="images/shop/package.gif">
	<a href="#" onclick="window.open('/files/priceSave.php?catId=@PageCategory@&gzip=true')" title="Excel Форма GZIP">Excel Форма GZIP</a>
	</td>
	<td>
	<img border=0 align=absmiddle hspace=3 vspace=3 src="images/shop/basket_add.gif">
    <a href="/files/onlineprice/" target="_blank" title="Интерактивная форма">Интерактивная форма</a>
    </td>
</tr>
</table>
</div>

@productPageDis@					
												