<h1 class="HTitle4">Прайс-лист</h1>
<div class="page_nava">
  <div ><a href="/">Главная</a> / <span>Прайс-лист</span></div>
</div>
<p>
<form>
  @searchPageCategory@
  <input type="button" value="Показать" onclick="DoPriceSort();" class="ok">
</form>
</p>
<div class="pod_cart">
  <table class="standart">
    <tr>
      <td width="30%"> <a href="/price/CAT_ALL.html" title="Вывести все позиции">Вывести все позиции</a> </td>
      <td width="30%"> <a href="javascript:GetAllForma('@PageCategory@')" title="Форма с описанием">Форма с описанием</a> </td>
      <td width="30%"> <a href="#" onclick="window.open('phpshop/forms/priceprint/print.html?catId=@PageCategory@')" title="Печатная форма">Печатная форма</a> </td>
    </tr>
    <tr>
      <td> <a href="#" onclick="window.open('/files/priceSave.php?catId=@PageCategory@')" title="Excel Форма">Excel Форма</a> </td>
      <td> <a href="#" onclick="window.open('/files/priceSave.php?catId=@PageCategory@&gzip=true')" title="Excel Форма GZIP">Excel Форма GZIP</a> </td>
      <td> <a href="/files/onlineprice/" target="_blank" title="Интерактивная форма">Интерактивная форма</a> </td>
    </tr>
  </table>
</div>
<div class="productPageDisTable">@productPageDis@</div>
