<!-- Product Info Starts -->
<div class="row product-info product-page-wrapper">
    <!-- Left Starts -->
    <div class="col-sm-5 images-block">
        <div id="fotoload">
            @productFotoList@
        </div>
    </div>
    <!-- Left Ends -->
    <!-- Right Starts -->
    <div class="col-sm-7 product-details">
        <!-- Product Name Starts -->
        <h2 itemprop="name">@productName@</h2>
        <!-- Product Name Ends -->
        <hr>
        <!-- Manufacturer Starts -->
        <ul class="list-unstyled manufacturer">
            <li>
                @productArt@
            </li>
            <li>
                @productSklad@
            </li>
            <li>
                <div class="rating">
                    <span>Рейтинг :</span>
                    @rateUid@
                </div>
            </li>
            <li> @promotionInfo@ </li>
        </ul>
        <!-- Manufacturer Ends -->
        <hr>
        <!-- Price Starts -->
        <div class="price">
            <span class="price-head">Цена :</span>
            <span class="price-new" itemprop="price">@productPrice@ <span itemprop="priceCurrency" class="rubznak">@productValutaName@</span></span><span class="price-old">@productPriceRub@</span>
        </div>
        <!-- Price Ends -->
        <hr>
        <!-- Available Options Starts -->

        <div class="options fix-wrapper">

            @optionsDisp@

            @ComStartCart@
            <div class="form-group">
                <label class="control-label text-uppercase" for="input-quantity">Количество</label>
                <input type="text" name="quantity" value="1" size="2" id="input-quantity" class="form-control addToCartListNum" data-uid="@productUid@"  type="text" maxlength="3" placeholder="1" required="">
            </div>
            <div class="cart-button button-group cart-list-button-wrapper">
                <button type="button" class="btn btn-cart addToCartList" role="button" data-num="1" data-uid="@productUid@" data-cart="@productSaleReady@">
                    <i class="fa fa-shopping-cart"></i>                                 
                    <span>@productSale@</span>
                </button>                                   
            </div>
            <div class="cart-button button-group compare-list-button-wrapper">
                <button type="button" class="btn btn-cart addToWishList" role="button" data-uid="@productUid@">
                    <i class="fa fa-heart" aria-hidden="true"></i>                            
                    Отложить
                </button>                                   
            </div>
            <div class="cart-button button-group compare-list-button-wrapper">
                <button type="button" class="btn btn-cart addToCompareList" role="button" data-uid="@productUid@">
                    <i class="fa fa-refresh" aria-hidden="true"></i>                            
                    Сравнить
                </button>                                   
            </div>

            @ComEndCart@ 

            @productParentList@


            @ComStartNotice@
            <div class="cart-button button-group compare-list-button-wrapper">
                <a class="btn btn-cart" href="/users/notice.html?productId=@productUid@" title="@productNotice@">
                    <i class="fa fa-envelope-o" aria-hidden="true"></i>                            
                    Уведомить
                </a>                                   
            </div>
            <div class="cart-button button-group compare-list-button-wrapper">
                <button type="button" class="btn btn-cart addToWishList" role="button" data-uid="@productUid@">
                    <i class="fa fa-heart" aria-hidden="true"></i>                            
                    Отложить
                </button>                                   
            </div>
            @ComEndNotice@ 

        </div>

        <!-- Available Options Ends -->
        <hr>
    </div>
    <!-- Right Ends -->
</div>
<!-- product Info Ends -->

<!-- Product Description Starts -->
<div class="product-info-box">
    <h4 class="heading">Описание</h4>
    <div class="content panel-smart">
        @productDes@
    </div>
</div>
<!-- Product Description Ends -->

<!-- Additional Information Starts -->
<div class="product-info-box empty-check">
    <h4 class="heading">Характеристики</h4>
    <div class="content panel-smart">
        @vendorDisp@
    </div>
</div>
<!-- Additional Information Ends -->

<!-- Reviews Information Starts -->
<div class="product-info-box">
    <h4 class="heading">Отзывы</h4>
    <div class="content panel-smart">
        <div id="commentList"></div>
        <button role="button" class="btn btn-info btn-show-comment-add-block" onclick="$('#addComment').slideToggle();
                $(this).hide();"><span class="glyphicon glyphicon-plus-sign"></span> Новый комментарий</button>
        <div id='addComment' class="well well-sm" style='display:none;margin-top:30px;'>
            <h3>Оставьте свой отзыв</h3>
            <textarea id="message" class="commentTexttextarea form-control"></textarea>
            <input type="hidden" id="commentAuthFlag" name="commentAuthFlag" value="@php if($_SESSION['UsersId']) echo 1; else echo 0; php@">
            <br>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-success btn-sm">
                    <input type="radio" name="rate" value="1"> +1
                </label>
                <label class="btn btn-success btn-sm">
                    <input type="radio" name="rate" value="2"> +2
                </label>
                <label class="btn btn-success btn-sm">
                    <input type="radio" name="rate" value="3"> +3
                </label>
                <label class="btn btn-success btn-sm">
                    <input type="radio" name="rate" value="4"> +4
                </label>
                <label class="btn btn-success btn-sm active">
                    <input type="radio" name="rate" value="5" checked> +5
                </label>
                <button role="button" class="btn btn-info btn-sm pull-right" onclick="commentList('@productUid@', 'add', 1);">Проголосовать</button>
            </div>
        </div>
    </div>
    <script type="text/javascript">
            $(document).ready(function() {
                commentList('@productUid@', 'list');
            });
    </script>
</div>
<!-- Reviews Information Ends -->

<!-- Files Information Starts -->
<div class="product-info-box empty-check">
    <h4 class="heading">Файлы</h4>
    <div class="content panel-smart">
        @productFiles@
    </div>
</div>
<!-- Files Information Ends -->

<!-- Articles Information Starts -->
<div class="product-info-box empty-check">
    <h4 class="heading">Статьи</h4>
    <div class="content panel-smart">
        @pagetemaDisp@
    </div>
</div>
<!-- Articles Information Ends -->


<!-- Модальное окно фотогалереи -->
<div class="modal bs-example-modal" id="sliderModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>

                <h4 class="modal-title" id="myModalLabel">@productName@</h4>
            </div>
            <div class="modal-body">
                @productFotoListBig@

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<!--/ Модальное окно фотогалереи -->