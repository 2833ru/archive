<font color="#FF0000">@usersError@</font>

<form method="post" name="user_forma">
<table width="238" height="84" border="0">
  <tr>
  	<td width="1">&nbsp;</td>
    <td colspan="3"><input type="text" value="@UserLogin@" name="login" class="topInputText" style="width:133px" /></td>
    <td width="87"><a href="/users/register.html"><u>Регистрация</u></a></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
    <td colspan="3"><input type="password" value="@UserPassword@" name="password" class="topInputText" style="width:133px" /></td>
    <td><a href="/users/sendpassword.html"><u>Забыли пароль?</u></a></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
    <td width="15" align="left"><input type="checkbox" value="1" name="safe_users" @UserChecked@/></td>
    <td width="50">Запомнить</td>
    <td width="45"><input type="button" value="Войти" onclick="ChekUserForma()" class="topInputSubmit1"></td>
    <td><input type="hidden" value="1" name="user_enter"></td>
  </tr>
</table></form>