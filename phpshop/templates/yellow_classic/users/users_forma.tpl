<table >
<form method="post" name="user_forma">
<tr>
	<td>Логин:</td>
	<td><input type="text" name="login" value="@UserLogin@" style="width:100px"></td>
	<td><a href="/users/register.html" class=b>Регистрация</a></td>
</tr>
<tr>
	<td>Пароль:</td>
	<td><input type="password" name="password" value="@UserPassword@" style="width:100px"></td>
	<td><a href="/users/sendpassword.html" class=b>Забыли пароль?</a></td>
</tr>
<tr>
	<td colspan="3" >
	<input type="checkbox" value="1" name="safe_users" @UserChecked@>Запомнить данные&nbsp;&nbsp;&nbsp;
	<input type="button" value="Войти" onclick="ChekUserForma()" class="but">
	<input type="hidden" value="1" name="user_enter">
	</td>
</tr>
</form>
</table>