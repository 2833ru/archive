<div class="breadcrumb">
    <div> <a href="/">Главная</a> <span>»</span> <a href="/selection/"> Подбор товаров </a></div>
</div>
<h1>Подбор товаров</h1>
<h2>@sortName@</h2>
<div align="left" style="padding:5px 0px"> @sortDes@ </div>
<div align="left" style="padding:5px 0px"> @DispCatNav@ </div>

<div class="product-filter" id="filter-selection-well">
    <div class="display">Вывод товаров:&nbsp;&nbsp; 

        <div class="btn-group pull-right">
            <button class="btn @gridSetAactive@" name="gridChange"  value="1" data-original-title="Товары списком"  rel="tooltip" data-url="?@productVendor@@php if(isset($_GET['f'])) echo '&f='.$_GET['f']; if(isset($_GET['s'])) echo  '&s='.$_GET['s']; php@&gridChange=1"><span class="icon-th-list"></span></button>
            <button class="btn @gridSetBactive@"  name="gridChange"  value="2" data-original-title="Товары сеткой"  rel="tooltip" data-url="?@productVendor@@php if(isset($_GET['f'])) echo '&f='.$_GET['f']; if(isset($_GET['s'])) echo  '&s='.$_GET['s']; php@&gridChange=2"><span class="icon-th"></span></button>
        </div>
    </div>
    <div class="product-compare" style="display:@compare Enabled@; "><a href="/compare/" id="compare-total">Сравнить товары (<span id="numcompare">@numcompare@</span> шт.)</a></div>
    <div class="sort"> Сортировка:&nbsp;&nbsp; 
        <div class="btn-group pull-right">
            <button class="btn @sSetAactive@" name="s" value="1" data-original-title="Наименование"  rel="tooltip" data-url="?@productVendor@&s=1@php if(isset($_GET['f'])) echo  '&f='.$_GET['f']; if(isset($_GET['gridChange'])) echo '&gridChange='.$_GET['gridChange']; php@"><span class="fa fa-sort-alpha-asc"></span></button>
            <button class="btn @sSetBactive@" name="s" value="2" data-original-title="Цена"  rel="tooltip" data-url="?@productVendor@&s=2@php if(isset($_GET['f'])) echo  '&f='.$_GET['f']; if(isset($_GET['gridChange'])) echo '&gridChange='.$_GET['gridChange']; php@"><span class="fa fa-sort-numeric-asc"></span></button>

            <button class="btn @fSetAactive@" name="f" value="1" data-original-title="По возрастанию"  rel="tooltip" data-url="?@productVendor@&f=1@php if(isset($_GET['s'])) echo  '&s='.$_GET['s']; if(isset($_GET['gridChange'])) echo '&gridChange='.$_GET['gridChange']; php@"><span class="fa fa-sort-amount-asc"></span></button>
            <button class="btn @fSetBactive@"  name="f" value="2" data-original-title="По убыванию"  rel="tooltip" data-url="?@productVendor@&f=2@php if(isset($_GET['s'])) echo  '&s='.$_GET['s']; if(isset($_GET['gridChange'])) echo '&gridChange='.$_GET['gridChange']; php@"><span class="fa fa-sort-amount-desc"></span></button>
        </div>
    </div>
    <div style="clear:both"></div>
</div>



<div class="product-grid">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        @productPageDis@ 
    </table>
</div>

<div class="navi">@productPageNav@</div>
