<div class="plashka_zag_3">Авторизация</div>
<div class="plashka_center_link_3"><a href="/users/register.html" title="Регистрация">регистрация</a></div>
<div id="usersdisp">
<div class="user_error">@usersError@</div>
<form method="post" name="user_forma">
	<div><input type="text" title="Логин" class="user" name="login" value="логин" onfocus="this.value=''"></div>
	<div class="user_pad_2"><input class="user_2" type="password" title="Пароль" name="password" value="пароль" onfocus="this.value=''"><span class="user_pad_3"></span>
	<input type="image" src="images/but_enter.gif" onclick="ChekUserForma()" title="Войти" id="user_but"><input type="hidden" value="1" name="user_enter"></div>
	<input type="checkbox" value="1" name="safe_users" @UserChecked@>запомнить<span class="pad_1"></span>
	<a href="/users/sendpassword.html" title="Забыли пароль?">забыли пароль?</a>
	<input type="hidden" value="1" name="user_enter">
</form>
</div>