<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tbody>
    	<tr>
        	<td align="center" valign="top"><div id="fotoload" align="center">@productFotoList@</div></td>
          <td><div style="width:15"></div></td>
            <td width="100%" align="left" valign="top">
           	  <div class="tovar_name">@productName@</div>
              <span class="tovar_art">@productArt@</span>
                <div class="tovar_content">@productDes@</div>
                @ComStart@<div class="tovar_optionsDisp">@optionsDisp@</div>@ComEnd@
                <div class="tovar_price">Цена:&nbsp;&nbsp;&nbsp;<span class="price">@productPrice@ @productValutaName@</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="tovar_productSklad">@productSklad@</span></div>
                <div class="tovar_priceOld">@productPriceRub@</div><br>@oneclick@
				@ComStart@@ComStartNotice@<div class="tovar_notice"><a href="/users/notice.html?productId=@productId@" title="@productNotice@"><img src="images/clothes_37.gif" border="0"></a></div>@ComEndNotice@
				@ComStartCart@<div class="tovar_order"><a href="javascript:AddToCart(@productId@)" title="@productSale@"><img src="images/clothes_36.gif" border="0"></a></div>@ComEndCart@
                <div class="tovar_compare"><a href="javascript:AddToCompare(@productUid@)" title="Сравнить @productName@"><img src="images/clothes_38.gif" border="0"></a></div>@ComEnd@
				@productParentList@
                <SCRIPT type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/links/links.js"></SCRIPT>
				<div class="full-links"><SCRIPT type="text/javascript">share42("","","@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@")</SCRIPT></div>
          </td>
      </tr>
    </tbody>
</table>

<div class="tab-pane" id="tabPane1" style="margin-top: 25px">
<script type="text/javascript">
tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
</script>
   <div class="tab-page" id="tabPage6">
		<h2 class="tab">Файлы</h2>
		
		<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage6" ) );</script>
		
		@productFiles@
		
	</div>
	<div class="tab-page" id="tabPage2">
		<h2 class="tab">Характеристики</h2>
		
		<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage2" ) );</script>
		
		@vendorDisp@
		
	</div>
	<div class="tab-page" id="tabPage5">
		<h2 class="tab">Рейтинг</h2>
		
		<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage5" ) );</script>
		
		@ratingfull@
		
	</div>
	<div class="tab-page" id="tabPage3">
		<h2 class="tab">Отзывы</h2>
		
		<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage3" ) );</script>
		
		<div id="bg_catalog_1" style="margin-top:10px">Комментарии пользователей</div>
<TEXTAREA id="message" style="WIDTH: 340px" rows="5" onkeyup="return countSymb();"></TEXTAREA>
<DIV style="FONT-SIZE: 10px; MARGIN-BOTTOM: 5px">Максимальное количество символов: <SPAN id="count" style="WIDTH: 30px; COLOR: green; TEXT-ALIGN: center">0</SPAN>/&nbsp;&nbsp;&nbsp;500 </DIV>
<div style="padding:5px" id="commentButtonAdd">
<input type="button"  value="Добавить комментарий" onclick="commentList('@productUid@','add',1)" >
</div>
<div  id="commentButtonEdit" style="padding:5px;visibility:hidden;display:none">
<input type="button"  value="Добавить комментарий" onclick="commentList('@productUid@','add',1)" >
<input type="button"  value="Править комментарий" onclick="commentList('@productUid@','edit_add','1')" >
<input type="button"  value="Удалить" onclick="commentList('@productUid@','dell','1')" >
<input type="hidden" id="commentEditId">
</div>
<div id="commentList" style="padding-top: 10px">
</div>
<script>
setTimeout("commentList('@productUid@','list')",500);
</script>
		
	</div>
	<div class="tab-page" id="tabPage4">
		<h2 class="tab">Статьи</h2>
		
		<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage4" ) );</script>
		
		@pagetemaDisp@
		
	</div>
	
</div>