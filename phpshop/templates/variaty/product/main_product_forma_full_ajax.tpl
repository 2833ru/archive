<div class="product-details clearfix"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">
<div class="modal-header"><i class="icon-remove"></i></button></div>
  <div class="span5">
    <div class="product-title">
        <h4><b><a class="highslide" href="/shop/UID_@productUid@.html" title="@productName@">@productName@</a></b></h4>
    </div>
    <div>@productArt@</div>
    <table  width="340" cellspacing="0" cellpadding="0">
      <tr>
        <td ></td>
        <td align="center"><div id="fotoload" align="center" class="product-img">@productFotoList@</div></td>
        <td ></td>
      </tr>
      <tr>
        <td width="3" height="3"><img src="images/pic1.gif" alt="" width="3" height="3" /></td>
        <td></td>
        <td width="3" height="3"><img src="images/pic3.gif" alt="" width="3" height="3" /></td>
      </tr>
    </table>
  </div>
  <div class="to-print"> <img src="images/shop/action_print.gif" alt="Печать"    align="absmiddle" > <A href="/print/UID_@productId@.html"  target="_blank" title="Версия для печати"> Версия для печати </A> </div>
  <div class="span4">
    <div class="product-set">
      <div class="product-price"> <span><span class="strike-through">@productPriceRub@</span> @ComStartCart@@productPrice@ @productValutaName@@ComEndCart@</span> </div>
      <div class="product-rate clearfix">
          @rateUid@
        </div>
		
      <div class="product-info"> <div class="vendor-disp">@vendorDisp@</div>	
        @productDes@ </div>
      @ComStart@
      <div class="product-inputs">
      <div id="right-sm"> 
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
        <form method="post" action="page">
          <div class="controls-row"> @optionsDisp@
            @productParentList@ </div>
          </div>
          <div class="input-append">
            <input class="span1" type="text" id="n@productUid@" name="n@productUid@" value="1" placeholder="1">
            <button onclick="AddToCartNum(@productUid@,'n@productUid@'); return false;" class="btn btn-primary"><i class="icon-shopping-cart"></i> В корзину</button>
            <button class="btn" onclick="addToWishList(@productUid@); return false;"  data-toggle="tooltip" data-title="+ Отложить"><i class="icon-heart"></i></button>
            <button onclick="AddToCompare(@productUid@); return false;" class="btn" data-toggle="tooltip" data-title="Сравнить @productName@"><i class="icon-refresh"></i></button>
          </div>
        </form>
      </div>
      @ComEnd@
      </div>
  </div>
</div>


</div>
