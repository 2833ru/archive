<table cellpadding="0" cellspacing="0" border="0" class="cartTable">
    <tbody>
        <tr>
            <td valign="top" class="cartTableTd1"><div id="fotoload" align="center">@productFotoList@</div></td>
            <td valign="top" class="cartTableTd2">&nbsp;</td>
            <td valign="top" class="cartTableTd3"><h1 class="cartName">@productName@</h1>
                @ComStart@
                <div class="optionsDispSelect">@optionsDisp@</div>
                <span class="productPriceRub">@productPriceRub@</span> @ComStartCart@
                <div class="productPrice"><span>Цена:</span> @productPrice@ @productValutaName@</div>
                @ComEndCart@ <span class="productSklad">@productSklad@</span><br>@oneclick@
                <div class="productBay-1">
                    <div class="productBay-2">
                        <!-- Блок уведомить -->
                        @ComStartNotice@ <a href="/users/notice.html?productId=@productId@" title="@productNotice@"><img src="images/furniture_29.gif" border="0"></a> @ComEndNotice@
                        <!-- Блок уведомить -->
                        <!-- Блок корзина -->
                        @ComStartCart@ <a href="javascript:AddToCart(@productId@)" title="@productSale@"><img src="images/furniture_28.gif" border="0"></a> @ComEndCart@
                        <!-- Блок корзина -->
                    </div>
                    
                </div>
                <div class="productCompare"><a href="javascript:AddToCompare(@productUid@)" title="Сравнить @productName@"><img src="images/furniture_30.gif" border="0"></a></div>
                @ComEnd@@productParentList@
                <br>@promotionInfo@
                </td>
        </tr>
    </tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="tabTable">
    <tbody>
        <tr>
            <td valign="top" class="tabTD"><div id="tabPanel1" class="tabPanel1_on" onClick="tabPaneClick('tabPanel', 'tabPanelSlect', '1', '6');" onMouseOver="tabPaneOver('tabPanel', 'tabPanelSlect', '1');" onMouseOut="tabPaneOut('tabPanel', 'tabPanelSlect', '1');">Описание</div>
                <div id="tabPanel2" class="tabPanel2_off" onClick="tabPaneClick('tabPanel', 'tabPanelSlect', '2', '6');" onMouseOver="tabPaneOver('tabPanel', 'tabPanelSlect', '2');" onMouseOut="tabPaneOut('tabPanel', 'tabPanelSlect', '2');">Файлы</div>
                <div id="tabPanel3" class="tabPanel3_off" onClick="tabPaneClick('tabPanel', 'tabPanelSlect', '3', '6');" onMouseOver="tabPaneOver('tabPanel', 'tabPanelSlect', '3');" onMouseOut="tabPaneOut('tabPanel', 'tabPanelSlect', '3');">Характеристики</div>
                <div id="tabPanel4" class="tabPanel4_off" onClick="tabPaneClick('tabPanel', 'tabPanelSlect', '4', '6');" onMouseOver="tabPaneOver('tabPanel', 'tabPanelSlect', '4');" onMouseOut="tabPaneOut('tabPanel', 'tabPanelSlect', '4');">Рейтинг</div>
                <div id="tabPanel5" class="tabPanel5_off" onClick="tabPaneClick('tabPanel', 'tabPanelSlect', '5', '6');" onMouseOver="tabPaneOver('tabPanel', 'tabPanelSlect', '5');" onMouseOut="tabPaneOut('tabPanel', 'tabPanelSlect', '5');">Отзывы</div>
                <div id="tabPanel6" class="tabPanel6_off" onClick="tabPaneClick('tabPanel', 'tabPanelSlect', '6', '6');" onMouseOver="tabPaneOver('tabPanel', 'tabPanelSlect', '6');" onMouseOut="tabPaneOut('tabPanel', 'tabPanelSlect', '6');">Статьи</div></td>
        </tr>
    </tbody>
