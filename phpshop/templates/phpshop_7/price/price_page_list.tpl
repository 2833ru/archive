<div class="page_nava"><div>
<a href="/">Главная</a> / Прайс-лист
</div></div>

<table cellpadding="0" cellspacing="0" border="0" class="catalogOptionTable">
	<tbody>
		<tr>
		  <td align="left" valign="bottom"><div class="catalogOption_zag"><div class="plashka_zag">Прайс-лист</div></div></td>
		</tr>
	</tbody>
</table>

<p><form>
@searchPageCategory@	<input type="button" value="Показать" onclick="DoPriceSort();" class="ok">
</form></p>


	


<div class="pod_cart">
<table class="standart">
<tr>
	<td width="150">
	<a href="/price/CAT_ALL.html" >Вывести все позиции</a>
	</td>
	<td width="150">
	<a href="javascript:GetAllForma('@PageCategory@')">Форма с описанием</a>
	</td>
	<td width="120">
	<a href="#" onclick="window.open('phpshop/forms/priceprint/print.html?catId=@PageCategory@')" >Печатная форма</a>
	</td>
</tr>
<tr>
	<td width="100">
	<a href="#" onclick="window.open('/files/priceSave.php?catId=@PageCategory@')">Excel Форма</a>
	</td>
	<td>
	<a href="#" onclick="window.open('/files/priceSave.php?catId=@PageCategory@&gzip=true')">Excel Форма GZIP</a>
	</td>
	<td></td>
</tr>
</table>
</div>

@productPageDis@					
												