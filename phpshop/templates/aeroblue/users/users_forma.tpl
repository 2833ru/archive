<font color="#FF0000">@usersError@</font>

<form method="post" name="user_forma">
    <table cellspacing="5">
        <tr>
            <td align="right">	Логин:
            </td>
            <td>									
                <input type="text" class="user" name="login" value="@UserLogin@">
            </td>
            <td><a href="/users/register.html" class="small" title="Регистрация">Регистрация</a>
            </td>
        </tr>
        <tr>
            <td align="right">	Пароль:
            </td>
            <td>									
                <input class="user" type="password" name="password" value="@UserPassword@">
            </td>
            <td><a href="javascript:ChekUserForma()"><img src="images/but_enter.jpg" alt="" width="63" height="21" border="0"></a>
            </td>
        </tr>
        <tr>
            <td>
            </td>
            <td align="right"><a href="/users/sendpassword.html" class="small" title="Забыли пароль?">Забыли пароль?</a>&nbsp;&nbsp;&nbsp;
            </td>
            <td>									
                <input type="checkbox" value="1" name="safe_users" @UserChecked@><b>Запомнить</b>
            </td>
        </tr>
    </table>
    <input type="hidden" value="1" name="user_enter">
</form>