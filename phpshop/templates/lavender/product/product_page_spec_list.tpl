<a href="/">Главная</a> / @catalogCategory@
<h2>@catalogCategory@</h2>
<form method="post" action="/shop/CID_@productId@.html" name="sort">
  <table width="704" class="filt6" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="3" height="3"><img src="images/filt1.gif" alt="" width="3" height="3" /></td>
      <td width="100%"></td>
      <td width="3" height="3"><img src="images/filt2.gif" alt="" width="3" height="3" /></td>
    </tr>
    <tr>
      <td  colspan="3"><table cellpadding="0" cellspacing="0" border="0" class="vendorDisp" >
          <tr>
            <td  ><table border="0" cellspacing="0" cellpadding="0">
                <tr> @vendorDisp@ </tr>
              </table></td>
            <td >@vendorSelectDisp@</td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td  height="2"  colspan="3"  class="filt5"><img src="images/spacer.gif" alt="" width="1" height="2" /></td>
    </tr>
    <tr>
      <td colspan="3" height="29"><div class="divSortPageList">Сортировать по: наименованию ( <span class="spanSortPageList"><a href="./spec_@productPageThis@.html?v=@productVendor@&f=2&amp;s=1">возр</a> / <a href="./spec_@productPageThis@.html?v=@productVendor@&f=1&amp;s=1">убыв</a></span> ), цене ( <span class="spanSortPageList"><a href="./spec_@productPageThis@.html?v=@productVendor@&f=2&amp;s=2">возр</a> / <a href="./spec_@productPageThis@.html?v=@productVendor@&f=1&amp;s=2">убыв</a></span> )</div></td>
    </tr>
    <tr>
      <td width="3" height="3"><img src="images/filt4.gif" alt="" width="3" height="3" /></td>
      <td></td>
      <td width="3" height="3"><img src="images/filt3.gif" alt="" width="3" height="3" /></td>
    </tr>
  </table>
</form>
<div style="overflow:hidden; margin-top:20px">
  <table cellpadding="0" cellspacing="0" border="0" width="100%">
    @productPageDis@
  </table>
</div>
<div class="navi">@productPageNav@</div>
