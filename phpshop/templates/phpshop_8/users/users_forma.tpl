<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tbody>
        <tr>
            <td align="right" valign="top" width="100%"><span class="usersError">@usersError@</span></td>
            <td align="left" valign="top"><div class="space2">
                    <div class="enter1">
                        <div class="divider"><img src="images/furniture_08.gif"></div>
                        <div class="enter2"><span class="linkNone"><a href="javascript:avtorizationClickOn('userform');" title="вход в кабинет">вход в кабинет</a></span></div>
                        <div class="enter3"><img src="images/furniture_09.gif"></div>
                    </div>
                    <div class="userRoomHidden" id="userform" onMouseOver="avtorizationOn('userform', 'userform');" onMouseOut="avtorizationOff('userform', 'userform');">
                        <div class="userRoomDiv">
                            <form method="post" name="user_forma" class="userRoomForm">
                                <table cellpadding="0" cellspacing="0" border="0" class="userRoomT">
                                    <tbody>
                                        <tr>
                                            <td align="left" valign="top" height="74"><div class="userR1">Вход</div>
                                                <div class="userR2">@usersError@</div>
                                                <div class="userR3"></div></td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top" height="60"><div class="user_Ed1">Логин:</div>
                                                <div class="user_Ed2">
                                                    <div class="user_Ed3">
                                                        <input type="text" class="user_enter" name="login" value="@UserLogin@">
                                                    </div>
                                                </div></td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top" height="52"><div class="user_Ed1">Пароль:</div>
                                                <div class="user_Ed2">
                                                    <div class="user_Ed3">
                                                        <input type="password" class="user_enter" name="password" value="@UserPassword@">
                                                    </div>
                                                </div></td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top" height="41"><div class="userR4"><a href="javascript:ChekUserForma();avtorizationClickOff('userform');"><img src="images/furniture_12.gif" border="0"></a></div>
                                                <div class="userR5">
                                                    <input type="checkbox" value="1" name="safe_users" @UserChecked@>
                                                </div>
                                                <div class="userR6"><span class="user_text">запомнить</span></div></td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top"><div class="userR7"><span class="user_text"><a href="/users/register.html" title="регистрация">регистрация</a></span></div>
                                                <div class="userR8"><span class="user_text"><a href="/users/sendpassword.html" title="забыли пароль?">забыли пароль?</a></span></div></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <input type="hidden" value="1" name="user_enter">
                            </form>
                        </div>
                    </div>
                </div></td>
        </tr>
    </tbody>
</table>