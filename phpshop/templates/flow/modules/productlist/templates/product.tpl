<!-- Шаблон имя_шаблона/modules/productlist/templates/product.tpl для метки @productlist_list@  -->
     
        <div class="js-slide">
          <!-- Product -->
          <div class="card card-bordered shadow-none text-center h-100">
            <div class="position-relative">
              <img class="card-img-top" src="@productImg@" alt="@productName@">

            <div class="position-absolute top-0 left-0 pt-3 pl-3">
            @hitIcon@
            @specIcon@
            @newtipIcon@
            </div>
            <div class="position-absolute bottom-0 left-0 pl-1 pb-1">
            @promotionsIcon@
            </div>
              <div class="position-absolute top-0 right-0 pt-3 pr-3">
                <button type="button" class="btn btn-xs btn-icon btn-outline-secondary rounded-circle addToWishList @elementCartHide@" data-uid="@productlist_product_id@" data-toggle="tooltip" data-placement="top" title="Отложить">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>

            <div class="card-body pt-4 px-4 pb-0">
              <div class="mb-2">
                <span class="d-block font-size-1">
                  <a class="text-inherit" title="@productName@" href="@shopDir@@productlist_product_url@.html">@productlist_product_name@ </a>
                </span>
                <div class="d-block">
                  <span class="text-dark font-weight-bold">@productlist_product_price@<span class="rubznak">@productlastview_product_currency@</span></span>
                  <span class="text-body ml-1 @php __hide('productlist_product_price_old'); php@" ><del>@productlist_product_price_old@</del></span>
                </div>
              </div>
            </div>

            <div class="card-footer border-0 pt-0 pb-4 px-4">
              <div class="mb-3">
                <a class="d-inline-flex align-items-center small" href="#">
                  <div class="rating text-warning mr-2">
				  @rateCid@                  
				  </div>
                </a>
              </div>
              <button type="button" class="btn btn-sm btn-outline-primary btn-pill transition-3d-hover">@productSale@</button>
            </div>
          </div>
          <!-- End Product -->
          </div>
<!-- Конец Шаблон имя_шаблона/modules/productlist/templates/product.tpl для метки @productlist_list@  -->

