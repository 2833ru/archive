<div itemscope itemtype="http://schema.org/Product">
<div class="product-info">
    <div class="left"> @productFotoList@ </div>
    <div class="right">
        <div class="buy">
            <header class="product-name">
                <h1 itemprop="name">@productName@</h1>
            </header>
            <div class="price">
                <div itemprop="offers" itemscope itemtype="http://schema.org/Offer"> @ComStartCart@   <span itemprop="price">@productPrice@</span> <span itemprop="priceCurrency">@productValutaName@</span>    @ComEndCart@  <span class="price-tax">@productPriceRub@</span></div><br>@promotionInfo@
            </div>
            <div class="review"> @rateUid@ </div>
            <div class="description">
                <div class="tovar_art"> </div>
                <b>@productSklad@</b><br>@oneclick@
                <div>@productOnSklad@</div>
                <div>@productArt@</div>
            </div>
            <div class="options"> @ComStart@
                <div class="tovar_optionsDisp">@optionsDisp@</div>
            </div>
            <div class="cart">
                <div class="add-to-cart">
                    <script type="text/javascript">
                        function addOrDeleteProduct(flag, id) {
                            var num = 1;
                            var val = eval(document.getElementById("n" + id).value);
                            if (flag == 1) {
                                document.getElementById("n" + id).value = val + num;
                            } else if (flag == 0) {
                                if (val > 1) {
                                    document.getElementById("n" + id).value = val - num;
                                }
                            }
                        }


                    </script>
                    <input type="button" class="dec"  onclick="javascript:addOrDeleteProduct(0, @productUid@)" value="">
                    <input class="input-mini" style="width:15px" id="n@productUid@" type=num maxLength=3 size="5" value="1" name="n@productUid@">
                    <input type="button" class="inc" onclick="javascript:addOrDeleteProduct(1, @productUid@)" value="">
                    &nbsp;&nbsp;&nbsp;&nbsp;

                    @ComStartCart@ <A href="javascript:AddToCartNum(@productUid@,'n@productUid@')" title="@productSale@" class="button-exclusive" id="button-cart">В корзину</A> @ComEndCart@

                    @ComStartNotice@<a href="/users/notice.html?productId=@productUid@" title="@productNotice@" class="button-exclusive" id="button-cart">Уведомить</a>@ComEndNotice@ </div>
                <br>
                <div class="wishlist-compare"><a class="addToWishList" data-uid="@productUid@" title="Отложить @productName@"><span class="wishlist"></span>Отложить</a> &nbsp;&nbsp;&nbsp;&nbsp;<a class="addToCompareList"  data-uid="@productUid@" title="Сравнить @productName@"><span class="compare"></span>Сравнить</a></div>
            </div>
            @ComEnd@
            @productParentList@
            <div style="padding:10px 0px;" align="center"> <img src="images/shop/action_print.gif" alt="Печать"  border="0" align="absmiddle" > <A href="/print/UID_@productId@.html"  target="_blank" title="Версия для печати"> Версия для печати </A> </div>
        </div>
        <div class="share hidden-desktop">
            <div class="share42init"></div>
            <script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/share/share42.js"></script>
        </div>
    </div>
    <div id="right-sm"> @brandUidDescription@
        <div class="right-sm-share visible-desktop">
            <div class="product-share">
                <div class="share">
                    <div class="share42init"></div>
                    <script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/share/share42.js"></script>
                </div>
            </div>
        </div>
        <div class="visible-desktop">
            @cloud@ 
        </div>
    </div>
</div>
<section id="product-information">
    <div id="tabs" class="htabs">
        <div style="clear:both"></div>
        <a href="#tab-description" >Описание</a> <a href="#tab-vendor">Характеристики</a> @productFilesStart@ <a href="#tab-files">Файлы</a> @productFilesEnd@ <a href="#tab-feedback" class="tab-feedback">Отзывы</a> @pagetemaDispStart@ <a href="#tab-art">Статьи</a> @pagetemaDispEnd@
        <div style="clear:both"></div>
    </div>
    <div style="clear:both"></div>
    <div id="tab-description" class="tab-content" >
        <article class="tab-content2" itemprop="description">@productDes@</article>
    </div>
    <div id="tab-vendor" class="tab-content" >
        <article class="tab-content2">@vendorDisp@</article>
    </div>
    @productFilesStart@
    <div id="tab-files" class="tab-content">
        <article class="tab-content2">@productFiles@</article>
    </div>
    @productFilesEnd@
    <div id="tab-feedback" class="tab-content" >
        <article class="tab-content2">
            <div id="commentList"> </div>
            <br>
            <div id="addComment">&nbsp;</div>
            <h2 id="review-title">Оставьте свой отзыв</h2>
            <TEXTAREA id="message" class="commentTextarea" style="WIDTH: 1
                      40px" rows="5" onkeyup="return countSymb();"></TEXTAREA>
            <input type="hidden" id="commentAuthFlag" name="commentAuthFlag" value="@php if($_SESSION['UsersId']) echo 1; else echo 0; php@">
            <!--<input type="hidden" id="evalForCommentAuth" value="document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block';$(window).scrollTop(0);"> -->
            <DIV style="FONT-SIZE: 11px; MARGIN-BOTTOM: 5px">Максимальное количество символов: <SPAN id="count" style="WIDTH: 30px; COLOR: green; TEXT-ALIGN: center">0</SPAN>/&nbsp;&nbsp;&nbsp;500 </DIV>
            <DIV style="FONT-SIZE: 10px; MARGIN-BOTTOM: 5px"> <span>Ваша оценка:
                    1 &nbsp;
                    <input type="radio" name="rate" id="rate" value="1">
                    &nbsp;&nbsp;
                    2 &nbsp;
                    <input type="radio" name="rate" id="rate" value="2">
                    &nbsp;&nbsp;
                    3 &nbsp;
                    <input type="radio" name="rate" id="rate" value="3">
                    &nbsp;&nbsp;
                    4 &nbsp;
                    <input type="radio" name="rate" id="rate" value="4">
                    &nbsp;&nbsp;
                    5 &nbsp;
                    <input type="radio" name="rate" id="rate" value="5"  checked="">
                </span> </DIV>
            <div style="padding:5px" id="commentButtonAdd"> <a id="button-review" class="button" onclick="commentList('@productUid@', 'add', 1);
                            return false;">Добавить отзыв</a> </div>
            <div  id="commentButtonEdit" style="padding:5px;visibility:hidden;display:none"> 
                <!-- <a id="button-review" class="button" onclick="commentList('@productUid@', 'add', 1); return false;">Добавить отзыв</a> -->
                <a id="button-review" class="button" onclick="commentList('@productUid@', 'edit_add', '1');
                            return false;">Править отзыв</a> <a id="button-review" class="button" onclick="commentList('@productUid@', 'dell', '1');
                            return false;">Удалить</a>
                <input type="hidden" id="commentEditId">
            </div>
            <script>
                        setTimeout("commentList('@productUid@','list')", 500);
            </script>
        </article>
    </div>
 
    @pagetemaDispStart@
    <div id="tab-art" class="tab-content" >
        <article class="tab-content2"> @pagetemaDisp@</article>
    </div>
    @pagetemaDispEnd@ </section>
<script type="text/javascript">
    $('#tabs a').tabs();
</script>
