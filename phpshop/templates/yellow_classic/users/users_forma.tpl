<table >
<form method="post" name="user_forma">
<tr>
    <td class="white">Логин:</td>
    <td><input type="text" name="login" value="@UserLogin@" style="width:100px"></td>
    <td><a href="/users/register.html">Регистрация</a></td>
</tr>
<tr>
    <td class="white">Пароль:</td>
    <td><input type="password" name="password" value="@UserPassword@" style="width:100px"></td>
    <td><a href="/users/sendpassword.html" >Забыли пароль?</a></td>
</tr>
<tr>
    <td colspan="3" class="white">
    <input type="checkbox" value="1" name="safe_users" @UserChecked@>Запомнить данные&nbsp;&nbsp;&nbsp;
    <input type="button" value="Войти" onclick="ChekUserForma()" class="but">
    <input type="hidden" value="1" name="user_enter">
    </td>
</tr>
</form>
</table>