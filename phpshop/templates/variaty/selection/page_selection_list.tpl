<div class="row">
    <div class="breadcrumb">
        <div> <a href="/">Главная</a> <span>»</span> <a href="/selection/"> Подбор товаров </a></div>
    </div>
    <h1>Подбор товаров</h1>
    <h2>@sortName@</h2>
    <div align="left" style="padding:5px 0px"> @sortDes@ </div>
    <div align="left" style="padding:5px 0px"> @DispCatNav@ </div>


    <NOINDEX>
        <div class="productFilter productFilterSelection clearfix" id="filter-selection-well">
            <div class="sortBy inline pull-left">
                <div class="sort">
                    <div class="btn-group pull-right">
                        <button class="btn @sSetAactive@" name="s" value="1" data-original-title="Наименование"  rel="tooltip" data-url="?@productVendor@&s=1@php if(isset($_GET['f'])) echo  '&f='.$_GET['f']; if(isset($_GET['gridChange'])) echo '&gridChange='.$_GET['gridChange']; php@"><span class="fa fa-sort-alpha-asc"></span></button>
                        <button class="btn @sSetBactive@" name="s" value="2" data-original-title="Цена"  rel="tooltip" data-url="?@productVendor@&s=2@php if(isset($_GET['f'])) echo  '&f='.$_GET['f']; if(isset($_GET['gridChange'])) echo '&gridChange='.$_GET['gridChange']; php@"><span class="fa fa-sort-numeric-asc"></span></button>

                        <button class="btn @fSetAactive@" name="f" value="1" data-original-title="По возрастанию"  rel="tooltip" data-url="?@productVendor@&f=1@php if(isset($_GET['s'])) echo  '&s='.$_GET['s']; if(isset($_GET['gridChange'])) echo '&gridChange='.$_GET['gridChange']; php@"><span class="fa fa-sort-amount-asc"></span></button>
                        <button class="btn @fSetBactive@"  name="f" value="2" data-original-title="По убыванию"  rel="tooltip" data-url="?@productVendor@&f=2@php if(isset($_GET['s'])) echo  '&s='.$_GET['s']; if(isset($_GET['gridChange'])) echo '&gridChange='.$_GET['gridChange']; php@"><span class="fa fa-sort-amount-desc"></span></button>
                    </div>
                </div>
            </div>
            <!--end sortBy-->
            <div class="displaytBy inline pull-right"> 
                <div class="btn-group">
                    <a href="?@productVendor@&f=@php echo $_GET['f']; php@&s=@php echo $_GET['s']; php@&gridChange=2" title="сетка" class="btn @gridChange2@"><i class="icon-th"></i></a> 
                    <a href="?@productVendor@&f=@php echo $_GET['f']; php@&s=@php echo $_GET['s']; php@&gridChange=1" title="Список" class="btn @gridChange@"><i class="icon-list"></i></a>
                </div>
            </div>
            <!--end displaytBy-->
        </div>
    </NOINDEX>
    <br>


    <div style="overflow:hidden; margin-top:20px">
        <ul class="hProductItems clearfix">
            @productPageDis@
        </ul>
    </div>

    <div class="navi">@productPageNav@</div>
</div>