</table>
<div class="tabPanelSect_on" id="tabPanelSlect1"> @productDes@ </div>
<div class="tabPanelSect_off" id="tabPanelSlect2"> @productFiles@ </div>
<div class="tabPanelSect_off" id="tabPanelSlect3"> @vendorDisp@ </div>
<div class="tabPanelSect_off" id="tabPanelSlect4"> @ratingfull@ </div>
<div class="tabPanelSect_off" id="tabPanelSlect5">
    <div id="bg_catalog_1" style="margin-top:10px">Комментарии пользователей</div>
    <TEXTAREA id="message" style="WIDTH: 340px" rows="5" onkeyup="return countSymb();" class="borderForm"></TEXTAREA>
  <DIV style="FONT-SIZE: 10px; MARGIN-BOTTOM: 5px">Максимальное количество символов: <SPAN id="count" style="WIDTH: 30px; COLOR: green; TEXT-ALIGN: center">0</SPAN>/&nbsp;&nbsp;&nbsp;500 </DIV>
  <DIV style="padding: 5px"> <IMG onmouseover="this.style.cursor = 'hand';" title=Смеется onclick="emoticon(':-D');" alt=Смеется src="images/smiley/grin.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title=Улыбается onclick="emoticon(':)');" alt=Улыбается src="images/smiley/smile3.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title=Грустный onclick="emoticon(':(');" alt=Грустный src="images/smiley/sad.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title="В шоке" onclick="emoticon(':shock:');" alt="В шоке" src="images/smiley/shok.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title=Самоуверенный onclick="emoticon(':cool:');" alt=Самоуверенный src="images/smiley/cool.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title=Стесняется onclick="emoticon(':blush:');" alt=Стесняется src="images/smiley/blush2.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title=Танцует onclick="emoticon(':dance:');" alt=Танцует src="images/smiley/dance.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title=Счастлив onclick="emoticon(':rad:');" alt=Счастлив src="images/smiley/happy.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title="Под столом" onclick="emoticon(':lol:');" alt="Под столом" src="images/smiley/lol.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title="В замешательстве" onclick="emoticon(':huh:');" alt="В замешательстве" src="images/smiley/huh.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title=Загадочный onclick="emoticon(':rolly:');" alt=Загадочный src="images/smiley/rolleyes.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title=Злой onclick="emoticon(':thuf:');" alt=Злой src="images/smiley/threaten.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title="Показывает язык" onclick="emoticon(':tongue:');" alt="Показывает язык" src="images/smiley/tongue.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title=Умничает onclick="emoticon(':smart:');" alt=Умничает src="images/smiley/umnik2.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title=Запутался onclick="emoticon(':wacko:');" alt=Запутался src="images/smiley/wacko.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title=Соглашается onclick="emoticon(':yes:');" alt=Соглашается src="images/smiley/yes.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title=Радостный onclick="emoticon(':yahoo:');" alt=Радостный src="images/smiley/yu.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title=Сожалеет onclick="emoticon(':sorry:');" alt=Сожалеет src="images/smiley/sorry.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title="Нет Нет" onclick="emoticon(':nono:');" alt="Нет Нет" src="images/smiley/nono.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title="Бьется об стенку" onclick="emoticon(':dash:');" alt="Бьется об стенку" src="images/smiley/dash.gif" border=0> <IMG onmouseover="this.style.cursor = 'hand';" title=Скептический onclick="emoticon(':dry:');" alt=Скептический src="images/smiley/dry.gif" border=0> </DIV>
  <div style="padding:5px" id="commentButtonAdd">
    <input type="button"  value="Добавить комментарий" onclick="commentList('@productUid@', 'add', 1)" >
  </div>
  <div  id="commentButtonEdit" style="padding:5px;visibility:hidden;display:none">
    <input type="button"  value="Добавить комментарий" onclick="commentList('@productUid@', 'add', 1)" >
    <input type="button"  value="Править комментарий" onclick="commentList('@productUid@', 'edit_add', '1')" >
    <input type="button"  value="Удалить" onclick="commentList('@productUid@', 'dell', '1')" >
    <input type="hidden" id="commentEditId">
  </div>
  <div id="commentList" style="padding-top: 10px"> </div>
  <script>
            setTimeout("commentList('@productUid@','list')", 500);
  </script>
</div>
<div class="tabPanelSect_off" id="tabPanelSlect6"> @pagetemaDisp@ </div>
