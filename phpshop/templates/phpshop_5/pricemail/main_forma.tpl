<div class="siteMiddleDivContent">
<div id="bg_catalog_1">Пожаловаться на цену</div>
<div id="bglist"></div>
<div id=allspec><a href="/" class="link">Главная</a> <img src="images/shop/arr2.gif" alt="" width="16" height="16" border="0" align="absmiddle"> <a href="/shop/UID_@productUid@_@productNameLat@.html" class="link">@productName@</a> <img src="images/shop/arr2.gif" alt="" width="16" height="16" border="0" align="absmiddle"> Пожаловаться на цену</div>
<div align="center" style="padding:10px"><table>
<tr>
	<td><img src="@productImg@" alt="" border="0"></td>
	<td style="padding:10px"><h2>@productName@</h2>
	<TABLE  BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
		<TD align="center" class="center">
		<strong class=price > @productPrice@ @productValutaName@</strong><br>
		<font class=black>@productPriceRub@</font>
		</TD>
	</TR>
</TABLE>
	</td>
</tr>
</table>
</div>
<div id="bg_catalog_1">Личные данные</div>
<div id="bglist"></div>
<form method="post" name="forma_pricemail">
<table  cellpadding="0" cellspacing="0" width=100 >
<tr valign="top">
    <td align="right">
	E-mail:
	</td>
	<td>
	<input type="text" name="mail" style="width:300px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="30" value="@UserMail@" @formaLock@><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">
	</td>
</tr>
<tr>
	<td align="right" class=tah12>
    Контактное лицо:
	</td>
	<td>
	<input type="text" name="name_person" style="width:300px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="30" value="@UserName@" @formaLock@>
<img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">
	</td>
</tr>
<tr>
	<td align="right" >
	Ссылка на товар с меньшей ценой:
	</td>
	<td>
	<input type="text" name="link_to_page" style="width:300px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="100" >
<img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">
	</td>
</tr>
<tr>
	<td align="right" >
	Компания:
	</td>
	<td>
	<input type="text" name="org_name" style="width:300px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="100" value="@UserComp@" @formaLock@>
	</td>
</tr>
<tr>
	<td align="right">
	Телефон:
	</td>
	<td>
	<input type="text" name="tel_code" style="width:50px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="5" value="@UserTelCode@"> -
	<input type="text" name="tel_name" style="width:150px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F " maxlength="30" value="@UserTel@">
	</td>
</tr>
<tr>
	<td align="right" class=tah12>
	Дополнительная<br>
	информация:
	</td>
	<td>
	<textarea style="width:300px; height:100px; font-family:tahoma; font-size:11px ; color:#4F4F4F " name="adr_name" >@UserAdres@</textarea>
	</td>
</tr>
<tr>
  <td></td>
  <td>
  <div  id=allspecwhite><img src="images/shop/comment.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">Данные, отмеченные <b>флажками</b> обязательны для заполнения.<br>
</div>

  </td>
</tr>
<tr>
	<td align="right"><IMG alt="" src="phpshop/captcha.php" border=0></td>
	<td style="padding-left:10px">Введите код, указанный на картинке:<BR><INPUT style="WIDTH: 220px" name="key" id="key"></td>
</tr>
<tr>
    <td colspan="2" align="center">
	<p><br></p>
	<table align="center">
<tr>
<td>
	<img src="images/shop/brick_error.gif" border="0" align="absmiddle">
	<a href="javascript:forma_pricemail.reset();" class=link>Очистить форму</a></td>
	<td width="20"></td>
	<td><img src="images/shop/brick_go.gif"  border="0" align="absmiddle">
	<a href="javascript:forma_pricemail.submit();" class=link>Отправить сообщение</a></td>
	
	
</tr>
</table>
	<input type="hidden" name="send_price_link" value="ok">
    </td>
</tr>
</table>
</form>
</div>