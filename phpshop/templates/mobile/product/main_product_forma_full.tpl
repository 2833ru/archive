
<div class="segmented-control" id="segmented-control">
  <a class="control-item active" href="#item1mobile" onclick="tab_on(this)">
    Описание
  </a>
  <a class="control-item" href="#item2mobile" onclick="tab_on(this)">
    Фото
  </a>
  <a class="control-item" href="#item3mobile" onclick="tab_on(this)">
    Опции
  </a>
</div>
<div class="card" id="segmented-content">
  <span id="item1mobile" class="control-content active" style="padding:10px">
      <h3>@productName@</h3>
      @productDes@
  </span>
    <span id="item2mobile" class="control-content">
      
     @productFotoList@
      
  </span>
  <span id="item3mobile" class="control-content">
      @vendorDisp@
  </span>
</div>

<div style="margin:10px">
    @ComStartCart@
    <A href="@ShopDir@/order/?from=html&id=@productUid@" title="@productSale@" onclick="go(this.href)"><button class="btn btn-positive btn-block"><span class="icon icon-download"></span> Купить за @productPrice@ @productValutaName@</button></a>
    @ComEndCart@
    @productParentList@
</div>
