<div class="product-details clearfix">
  <div class="span5">
    <div class="product-title">
      <h1>@productName@</h1>
    </div>
    <div>@productArt@</div>
    <table style="width:340px"  >
      <tr>
        <td ></td>
        <td style="text-align:center"><div id="fotoload" style="text-align:center" class="product-img">@productFotoList@</div></td>
        <td ></td>
      </tr>
      <tr>
        <td style="width:3px; height:3px"><img src="images/pic1.gif" alt="" width="3" height="3" /></td>
        <td></td>
        <td style="width:3px; height:3px"><img src="images/pic3.gif" alt="" width="3" height="3" /></td>
      </tr>
    </table>
  </div>
  <div style="padding:0px 0px; text-align: right"> <img src="images/shop/action_print.gif" alt="Печать" > <A href="/print/UID_@productId@.html"  target="_blank" title="Версия для печати"> Версия для печати </A> </div>
  <div class="span4">
    <div class="product-set">
      <div class="product-price"> <span><span class="strike-through">@productPriceRub@</span> @ComStartCart@@productPrice@ @productValutaName@@ComEndCart@</span></div>
      <div class="hidden-xs">@oneclick@</div>
      <div class="product-rate clearfix"> @rateUid@ </div>
      <div class="hidden-xs">@promotionInfo@ </div>
      <div class="product-info">
        <div class="vendor-disp">@vendorDisp@</div>
      </div>
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
          <div class="visible-desktop"> @cloud@ </div>
        </div>
        <form method="post" action="page">
          <div class="controls-row @elementCartOptionHide@"> @optionsDisp@
            @productParentList@ </div>
          <div>
		   @ComStartCart@
          <div class="input-append">
            <input class="span1" type="text" id="n@productUid@" name="n@productUid@" value="1" placeholder="1">
            <button rel="tooltip" data-original-title="В корзину" onclick="AddToCartNum(@productUid@,'n@productUid@'); return false;" class="btn btn-primary"><i class="icon-shopping-cart"></i> В корзину</button>
            <button rel="tooltip" data-original-title="Отложить" class="btn" onclick="addToWishList(@productUid@); return false;"  data-toggle="tooltip" data-title="+ Отложить"><i class="icon-heart"></i></button>
            <button rel="tooltip" data-original-title="Сравнить" onclick="AddToCompare(@productUid@); return false;" class="btn" data-toggle="tooltip" data-title="Сравнить @productName@"><i class="icon-refresh"></i></button>
          </div>
         @ComEndCart@
      </div></form>
      @ComEnd@ </div>
  </div>
</div>
</div>
<div class="product-tab">
  <ul class="nav nav-tabs">
    <li class="active"> <a href="#descraption" data-toggle="tab">Описание</a> </li>
    <li> <a href="#specfications" data-toggle="tab">Характеристики</a> </li>
    <li> <a href="#return-info" data-toggle="tab">Файлы</a> </li>
    <li><a href="#read-review" data-toggle="tab" class="feedback">Отзывы</a></li>
    <li><a href="#product-pages" data-toggle="tab" class="feedback">Статьи</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="descraption"> @productDes@ </div>
    <div class="tab-pane" id="specfications"> @vendorDisp@ </div>
    <div class="tab-pane" id="return-info">@productFiles@ </div>
    <div class="tab-pane" id="read-review">
      <div class="single-review clearfix">
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
          <div  id="commentButtonEdit" style="padding:5px;visibility:hidden;display:none">
            <!-- <a id="button-review" class="button" onclick="commentList('@productUid@', 'add', 1); return false;">Добавить отзыв</a> -->
            <a id="button-review" class="button" onclick="commentList('@productUid@', 'edit_add', '1');
                            return false;">Править отзыв</a> <a id="button-review" class="button" onclick="commentList('@productUid@', 'dell', '1');
                            return false;">Удалить</a>
            <input type="hidden" id="commentEditId">
          </div>
        </article>
        <div style="padding:5px" id="commentButtonAdd">
          <input class="btn" type="button"  value="Добавить комментарий" onclick="commentList('@productUid@','add',1)" >
        </div>
        <div  id="commentButtonEdit" style="padding:5px;visibility:hidden;display:none">
          <input type="button"  value="Добавить комментарий" onclick="commentList('@productUid@','add',1)" >
          <input type="button"  value="Править комментарий" onclick="commentList('@productUid@','edit_add','1')" >
          <input type="button"  value="Удалить" onclick="commentList('@productUid@','dell','1')" >
          <input type="hidden" id="commentEditId">
        </div>
        <div id="commentList"> </div>
        <script>
            setTimeout("commentList('@productUid@','list')",500);
        </script>
      </div>
    </div>
    <div class="tab-pane" id="make-review">@ratingfull@ </div>
    <div class="tab-pane" id="product-pages">@pagetemaDisp@</div>
  </div>
</div>
