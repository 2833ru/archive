<!-- Breadcrumb Starts -->
<ol class="breadcrumb hidden-xs">
    <li><a href="/" >Главная</a></li>
    <li class="active">Ваша корзина</li>
</ol>
<!-- Breadcrumb Ends -->

<!-- Main Heading Starts -->
<div class="page-header">
    <h2>Ваша корзина</h2>
</div>
<!-- Main Heading Ends -->

<!-- Top button Order Page Start -->
<div class="row top-button-order-row">
	<div class="col-md-12">
		<div class="pull-right">
			<a href="?cart=clean" class="btn btn-main"><span class="glyphicon glyphicon-remove"></span> Очистить корзину</a> 
	    	<a href="phpshop/forms/cart/index.html" target="_blank" class="btn btn-main hidden-xs"><span class="glyphicon glyphicon-print"></span> Печатная форма корзины</a>
		</div>
	</div>
</div>
<!-- Top button Order Page End -->

<!-- Shopping Cart Table Starts -->
<div class="table-responsive shopping-cart-table img_fix">
    @orderContentCart@
</div>
<!-- Shopping Cart Table Ends -->

<!-- Shipping Section Starts -->
<section class="registration-area">
	@orderContent@
</section>
<!-- Shipping Section Ends -->