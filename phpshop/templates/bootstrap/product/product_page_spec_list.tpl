
<ol class="breadcrumb">
    @breadCrumbs@
</ol>
<div class="page-header">
    <h2>@catalogCategory@</h2>
</div>

<div class="well">
    <div class="row">
        <div class="col-xs-8">
            Вывод товаров:  <a href="?@productVendor@&f=@php echo $_GET['f']; php@&s=@php echo $_GET['s']; php@&gridChange=1" class="btn btn-default btn-sm @gridSetAactive@" data-toggle="tooltip" data-placement="top" title="Товары списком"><span class="glyphicon glyphicon-th-list" ></span></a> <a href="?@productVendor@&f=@php echo $_GET['f']; php@&s=@php echo $_GET['s']; php@&gridChange=2" class="btn btn-default btn-sm @gridSetBactive@"  data-toggle="tooltip" data-placement="top" title="Товары сеткой"><span class="glyphicon glyphicon-th"></span></a>  

        </div>
        <div class="col-xs-4 text-right">
            Сортировка: 
            <a href="?@productVendor@&f=2&s=@php echo $_GET['s']; php@&gridChange=@php echo $_GET['gridChange']; php@" class="btn btn-default btn-sm @fSetBactive@"  data-toggle="tooltip" data-placement="top" title="Наименование по возрастанию"><span class="glyphicon glyphicon-sort-by-alphabet" ></span></a> 
            <a href="?@productVendor@&f=1&s=@php echo $_GET['s']; php@&gridChange=@php echo $_GET['gridChange']; php@" class="btn btn-default btn-sm @fSetAactive@" data-toggle="tooltip" data-placement="top" title="Наименование по убыванию"><span class="glyphicon glyphicon-sort-by-alphabet-alt" ></span></a>
            <a href="?@productVendor@&f=@php echo $_GET['f']; php@&s=2&gridChange=@php echo $_GET['gridChange']; php@" class="btn btn-default btn-sm @sSetBactive@"  data-toggle="tooltip" data-placement="top" title="Цена по возрастанию"><span class="glyphicon glyphicon-sort-by-attributes" ></span></a>
            <a href="?@productVendor@&f=@php echo $_GET['f']; php@&s=1&gridChange=@php echo $_GET['gridChange']; php@" class="btn btn-default btn-sm @sSetAactive@"  data-toggle="tooltip" data-placement="top" title="Цена по убыванию"><span class="glyphicon glyphicon-sort-by-attributes-alt" ></span></a>
        </div>
    </div> 
    <p>
        <form method="post" action="/shop/CID_@productId@@nameLat@.html" name="sort">
            @vendorDisp@ @vendorSelectDisp@
        </form>
    </p>
</div>
<div class="template-product-list">@productPageDis@</div>


@productPageNav@