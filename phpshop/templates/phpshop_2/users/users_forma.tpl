<div style="background:#efefef; width:215px; height:156px; margin-top:-38px; position:relative"><table border="0" style="margin:0px 0px 0px 9px" >
<form method="post" name="user_forma">
<tr>
	<td  colspan="2" valign="top" ><table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" ><div style=" color:#3f3f3f; text-transform:uppercase; font-weight:bold; padding:11px 0px 5px 2px">Авторизация</div></td>
    <td valign="top" style="padding:11px 0px 5px 25px" ><a class="login-form" href="/users/register.html" >Регистрация</a></td>
  </tr>
</table>
</td>
</tr>
<tr>
	
	<td colspan="2" style="padding:22px 0px 1px 3px"><input type="text" onFocus="this.value=''" name="login" value="@UserLogin@" style="font-size:11px; color:#7f7f7f; font-weight:bold; padding:4px 0px 0px 10px;width:186px; height:23px ;border-top:1px solid #cdcdcd;border-left:1px solid #cdcdcd;border-bottom:1px solid #f4f4f4;border-right:1px solid #f4f4f4; " title="Логин"></td>

</tr>
<tr>

	<td style="padding-left:3px" width="118"><input type="password" name="password" onFocus="this.value=''" value="@UserPassword@" style="width:118px; height:23px; border-top:1px solid #cdcdcd;border-left:1px solid #cdcdcd;border-bottom:1px solid #f4f4f4;border-right:1px solid #f4f4f4;padding:5px 0px 0px 10px;" title="Пароль"></td>
	<td class="login-form" ><input type="image" src="images/enter.gif" width="66" height="26" onclick="ChekUserForma()" hspace="2" ><input type="hidden" value="1" name="user_enter">
</td>
</tr>
<tr>
	<td colspan="2" class="login-form" valign="top"><table style="margin:10px 0px 0px 0px"  border="0" cellpadding="0" cellspacing="0">
  <tr>

    <td   align="right"><input type="checkbox" value="1" name="safe_users" @UserChecked@></td>
    <td   align="left"  class="login-form" style="padding-right:25px">Запомнить</td>
    <td nowrap="nowrap"   ><a class="login-form" href="/users/sendpassword.html">Забыли пароль?</a></td>
  </tr>
</table>
	
	</td>
</tr>
</form>
</table></div>
