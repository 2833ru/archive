<div class="breadcrumb">
    <div> <a href="/">Главная</a> <span>»</span> <a href="/selection/"> Подбор товаров </a></div>
</div>
<h1>Подбор товаров</h1>
<h2>@sortName@</h2>
<div align="left" style="padding:5px 0px"> @sortDes@ </div>
<div align="left" style="padding:5px 0px"> @DispCatNav@ </div>


<NOINDEX>
    <form method="post" action="/shop/CID_@productId@.html" name="sort">
        <div class="product-filter">
            <div class="display">Вывод товаров:&nbsp;&nbsp;
                <a href="?@productVendor@&f=@php echo $_GET['f']; php@&s=@php echo $_GET['s']; php@&gridChange=1"><img src="images/icon_list.png" alt="Список" title="Список" /></a>&nbsp;
                <a href="?@productVendor@&f=@php echo $_GET['f']; php@&s=@php echo $_GET['s']; php@&gridChange=2"><img src="images/icon_grid.png" alt="Сетка" title="Сетка" /></a>    
            </div>
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
        </div>
    </form>
</NOINDEX>


<div class="product-grid">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        @productPageDis@ 
    </table>
</div>

<div class="navi">@productPageNav@</div>
