<div id="setUser" class="modal">
            <header class="bar bar-nav">
                <a class="icon icon-close pull-right" href="#setUser" onclick="modal_off(this.hash)"></a>
                <h1 class="title">Авторизация</h1>
            </header>

            <div class="content">
                <div class="content-padded">
                    <form method="post" name="user_forma">
                        <p>
                            <input type="text" placeholder="Логин" name="login" required value="@UserLogin@">
                            <input type="password" placeholder="Пароль" name="password" required value="@UserPassword@">
                            <input type="hidden" value="1" name="safe_users">
                            <input type="hidden" value="1" name="user_enter">
                        </p>
                        <button class="btn btn-positive btn-block"><span class="icon icon-home"></span> Вход</button>
                        
                    </form>
                 <!--   <button class="btn btn-primary btn-block" onclick="window.location.replace('/users/')" ontouchstart="window.location.replace('/users/')"> <span class="icon icon-plus"></span> Регистрация</button> -->
                </div>
            </div>
        </div>