<p> <a href="javascript:void(0)" onclick="document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block'">Войти в личный кабинет</a> &nbsp; или &nbsp; <a href="/users/register.html" title="Регистрация">Регистрация?</a> <span class="rederror">@usersError@</span> </p>
<div >
    <div id="light" class="white_content">
        <div style="position:relative;" class="usbg">
            <form method="post" name="user_forma" action="/users/">
                <table class="user-enter">
                    <tr>
                        <td><table style="margin-top:30px;width:100%;">
                                <tr>
                                    <td><h2>Авторизация</h2></td>
                                    <td style="text-align:right;"><div class="closeX"><a href="javascript:void(0)" onClick="document.getElementById('light').style.display = 'none';
                          document.getElementById('fade').style.display = 'none'">закрыть</a></div></td>
                                </tr>
                            </table></td>
                    </tr>
                    <tr>
                        <td style="height:40px;" ><input type="text" onFocus="" name="login" value="@UserLogin@" placeholder="E-mail">
                        </td>
                    </tr>
                    <tr>
                        <td style="height:37px;" ><input type="password" name="password" onFocus="" value="@UserPassword@"  placeholder="Пароль" >
                        </td>
                    </tr>
                    <tr>
                        <td  ><table style="margin-top:10px;width:100%;">
                                <tr>
                                    <td  ><table>
                                            <tr>
                                                <td  style="width:10px;"><input id="zap" type="checkbox" value="1" name="safe_users" @UserChecked@></td>
                                                <td><label for="zap" style="margin-top: 9px;">Запомнить</label></td>
                                            </tr>
                                        </table></td>
                                    <td  style="text-align:right;">
                                        <input name="button" class="btn" type="submit"  onclick="ChekUserForma()" value="    Войти    "   >
                                        <input type="hidden" value="1" name="user_enter">
                                    </td>
                                </tr>
                            </table>
                            <table style="width:100%;">
                                <tr>
                                    <td  style="height:37px;width:50%;" > @facebookAuth@ @twitterAuth@ </td>
                                    <td style="text-align:right;width:50%;">
                                        <a href="/users/sendpassword.html" class="forg2" >Забыли пароль?</a>
                                    </td>
                                </tr>
                            </table></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
