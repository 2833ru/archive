<div class="prod_cart_list">
<div class="span3">
  <div class="thumbnail"> <a class="highslide" href="/shop/UID_@productUid@.html" title="@productName@"><img src="@productImg@"  onerror="NoFoto(this,'@pathTemplate@')"  alt="@productName@" title="@productName@" ></a>
    <div class=highslide-caption>@productName@</div>
  </div></div>
  <div class="span6">
  <div class="thumbSetting">
    <div class="thumbTitle"> <a href="/shop/UID_@productUid@.html" class="invarseColor" title="@productName@">@productName@</a> <br> </div>
    @ComStart@
    <div class="thumbPrice"> <span><span class="strike-through">@productPriceRub@</span> @productPrice@ @productValutaName@</span>
    
        @rateCid@
       
     
        </div> 
    @ComEnd@
    <div class="thumbDesc">@productDes@</div>
    <div class="thumbButtons"> @ComStartCart@
      <button onclick="javascript:AddToCart(@productUid@);" class="btn btn-primary btn-small" data-title="@productSale@" data-placement="top" data-toggle="tooltip"> <i class="icon-shopping-cart"></i> </button>
      @ComEndCart@
      
      @ComStartNotice@
      <button onclick="document.location.href='/users/notice.html?productId=@productUid@';" class="btn btn-small" data-title="@productNotice@" data-placement="top" data-toggle="tooltip"> <i class="icon-info-sign"></i> </button>
      @ComEndNotice@
      <button onclick="javascript:addToWishList(@productUid@)" class="btn btn-small" data-title="+ Отложить" data-placement="top" data-toggle="tooltip"> <i class="icon-heart"></i> </button>
      <button onclick="javascript:AddToCompare(@productUid@)" class="btn btn-small" data-title="Сравнить @productName@" data-placement="top" data-toggle="tooltip"> <i class="icon-refresh"></i> </button>
	  <a href="#modal" data-role="/shop/UID_@productUid@.html" class="btn btn-small fastView" data-toggle="modal" data-hover="tooltip" data-title='Быстрый просмотр'><i class="icon-eye-open"></i></a>
	  
	  
	  
    </div>
  </div>
  </div>
  </div>