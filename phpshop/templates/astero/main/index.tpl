<!DOCTYPE html>
<html lang="@lang@">
    <head>
        <meta charset="@charset@">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@pageTitl@</title>
        <meta name="description" content="@pageDesc@">
        <meta name="keywords" content="@pageKeyw@">
        <meta name="copyright" content="@pageReg@">
        <link rel="apple-touch-icon" href="@icon@">
        <link rel="icon" href="@icon@" type="image/x-icon">
        <link rel="mask-icon" href="@icon@" >
        
         <!-- OpenGraph -->
        <meta property="og:title" content="@ogTitle@">
        <meta property="og:image" content="http://@serverName@@ogImage@">
        <meta property="og:url" content="http://@ogUrl@">
        <meta property="og:type" content="website">
        <meta property="og:description" content="@ogDescription@">

        <!-- Preload -->
        <link rel="preload" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bootstrap.min.css" as="style">
        <link rel="preload" href="@pageCss@" as="style">
        <link rel="preload" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/@astero_theme@.css" as="style">
         <link rel="preload" href="//fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap&subset=cyrillic"  as="font" type="font/woff2" crossorigin>

        <!-- Bootstrap -->
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body id="body" data-dir="@ShopDir@" data-path="@php echo $GLOBALS['PHPShopNav']->objNav['path']; php@" data-id="@php echo $GLOBALS['PHPShopNav']->objNav['id']; php@" data-token="@dadataToken@">

        <!-- Template -->
        <link href="@pageCss@" rel="stylesheet">
        <link href="//fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap&subset=cyrillic" rel="stylesheet">

        <!-- Theme -->
        <link id="bootstrap_theme" data-name="@php echo $_SESSION['skin']; php@" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/@astero_theme@.css" rel="stylesheet">

        <!-- Header Section Starts -->
        <header id="header-area">
            <!-- Nested Container Starts -->
            <div class="container">
                <!-- Header Top Starts -->
                <div class="header-top">
                    <!-- Row Starts -->
                    <div class="row">
                        <!-- Header Links Starts -->
                        <div class="col-sm-12 col-xs-12 col-md-8">
                            <div class="header-links header-color">
                                <ul class="nav navbar-nav pull-left">
                                    @wishlist@
                                    <li>
                                        <a class="hidden-xs hidden-sm link" href="/compare/">                                    
                                            <i class="icon-sliders" ></i><span class="">{Сравнить} (<span id="numcompare">@numcompare@</span>)</span>
                                        </a>
                                        <a href="/compare/" class="btn btn-main btn-sm hidden-md hidden-lg">
                                            <i class="icon-sliders" aria-hidden="true"></i>
                                            {Сравнить} (<span id="numcompare">@numcompare@</span>)
                                        </a>
                                    </li>
                                    @usersDisp@
                                </ul>
                            </div>
                        </div>
                        <!-- Header Links Ends -->
                        <!-- Currency & Languages Starts -->
                        <div class="col-sm-4 col-md-4 hidden-xs hidden-sm">
                            <div class="pull-right text-right">                           
                                <!-- Currency Starts -->
                                <div class="btn-group header-valuta-disp-wrapper header-color">
                                    <h4><i class="icon-phone" aria-hidden="true"></i> @telNumMobile@</h4>
                                    @telNum2@<br>@workingTime@
                                </div>
                                <!-- Currency Ends -->                      
                            </div>
                        </div>
                        <!-- Currency & Languages Ends -->
                    </div>
                    <!-- Row Ends -->
                </div>
                <!-- Header Top Ends -->
                <!-- Main Header Starts -->
                <div class="main-header">
                    <!-- Row Starts -->
                    <div class="row">
                        <div class="col-md-12 hidden-xs hidden-sm">
                            <div class="returncall-wrapper returncall-desctop header-links pull-right header-color">
                                @returncall@
                            </div>
                        </div>
                        <!-- Search Starts -->
                        <div class="col-sm-3 hidden-xs">
                            <form id="search_form" action="/search/" role="search" method="post" class="header-color">
                                <div class="input-group">
                                    <input class="form-control input-lg" name="words" maxlength="50" id="search"  placeholder="{Искать}..." required="" type="search" data-trigger="manual" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true"  data-content="">
                                    <span class="input-group-btn">
                                        <button class="btn btn-lg" type="submit">
                                            <i class="icon-search-1"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <!-- Search Ends -->
                        <!-- Logo Starts -->
                        <div class="col-md-6 col-sm-5 col-xs-12">
                            <div id="logo">
                                <a href="/">
                                    <img src="@logo@" alt="@name@" class="img-responsive" /></a>
                            </div>
                            <div class="returncall-wrapper header-links hidden-md hidden-lg header-color">
                                @returncall@
                            </div>
                        </div>
                        <!-- Logo Starts -->
                        <!-- Shopping Cart Starts -->
                        <div class="col-md-3 col-sm-4 col-xs-12 hidden-xs">
                            <div id="cart" class="btn-group btn-block header-color">
                                <a id="cartlink" type="button"  href="/order/" class="btn btn-block btn-lg dropdown-toggle" data-trigger="hover" data-container="body"  data-placement="bottom" data-html="true" data-url="/order/"  data-content='@visualcart@'>
                                    <i class="icon-basket"></i>
                                    <span>{Корзина}:</span> 
                                    <span id="cart-total"><span><span id="num">@num@</span>{шт.} - </span><span id="sum"> @sum@</span> <span class="rubznak">@productValutaName@</span></span>
                                </a>
                                @visualcart@
                            </div>
                        </div>
                        <!-- Shopping Cart Ends -->
                    </div>
                    <!-- Row Ends -->
                </div>
                <div class="visible-xs col-xs-12 text-center">@sticker_social@</div>
                <!-- Main Header Ends -->
            </div>
            <!-- Nested Container Ends -->

            <!-- Header Area Background Block Starts -->
            <div class="header-area-background-block"></div>
            <!-- Header Area Background Block Ends -->
        </header>
        <!-- Header Section Ends -->
        <!-- Main Menu Starts -->
        <nav id="main-menu" class="navbar" role="navigation">
            <!-- Nested Container Starts -->
            <div class="container">
                <!-- Nav Header Starts -->
                <div class="navbar-header">

                    <a class="navbar-brand visible-xs pull-right" href="tel:@telNumMobile@">
                        <span class="glyphicon glyphicon-phone"></span> @telNumMobile@
                    </a>

                    <button type="button" class="btn btn-navbar navbar-toggle main-menu-button" data-toggle="collapse" data-target=".navbar-cat-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <i class="icon-menu"></i>
                    </button>
                </div>
                <!-- Nav Header Ends -->
                <!-- Navbar Cat collapse Starts -->
                <div class="collapse navbar-collapse navbar-cat-collapse header-menu-wrapper">
                    <div class="row">
                        <ul class="nav navbar-nav main-navbar-top">
                            <li class="main-navbar-top-catalog hidden-xs">
                                <a href="javascript:void(0);" id="nav-catalog-dropdown-link" class="nav-catalog-dropdown-link" aria-expanded="false">{Каталог}
                                </a>
                                <ul class="main-navbar-list-catalog-wrapper fadeIn animated">
                                    @leftCatal@
                                </ul>
                            </li>
                           
							 @topBrands@
                            @topcatMenu@
                            @topMenu@
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Navbar Cat collapse Ends -->
            </div>
            <!-- Nested Container Ends -->
        </nav>
        <!-- Main Menu Ends -->
        <!-- Slider Section Starts -->
        <!-- Nested Container Starts -->
        <!-- Carousel Starts -->
        <div class="slider hidden-xs">
            <div class="container">
                <div class="row">
                    @imageSlider@
                </div>
            </div>
        </div>

        <div class="slider col-xs-12 hidden-lg hidden-md hidden-sm">
            <div class="banner text-center hidden-lg hidden-md hidden-sm">
                @sticker_mobile_slider@
            </div>

            <!-- Carousel Ends -->
            <!-- Nested Container Ends -->
        </div>
        <!-- Slider Section Ends -->
        <!-- Main Container Starts -->
        <div class="main-container container">
            <div class="page-header product-head">
                <h2>@mainContentTitle@</h2>
            </div>
            <div >@mainContent@</div>
            <!-- Banners Starts -->
            <div class="top-col-banners">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="list-unstyled">
                            @sticker_banner@
                        </ul>
                    </div>
                </div>

            </div>
            <!-- Banners Ends --> 

            <!-- Featured Products Starts -->
            <section class="products-list @php __hide('specMainIcon'); php@">
                <!-- Heading Starts -->
                <h2 class="product-head page-header"><a href="/newtip/" title="{Все новинки}">{Новинки}</a></h2>
                <!-- Heading Ends -->
                <!-- Products Row Starts -->
                <!-- Product Starts -->
                <div class="new-product-list">
                    @specMainIcon@
                </div>
                <!-- Product Ends -->
                <!-- Products Row Ends -->
            </section>
            <!-- Featured Products Ends -->

            <!-- Latest Products Starts -->
            <section class="products-list @php __hide('specMain'); php@">         
                <div class="container">
                    <!-- Heading Starts -->
                    <h2 class="product-head page-header"><a href="/spec/" title="{Все спецпредложения}">{Спецпредложения}</a></h2>
                    <!-- Heading Ends -->
                    <!-- Products Row Starts -->
                    <div class="spec-main-list">
                        @specMain@
                    </div>
                    <!-- Products Row Ends -->
                </div>
            </section>
            <!-- Latest Products Ends -->
            <div class="row">@leftCatalTable@</div>

            <!-- News Starts -->
            <h2 class="product-head page-header @php __hide('miniNews'); php@"><a href="/news/" title="{Все новости}">{Новости}</a></h2>
            <div class="news-list">
                <div class="row">
                    @miniNews@
                </div>                
            </div>
            <!-- News Ends -->

        </div>
        <!-- Main Container Ends -->


        <!-- toTop -->
        <div class="visible-lg visible-md">
            <a href="#" id="toTop"><span id="toTopHover"></span>{Наверх}</a>
        </div>
        <!--/ toTop -->

        <!-- Footer Section Starts -->
        <footer class="visible-sm visible-md visible-lg" id="footer-area">
            <!-- Footer Links Starts -->
            <div class="footer-links">
                <!-- Container Starts -->
                <div class="container">
                    <!-- Information Links Starts -->
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <h5>{Информация}</h5>
                        <ul>
                            @bottomMenu@                        </ul>
                    </div>
                    <!-- Information Links Ends -->
                    <!-- My Account Links Starts -->
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <h5>{Личный кабинет}</h5>
                        <ul>
                            <li><a href="/users/">@UsersLogin@</a></li>
                            <li><a href="/users/order.html">{Отследить заказ}</a></li>
                            <li><a href="/users/notice.html">{Уведомления о товарах}</a></li>
                            @php if($_SESSION['UsersId']) echo '<li><a href="/users/message.html">{Связь с менеджерами}</a></li>
                            <li><a href="?logout=true">{Выйти}</a></li>'; php@
                        </ul>
                    </div>
                    <!-- My Account Links Ends -->
                    <!-- Customer Service Links Starts -->
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <h5>{Навигация}</h5>
                        <ul>
                            <li><a href="/price/" title="{Прайс-лист}">{Прайс-лист}</a></li>
                            <li><a href="/news/" title="{Новости}">{Новости}</a></li>
                            <li><a href="/gbook/" title="{Отзывы}">{Отзывы}</a></li>
                            <li><a href="/map/" title="{Карта сайта}">{Карта сайта}</a></li>
                            <li><a href="/forma/" title="{Форма связи}">{Форма связи}</a></li>
                        </ul>
                    </div>
                    <!-- Customer Service Links Ends -->
                    <!-- Contact Us Starts -->
                    <div class="col-md-3 col-sm-8 col-xs-12">
                        <h5>{Контакты}</h5>
                        <ul>
                        <li class="footer-map">@streetAddress@</li>
                        <li class="footer-email">@adminMail@</li>
                        <li class="footer-email">@telNum@<br>
                         @telNum2@<br>
                         @workingTime@</li>                              
                    </ul>
                    </div>
                    <!-- Contact Us Ends -->
                </div>
                <!-- Container Ends -->
            </div>
            <!-- Footer Links Ends -->
            <!-- Copyright Area Starts -->
            <div class="copyright">
                <!-- Container Starts -->
                <div class="container">
                    <div class="pull-right">@button@</div>
                    <p itemscope itemtype="http://schema.org/Organization">&copy; <span itemprop="name">@company@</span> @year@, {Тел}: <span itemprop="telephone">@telNum@</span>, <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">{Адрес}: <span itemprop="streetAddress">@streetAddress@</span></span><span itemprop="email" class="hide">@adminMail@</span></p>
                </div>
                <!-- Container Ends -->
            </div>
            <!-- Copyright Area Ends -->
        </footer>
        <!-- Footer Section Ends -->

        <!-- jQuery -->
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery-1.11.0.min.js"></script>

        @editor@

        <!-- Fixed mobile bar -->

          <nav class="navbar navbar-default navbar-fixed-bottom bar bar-tab visible-xs visible-sm" role="navigation">
        <a class="tab-item" href="/">
          
            <span class="tab-label">{Домой}</span>
        </a>
        <a class="tab-item @user_active@" @user_link@ data-target="#userModal">
          
            <span class="tab-label">{Кабинет}</span>
        </a>
        <a class="tab-item @cart_active@" href="/order/" id="bar-cart">
            <span class="badge badge-positive" id="mobilnum">@cart_active_num@</span>
            <span class="tab-label">{Корзина}</span>
        </a>
        <a class="tab-item" href="#" data-toggle="modal" data-target="#searchModal">
            
            <span class="tab-label">{Поиск}</span>
        </a>
    </nav>
        <!--/ Fixed mobile bar -->
        <!-- Notification -->
        <div id="notification" class="success-notification" style="display: none;">
            <div  class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                <span class="notification-alert"> </span>
            </div>
        </div>
        <!--/ Notification -->

        <!-- Модальное окно авторизации-->
        <div class="modal fade bs-example-modal-sm" id="userModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm auto-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">{Авторизация}</h4>
                        <span id="usersError" class="hide">@usersError@</span>
                    </div>
                    <form role="form" method="post" name="user_forma">
                        <div class="modal-body">
                            <div class="form-group">

                                <input type="email" name="login" class="form-control" placeholder="Email" required="" value="@UserLogin@">
                                <span class="glyphicon glyphicon-remove form-control-feedback hide" aria-hidden="true"></span>
                                <br>

                                <input type="password" name="password" class="form-control" placeholder="{Пароль}" required="" value="@UserPassword@">
                                <span class="glyphicon glyphicon-remove form-control-feedback hide" aria-hidden="true"></span>
                            </div>
                            <div class="flex-row">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="1" name="safe_users" @UserChecked@> {Запомнить}
                                    </label>
                                </div>
                                <a href="/users/sendpassword.html" class="pass">{Забыли пароль}</a>
                            </div>

                            @facebookAuth@ @twitterAuth@
                        </div>
                        <div class="modal-footer flex-row">

                            <input type="hidden" value="1" name="user_enter">
                            <button type="submit" class="btn btn-primary">{Войти}</button>
                            <a href="/users/register.html" >{Зарегистрироваться}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--/ Модальное окно авторизации-->

        <!-- Модальное окно мобильного поиска -->
        <div class="modal fade bs-example-modal-sm" id="searchModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">{Поиск}</h4>
                    </div>
                    <div class="modal-body">
                        <form  action="/search/" role="search" method="post">
                            <div class="input-group">
                                <input name="words" maxlength="50" class="form-control" placeholder="{Искать}.." required="" type="search">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                </span>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--/ Модальное окно мобильного поиска -->

        <!-- Модальное окно returncall-->
        <!-- Модальное окно returncall-->
        <div class="modal fade bs-example-modal-sm return-call" id="returnCallModal" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">{Обратный звонок}</h4>
                    </div>
                    <form role="form" method="post" name="user_forma" action="@ShopDir@/returncall/">
                        <div class="modal-body">

                            <div class="form-group">

                                <input type="text" name="returncall_mod_name" class="form-control" placeholder="{Имя}" required="">
                            </div>
                            <div class="form-group">

                                <input type="text" name="returncall_mod_tel" class="form-control phone" placeholder="{Телефон}" required="">
                            </div>
                            <div class="form-group">

                                <input class="form-control" type="text" placeholder="{Время звонка}" name="returncall_mod_time_start">
                            </div>
                            <div class="form-group">

                                <textarea class="form-control" name="returncall_mod_message" placeholder="{Сообщение}"></textarea>
                            </div>
                            <div class="form-group">

                                <p class="small">
                                    <input type="checkbox" value="on" name="rule" class="req" checked="checked"> 
                                    {Я согласен}  <a href="/page/soglasie_na_obrabotku_personalnyh_dannyh.html" alt="{Согласие на обработку персональных данных}">{на обработку моих персональных данных}</a> 
                                </p>

                            </div>

                            @returncall_captcha@

                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="returncall_mod_send" value="1">
                            <button type="button" class="btn btn-default" data-dismiss="modal">{Закрыть}</button>
                            <button type="submit" class="btn btn-primary">{Заказать звонок}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Согласие на использование cookie  -->
        <div class="cookie-message hide"><p></p><a href="#" class="btn btn-default btn-sm">Ok</a></div>

        <!-- JQuery Plugins  -->
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/fontello.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/jquery.bxslider.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/jquery-ui.min.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bootstrap-select.min.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/responsive.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/animate.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bar.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/suggestions.min.css" rel="stylesheet">
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/bootstrap.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.lazyloadxt.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/astero.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/bootstrap-select.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.suggestions.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@/js/phpshop.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery-ui.min.js"></script>
        <script src="java/jqfunc.js"></script>
        <script src="phpshop/locale/@php echo $_SESSION['lang']; php@/template.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.maskedinput.min.js"></script>
        <script  src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/flipclock.min.js"></script>
        <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/flipclock.css">
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@/js/jquery.cookie.js"></script>
       
        @visualcart_lib@
