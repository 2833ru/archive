<table width="226" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="images/users_top.gif" alt="" width="226" height="11" /></td>
  </tr>
  <tr>
    <td valign="top" height="128" style="background:#e6e6e6; padding:0px 0px 0px 12px">
<form method="post" name="user_forma">
<table border="0" cellpadding="0"  cellspacing="0" >

<tr>
	<td  colspan="2" valign="top" ><table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" ><div style=" color:#595959; font-size:14px; text-transform:uppercase; font-weight:bold; padding:5px 0px 0px 2px">Авторизация</div></td>
    <td valign="top" style="padding:8px 0px 10px 10px" ><a class="login-form" href="/users/register.html" >Регистрация</a></td>
  </tr>
</table>
</td>
</tr>
<tr>
	
	<td colspan="2" style="padding:8px 0px 4px 3px"><input type="text" onFocus="this.value=''" name="login" value="@UserLogin@" style="font-size:11px; color:#7f7f7f; font-weight:bold; padding:4px 0px 0px 10px;width:186px; height:23px ;border-top:1px solid #cdcdcd;border-left:1px solid #cdcdcd;border-bottom:1px solid #f4f4f4;border-right:1px solid #f4f4f4; "></td>

</tr>
<tr>

	<td style="padding-left:3px" width="118"><input type="password" name="password" onFocus="this.value=''" value="@UserPassword@" style="width:118px; height:23px; border-top:1px solid #cdcdcd;border-left:1px solid #cdcdcd;border-bottom:1px solid #f4f4f4;border-right:1px solid #f4f4f4;padding:5px 0px 0px 10px;"></td>
	<td class="login-form" ><input type="image" src="images/enter.gif" width="64" height="25" onclick="ChekUserForma()" hspace="2" ><input type="hidden" value="1" name="user_enter">
</td>
</tr>
<tr>
	<td colspan="2" class="login-form" valign="top"><table style="margin:10px 0px 0px 0px"  border="0" cellpadding="0" cellspacing="0">
  <tr>

    <td   align="right"><input type="checkbox" value="1" name="safe_users" @UserChecked@></td>
    <td   align="left"  class="login-form" style="padding-right:29px;"><b style="font-weight:normal; background:url(../images/news_bg.gif) bottom left repeat-x; padding-bottom:2px; padding-left:3px">Запомнить</b></td>
    <td nowrap="nowrap"    ><a class="login-form" href="/users/sendpassword.html">Забыли пароль?</a></td>
  </tr>
</table>
	
	</td>
</tr>

</table>
</form>
</td>
  </tr>
  <tr>
    <td><img src="images/users_bot.gif" alt="" width="226" height="12" /></td>
  </tr>
</table>


