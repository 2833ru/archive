<h1>@productName@</h1>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
  <tbody>
    <tr>
      <td  width="361" valign="top"><table border="0" width="340" cellspacing="0" cellpadding="0">
          <tr>
            <td width="3" height="3"><img src="images/pic1.gif" alt="" width="3" height="3" /></td>
            <td class="pic2"></td>
            <td width="3" height="3"><img src="images/pic3.gif" alt="" width="3" height="3" /></td>
          </tr>
          <tr>
            <td class="pic8"></td>
            <td align="center"><div id="fotoload" align="center" >@productFotoList@</div></td>
            <td class="pic4"></td>
          </tr>
          <tr>
            <td width="3" height="3"><img src="images/pic1.gif" alt="" width="3" height="3" /></td>
            <td class="pic6"></td>
            <td width="3" height="3"><img src="images/pic3.gif" alt="" width="3" height="3" /></td>
          </tr>
        </table></td>
      <td><div style="width:15px"></div></td>
      <td  align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td  width="150"><span class="tovar_art">Артикул @productArt@</span></td>
            <td ><!-- AddThis Button BEGIN -->
              <div class="addthis_toolbox addthis_default_style " > <a class="addthis_button_preferred_1"></a> <a class="addthis_button_preferred_2"></a> <a class="addthis_button_preferred_3"></a> <a class="addthis_button_preferred_4"></a> <a class="addthis_button_compact"></a> <a class="addthis_counter addthis_bubble_style"></a> </div>
              <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e9fd3684a6b1802"></script>
              <!-- AddThis Button END --></td>
          </tr>
        </table>
        <div class="tovar_content">@productDes@</div>
        @ComStart@
        <div class="tovar_optionsDisp">@optionsDisp@</div>
      <table style="margin:10px 0px" width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><div style="width:75px"  align="left"><span class="tovarDivOldPrice1">@productPriceRub@</span></div></td>
            <td> @ComStartCart@
              <div class="tovarDivPrice1" align="left"> <span>@productPrice@ @productValutaName@</span> </div>
              @ComEndCart@</td>
            <td><div class="tovarDivAdd1" style="padding-bottom:0px; margin-top:0px;">
                <div class="tovarDivAdd3">
                  <!-- Блок купить -->
                  @ComStartCart@<a href="javascript:AddToCart(@productUid@)" title="@productSale@">@productSale@</a>
                  <!-- Блок купить -->
                  <!-- Блок уведомить -->
                  @ComStartNotice@<a href="/users/notice.html?productId=@productUid@" title="@productNotice@">@productNotice@</a>@ComEndNotice@
                  <!-- Блок уведомить -->
                </div>
                <div class="tovarDivAdd2"><a href="javascript:AddToCompare(@productUid@)" title="Сравнить @productName@">Сравнить</a></div>
              </div></td>
          </tr>
        </table>
       <div>          <b>@productSklad@</b>
		<br>@oneclick@</div>
        @ComEnd@
        
        @productParentList@ </td>
    </tr>
  </tbody>
