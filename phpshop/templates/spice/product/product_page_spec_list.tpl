<!-- Breadcrumb Starts -->
    <ol class="breadcrumb ">
        @breadCrumbs@
    </ol>
<!-- Breadcrumb Ends -->

<!-- Main Heading Starts -->
    <h2 class="main-heading2">
        @catalogCategory@
    </h2>
<!-- Main Heading Ends -->

<!-- Category Intro Content Starts -->
    <div class="row cat-intro">
        <div class="col-md-12">
             @catalogContent@
        </div>
    </div>
<!-- Category Intro Content Ends -->

<!-- Product Filter Starts -->
    <div class="product-filter" id="filter-well">
        <div class="row">
            <div class="col-md-6">
                <div class="display" data-toggle="buttons">
                    <label class="control-label">Вывод товаров:</label>
                    <label class="btn btn-sm glyphicon glyphicon-th-list btn-sort @gridSetAactive@" data-toggle="tooltip" data-placement="top" title="Товары списком">
                        <input type="radio" name="gridChange" value="1">
                    </label>
                    <label class="btn btn-sm glyphicon glyphicon-th btn-sort @gridSetBactive@" data-toggle="tooltip" data-placement="top" title="Товары сеткой">
                        <input type="radio" name="gridChange" value="2">
                    </label>
                </div>
            </div>
            <div class="col-md-6 filter-well-right-block">
                <div class="display">
                    <label class="control-label">Сортировка: </label>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-sm btn-sort glyphicon glyphicon-signal @sSetCactive@" data-toggle="tooltip" data-placement="top" title="По умолчанию">
                            <input type="radio" name="s" value="3">
                        </label>
                        <label class="btn btn-sm btn-sort glyphicon glyphicon-sort-by-alphabet @sSetAactive@" data-toggle="tooltip" data-placement="top" title="Наименование">
                            <input type="radio" name="s" value="1">
                        </label>
                        <label class="btn btn-sm btn-sort glyphicon glyphicon-sort-by-order @sSetBactive@" data-toggle="tooltip" data-placement="top" title="Цена">
                            <input type="radio" name="s" value="2">
                        </label>
                    </div>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-xs btn-sort glyphicon glyphicon-sort-by-attributes @fSetAactive@" data-toggle="tooltip" data-placement="top" title="По возрастанию">
                            <input type="radio" name="f" value="1">
                        </label>
                        <label class="btn btn-xs btn-sort glyphicon glyphicon-sort-by-attributes-alt @fSetBactive@" data-toggle="tooltip" data-placement="top" title="По убыванию">
                            <input type="radio" name="f" value="2">
                        </label>
                    </div>
                </div>
            </div>
        </div> 
        <a name="sort"></a>
        <form method="post" action="/shop/CID_@productId@@nameLat@.html" name="sort" id="sorttable" class="hide">
            <table><tr>@vendorDisp@ <td>&nbsp;</td><td>@vendorSelectDisp@</td></tr></table>
        </form>                      
    </div>
<!-- Product Filter Ends -->
<div class="template-product-list">@productPageDis@</div>


@productPageNav@