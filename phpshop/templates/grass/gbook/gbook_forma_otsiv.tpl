 <div class="plashka_center">
				<div class="plashka_zag">Форма отзыва</div>
			</div>
<div class="page_nava">
<div>
<a href="/">Главная</a> / <a href="/gbook/">Отзывы</a> /  Форма отзыва
</div></div>

<p><br></p>
<table cellpadding="5" cellspacing="1" border="0" class="standart">
<form method="post" name="forma_gbook">
<tr>
	<td width="50" align="right">
	Имя
	</td>
	<td>
	<input type="text" name="name_new" maxlength="45" style="width:300px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F "><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0">
	</td>
</tr>
<tr >
	<td width="50" align="right">
	E-mail
	</td>
	<td>
	<input  class=s type="text" name="mail_new" maxlength="30" style="width:300px; height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F ">
	</td>
</tr>
<tr>
	<td width="50" align="right">
	Тема сообщения
	</td>
	<td>
	<textarea style="width:300px; height:50px; font-family:tahoma; font-size:11px ; color:#4F4F4F " name="tema_new" maxlength="60"></textarea><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0">
	</td>
</tr>
<tr bgcolor="ffffff">
	<td align="right">
	Отзыв
	</td>
	<td valign="top">
	<textarea style="width:300px; height:150px; font-family:tahoma; font-size:11px ; color:#4F4F4F " name="otsiv_new" maxlength="100" ></textarea><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0">
	</td>
</tr>
<tr>
    <td colspan="2" align="center">
	<DIV class="gbook_otvet"><IMG height=16 alt="" hspace=5 src="images/shop/comment.gif" width=16 align=absMiddle border=0>Данные, отмеченные <B>флажками</B> обязательны для заполнения. Отзыв будет размещен только после проверки модератором.<br>
<font color="#FF0000"><strong>@Error@</strong></font>
</DIV>
<p><br></p>
<table>
<tr>
	<td><img src="phpshop/captcha.php" alt="" border="0"></td>
	<td>Введите код, указанный на картинке<br><input type="text" name="key" style="width:220px;"><img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"></td>
</tr>
</table>
<p><br></p>
	<input type="Hidden" name="send_gb">
	<table align="center">
	
<tr>
<td>
	<img src="images/shop/brick_error.gif" alt="" width="16" height="16" border="0">
	<a href="javascript:forma_gbook.reset();" class="standart"><u class=style1>Очистить форму</u></a></td>
<td width="20"></td>
	<td><img src="images/shop/brick_go.gif" alt="" width="16" height="16" border="0">
	<a href="javascript:Fchek();" class="standart"><u class=style1>Добавить отзыв</u></a></td>
	
	
</tr>
</table>
	<input type="hidden" name="send_gb" value="ok" >
    </td>
</tr>
</form>
</table>