</table>
<div class="tab-pane" id="tabPane1" style="margin-top: 20px">
  <script type="text/javascript">
        tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
    </script>
  <div class="tab-page" id="tabPage1">
    <h2 class="tab">
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="3" height="34"><img src="images/spacer.gif" class="sel_left" alt="" width="3" height="34" border="0" /></td>
          <td class="sel_center">Характеристики</td>
          <td width="3" height="34"><img src="images/spacer.gif" class="sel_right" alt="" width="3" height="34" border="0" /></td>
        </tr>
      </table>
    </h2>
    <script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage1" ) );</script>
    @vendorDisp@ </div>
  <div class="tab-page" id="tabPage6">
    <h2 class="tab">
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="3" height="34"><img src="images/spacer.gif" class="sel_left" alt="" width="3" height="34" border="0" /></td>
          <td class="sel_center">Файлы</td>
          <td width="3" height="34"><img src="images/spacer.gif" class="sel_right" alt="" width="3" height="34" border="0" /></td>
        </tr>
      </table>
    </h2>
    <script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage6" ) );</script>
    @productFiles@ </div>
  <div class="tab-page" id="tabPage5">
    <h2 class="tab">
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="3" height="34"><img src="images/spacer.gif" class="sel_left" alt="" width="3" height="34" border="0" /></td>
          <td class="sel_center">Рейтинг</td>
          <td width="3" height="34"><img src="images/spacer.gif" class="sel_right" alt="" width="3" height="34" border="0" /></td>
        </tr>
      </table>
    </h2>
    <script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage5" ) );</script>
    @ratingfull@ </div>
  <div class="tab-page" id="tabPage3">
    <h2 class="tab">
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="3" height="34"><img src="images/spacer.gif" class="sel_left" alt="" width="3" height="34" border="0" /></td>
          <td class="sel_center">Отзывы</td>
          <td width="3" height="34"><img src="images/spacer.gif" class="sel_right" alt="" width="3" height="34" border="0" /></td>
        </tr>
      </table>
    </h2>
    <script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage3" ) );</script>
    <div id="bg_catalog_1" style="margin-top:10px">Комментарии пользователей</div>
    <TEXTAREA id="message" style="WIDTH: 340px" rows="5" onkeyup="return countSymb();"></TEXTAREA>
    <DIV style="FONT-SIZE: 10px; MARGIN-BOTTOM: 5px">Максимальное количество символов: <SPAN id="count" style="WIDTH: 30px; COLOR: green; TEXT-ALIGN: center">0</SPAN>/&nbsp;&nbsp;&nbsp;500 </DIV>
    <DIV style="padding: 5px"> <IMG onmouseover="this.style.cursor='hand';" title=Смеется onclick="emoticon(':-D');" alt=Смеется src="images/smiley/grin.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title=Улыбается onclick="emoticon(':)');" alt=Улыбается src="images/smiley/smile3.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title=Грустный onclick="emoticon(':(');" alt=Грустный src="images/smiley/sad.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title="В шоке" onclick="emoticon(':shock:');" alt="В шоке" src="images/smiley/shok.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title=Самоуверенный onclick="emoticon(':cool:');" alt=Самоуверенный src="images/smiley/cool.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title=Стесняется onclick="emoticon(':blush:');" alt=Стесняется src="images/smiley/blush2.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title=Танцует onclick="emoticon(':dance:');" alt=Танцует src="images/smiley/dance.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title=Счастлив onclick="emoticon(':rad:');" alt=Счастлив src="images/smiley/happy.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title="Под столом" onclick="emoticon(':lol:');" alt="Под столом" src="images/smiley/lol.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title="В замешательстве" onclick="emoticon(':huh:');" alt="В замешательстве" src="images/smiley/huh.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title=Загадочный onclick="emoticon(':rolly:');" alt=Загадочный src="images/smiley/rolleyes.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title=Злой onclick="emoticon(':thuf:');" alt=Злой src="images/smiley/threaten.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title="Показывает язык" onclick="emoticon(':tongue:');" alt="Показывает язык" src="images/smiley/tongue.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title=Умничает onclick="emoticon(':smart:');" alt=Умничает src="images/smiley/umnik2.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title=Запутался onclick="emoticon(':wacko:');" alt=Запутался src="images/smiley/wacko.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title=Соглашается onclick="emoticon(':yes:');" alt=Соглашается src="images/smiley/yes.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title=Радостный onclick="emoticon(':yahoo:');" alt=Радостный src="images/smiley/yu.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title=Сожалеет onclick="emoticon(':sorry:');" alt=Сожалеет src="images/smiley/sorry.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title="Нет Нет" onclick="emoticon(':nono:');" alt="Нет Нет" src="images/smiley/nono.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title="Бьется об стенку" onclick="emoticon(':dash:');" alt="Бьется об стенку" src="images/smiley/dash.gif" border=0> <IMG onmouseover="this.style.cursor='hand';" title=Скептический onclick="emoticon(':dry:');" alt=Скептический src="images/smiley/dry.gif" border=0> </DIV>
    <div style="padding:5px" id="commentButtonAdd">
      <input type="button"  value="Добавить комментарий" onclick="commentList('@productUid@','add',1)" >
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
  <div class="tab-page" id="tabPage4">
    <h2 class="tab">
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="3" height="34"><img src="images/spacer.gif" class="sel_left" alt="" width="3" height="34" border="0" /></td>
          <td class="sel_center">Статьи</td>
          <td width="3" height="34"><img src="images/spacer.gif" class="sel_right" alt="" width="3" height="34" border="0" /></td>
        </tr>
      </table>
    </h2>
    <script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage4" ) );</script>
    @pagetemaDisp@ </div>
</div>
