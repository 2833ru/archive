<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="windows-1251">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@pageTitl@</title>
        <meta name="description" content="@pageDesc@">
        <meta name="keywords" content="@pageKeyw@">
        <meta name="copyright" content="@pageReg@">
        <meta content="General" name="rating">
        <meta name="ROBOTS" content="ALL">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="icon" href="/favicon.ico"> 

        <!-- Bootstrap -->
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bootstrap.min.css" rel="stylesheet">

    </head>
    <body id="body" data-dir="@ShopDir@" data-path="@php echo $GLOBALS['PHPShopNav']->objNav['path']; php@" data-id="@php echo $GLOBALS['PHPShopNav']->objNav['id']; php@" data-subpath="@php echo $GLOBALS['PHPShopNav']->objNav['name']; php@" data-token="@dadataToken@">

        <!-- Fonts -->
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/font-awesome.min.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/iconfont.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bootstrap.offcanvas.min.css" rel="stylesheet">


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- jQuery -->
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery-1.11.0.min.js"></script>

        <!-- jQuery Plugins -->
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/owl.carousel.min.css" rel="stylesheet">
        <script src="java/jqfunc.js"></script>
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/jquery.bxslider.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/jquery-ui.min.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bootstrap-select.min.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bar.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/suggestions.min.css" rel="stylesheet">

        <!-- Template -->
        <link href="@pageCss@" rel="stylesheet">

        <!-- Theme -->
        <link id="bootstrap_theme" data-name="@php echo $_SESSION['skin']; php@" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/@unishop_theme@.css" rel="stylesheet">

        <div class="navbar-offcanvas" id="js-bootstrap-offcanvas">
            <ul class="offcanvas-list">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{Каталог} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        @menuCatal@
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="feather iconz-user"></i> {Кабинет }<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/users/order.html">{Отследить заказ}</a></li>
                        <li><a href="/users/notice.html">{Уведомления о товарах}</a></li>
                        <li><a href="/users/message.html">{Связь с менеджерами}</a></li>
                        @php if($_SESSION['UsersId']) echo '<li><a href="?logout=true">{Выйти}</a></li>'; php@
                    </ul>
                </li>
                @topMenu@
                <li class=""><a href="/news/">{Новости}</a></li>
                <li class=""><a href="/gbook/">{Отзывы}</a></li>
                <li class=""><a href="/price/">{Прайс-лист}</a></li>
                <li class=""><a href="/map/">{Карта сайта}</a></li>
            </ul>
        </div>
        <!-- Header Section Starts -->
        <header>
            <div class="header-top">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 top-mobile-fix">
                            <a class="header-top-link header-link-contact" href="mailto:@adminMail@"><i class="fa fa-envelope-o" aria-hidden="true"></i> @adminMail@</a>
                            <a class="header-top-link header-link-contact" href="tel:@telNum@"><i class="fa fa-bell-o" aria-hidden="true"></i> @telNum@</a>
                            <!-- Social Button -->
                            <a class="social-button hidden-xs hidden-sm header-top-link" href="#" title="{Поделится в} Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            <a class="social-button hidden-xs hidden-sm header-top-link" href="#" title="{Поделится в} Контакте"><i class="fa fa-vk" aria-hidden="true"></i></a>
                            <a class="social-button hidden-xs hidden-sm header-top-link" href="#" title="{Поделится в} Одноклассники"><i class="fa fa-odnoklassniki" aria-hidden="true"></i></a>
                            <!--/ Social Button -->

                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 top-mobile-fix">
                            <div class="header-wishlist">
                                <a class="header-link-color link" href="/compare/">
                                    <span class="hidden-sm hidden-xs">
                                        <i class="fa fa-bar-chart-o"></i> {Сравнить} (<span id="numcompare">@numcompare@</span>)<span id="wishlist-total" ></span>
                                </span>
                                </a>
                                @wishlist@
                            </div>
                            <div class="header-top-right">
                                @returncall@
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-bottom">
                <div class="container header-container-fix">
                    <div class="row">
                        <div class="col-md-12 ">
                            <form id="search_form" action="/search/" role="search" method="post" class="header-search-form">
                                <input id="search" class="form-control input-lg" name="words" maxlength="50"  placeholder="{Поиск}..." required="" type="search" data-trigger="manual" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true"  data-content="">
                                <button class="" type="submit">
                                    <i class="feather iconz-search"></i>
                                </button>
                                <i class="search-close feather iconz-x"></i>
                            </form>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 bottom-mobile-fix">
                            <div id="logo">
                                <a href="/">
                                    <img src="@logo@" alt="@name@" title="@name@" class="img-responsive" />
                                </a>
                            </div>
                            <button type="button" class="hidden-md hidden-lg navbar-toggle feather iconz-menu modile-cat-open offcanvas-toggle" data-toggle="offcanvas" data-target="#js-bootstrap-offcanvas">
                            </button>
                        </div>
                        <div class="col-md-6 col-lg-7 hidden-xs hidden-sm header-menu-wrapper">
                            <div class="row">
                                <ul class="nav navbar-nav main-navbar-top">
                                    <li class="catalog-menu">
                                        <a id="nav-catalog-dropdown-link" class="nav-catalog-dropdown-link" aria-expanded="false">{Каталог}
                                        </a>
                                        <ul class="main-navbar-list-catalog-wrapper">
                                            @leftCatal@
                                        </ul>
                                    </li>
                                    @topBrands@
                                    @topMenu@
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-7 col-xs-12 col-md-4 col-lg-3 hidden-xs hidden-sm bottom-mobile-fix">
                            <div id="cart" class="btn-group header-color">
                                <a id="cartlink"  class="btn btn-block btn-lg dropdown-toggle" href="/order/">
                                    <span id="cart-total">
                                        <i class="feather iconz-trash"></i>
                                        <span class="count" id="num">@num@</span>
                                        <span class="sub-total">
                                            <span id="sum"> @sum@</span> <span class="rubznak">@productValutaName@</span>
                                        </span>
                                    </span>
                                </a>
                                @visualcart@
                            </div>
                            <div class="header-account">
                                @usersDisp@
                            </div>
                            <div class="search-open-button">
                                <i class="feather iconz-search"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="header-area" class="header-area-background-block"></div>
            </div>
        </header>
        <!-- Header Section Ends -->
        <div class="secondary-nav">
            <div class="container">
                <h1 class="shop-page-main-title"></h1>
                <ol class="breadcrumb">
                    @breadCrumbs@
                </ol>
            </div>
        </div>
        <!-- Main Container Starts -->
        <div class="middle-content shop-page  main-color-text">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-md-3 main-sidebar-left">
                        <div class="order-page-sidebar-user-block hidden-xs hidden-sm">
                            <h5 class="user-title">{Личный кабинет}</h5>
                            <ul class="user-list">
                                <li><a href="/users/">@UsersLogin@</a></li>
                                <li><a href="/users/order.html">{Отследить заказ}</a></li>
                                <li><a href="/users/notice.html">{Уведомления о товарах}</a></li>
                                <li><a href="/users/message.html">{Связь с менеджерами}</a></li>
                                @php if($_SESSION['UsersId']) echo '<li><a href="?logout=true">{Выйти}</a></li>'; php@
                            </ul>
                        </div>
                        <h3 class="side-heading hidden-xs hidden-sm">{Категории}</h3>
                        <ul class="list-group sidebar-nav hidden-xs hidden-sm">
                            @leftCatal@
                        </ul>
                        <div class="hide" id="faset-filter">
                            <h3 class="side-heading filter-title">{Фильтр товаров }<a href="?" id="faset-filter-reset" data-toggle="tooltip" data-placement="top" title="{Сбросить фильтр}"><span class="glyphicon glyphicon-remove"></span></a></h3>
                            <div class="list-group filter-body-fix">
                                <div id="faset-filter-body">{Загрузка}...</div>
                                <div id="price-filter-body">
                                    <h4>{Цена}</h4>
                                    <form method="get" id="price-filter-form">
                                        <div class="row">
                                            <div class="col-md-6" id="price-filter-val-min">
                                                <span>{от}</span>
                                                <input type="text" class="form-control input-sm" name="min" value="@price_min@" > 
                                            </div>
                                            <div class="col-md-6" id="price-filter-val-max">
                                                <span>{до}</span>
                                                <input type="text" class="form-control input-sm" name="max" value="@price_max@"> 
                                            </div>
                                        </div>
                                        <p></p>
                                        <div id="slider-range"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="menu-fix hidden-sm hidden-xs">
                            <h3 class="side-heading">{Полезная информация}</h3>
                            <div class="list-group sidebar-nav">
                                @pageCatal@
                            </div>
                            @rightMenu@
                            @leftMenu@
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-9 middle-content-fix">
                        @DispShop@
                        @getPhotos@
                    </div>

                    <div class="col-xs-12">
                        <div class="banner-block">@banersDisp@</div>
                    </div>
                    
                    <div class="col-xs-12 @php __hide('now_buying'); php@">
                        <h2 class="main-page-title">@now_buying@</h2>
                        <div class="owl-carousel nowBuy">
                            @nowBuy@
                        </div>
                    </div>
                    <!-- Primary Content Ends -->
                </div>
            </div>
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
                    <!-- Contact Us Starts -->
                    <div class="col-md-3 col-sm-8 col-xs-12">
                        <h5>{Контакты}</h5>
                        <ul>
                            <li class="footer-map">@streetAddress@</li>
                            <li class="footer-email"><a href="mailto:@adminMail@"><i class="fa fa-envelope-o"></i> @adminMail@</a></li>
                            <li class="footer-map">
                                <h4 class="lead">
                                    <a href="tel:@telNum@">Тел: @telNum@</a>
                                </h4>
                            </li>
                        </ul>
                        <div class="footer-social">
                          
                            <!-- Social Button -->
                            <a class="social-button hidden-xs hidden-sm header-top-link" href="#" title="{Поделится в} Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            <a class="social-button hidden-xs hidden-sm header-top-link" href="#" title="{Поделится в} Контакте"><i class="fa fa-vk" aria-hidden="true"></i></a>
                            <a class="social-button hidden-xs hidden-sm header-top-link" href="#" title="{Поделится в} Одноклассники"><i class="fa fa-odnoklassniki" aria-hidden="true"></i></a>
                            <!--/ Social Button -->
                        </div>
                    </div>
                    <!-- Contact Us Ends -->
                    <!-- My Account Links Starts -->
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <h5>{Личный кабинет}</h5>
                        <ul>
                            <li><a href="/users/order.html">{Отследить заказ}</a></li>
                            <li><a href="/users/notice.html">{Уведомления о товарах}</a></li>
                            <li><a href="/users/message.html">{Связь с менеджерами}</a></li>
                            @php if($_SESSION['UsersId']) echo '<li><a href="?logout=true">{Выйти}</a></li>'; php@
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
                    <!-- Information Links Starts -->
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <h5>{Информация}</h5>
                        <ul>
                            @topMenu@
                            <li><a href="/page/politika_konfidencialnosti.html" title="{Политика конфиденциальности}">{Политика конфиденциальности}</a></li>
                        </ul>
                    </div>
                    <!-- Information Links Ends -->
                    <div class="col-xs-12">
                        <div class="footer-layer"></div>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="footer-payment-methods">
                                    <img src="images/payment_methods.png">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <form action="/search/" class="news-subscription-form">
                                    <div class="clearfix">
                                        <div class="main-page-subscrition-form-input">
                                            <input class="form-control" name="words" maxlength="50" id=""  placeholder="{Поиск}..." required="" type="search" data-trigger="manual" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true"  data-content="">
                                        </div>
                                        <button type="submit"><i class="feather iconz-search"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container Ends -->
            </div>
            <!-- Footer Links Ends -->
            <!-- Copyright Area Starts -->
            <div class="copyright">
                <!-- Container Starts -->
                <div class="container">
                    <div class="pull-right">@button@</div>
                    <p itemscope itemtype="http://schema.org/Organization">© <span itemprop="name">@company@</span> @year@, {Тел}: <span itemprop="telephone">@telNum@</span>, <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">{Адрес}: <span itemprop="streetAddress">@streetAddress@</span></span><span itemprop="email" class="hide">@adminMail@</span></p>
                </div>
                <!-- Container Ends -->
            </div>
            <!-- Copyright Area Ends -->
        </footer>
        <!-- Footer Section Ends -->

        @editor@

        <!-- Fixed mobile bar -->
        <div class="bar-padding-fix visible-xs visible-sm"> </div>
        <nav class="navbar navbar-default navbar-fixed-bottom bar bar-tab visible-xs visible-sm" role="navigation">
            <a class="tab-item" href="/">
                <span class="icon icon-home"></span>
                <span class="tab-label">{Домой}</span>
            </a>
            <a class="tab-item @user_active@" @user_link@ data-target="#userModal">
                <span class="icon icon-person"></span>
                <span class="tab-label">{Кабинет}</span>
            </a>
            <a class="tab-item @cart_active@" href="/order/" id="bar-cart">
                <span class="icon icon-download"></span> <span class="badge badge-positive" id="mobilnum">@cart_active_num@</span>
                <span class="tab-label">{Корзина}</span>
            </a>
            <a class="tab-item" href="#" data-toggle="modal" data-target="#searchModal">
                <span class="icon icon-search"></span>
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
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">{Авторизация}</h4>
                        <span id="usersError" class="hide">@usersError@</span>
                    </div>
                    <form role="form" method="post" name="user_forma">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="login" class="form-control" placeholder="Email..." required="">
                                <span class="glyphicon glyphicon-remove form-control-feedback hide" aria-hidden="true"></span>
                            </div>

                            <div class="form-group">
                                <label>{Пароль}</label>
                                <input type="password" name="password" class="form-control" placeholder="{Пароль}..." required="">
                                <span class="glyphicon glyphicon-remove form-control-feedback hide" aria-hidden="true"></span>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="safe_users" @UserChecked@> {Запомнить}
                                </label>
                            </div>

                            @facebookAuth@ @twitterAuth@
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary pull-left">{Войти}</button>
                            <span class="pull-right"><a href="/users/sendpassword.html" class="btn btn-default">{Забыли}?</a>
                            </span>
                            <input type="hidden" value="1" name="user_enter">
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

        <!-- JQuery Plugins  -->
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/bootstrap.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/owl.carousel.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/unishop.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/bootstrap.offcanvas.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/bootstrap-select.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@/js/phpshop.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery-ui.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.ui.touch-punch.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.bxslider.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@/js/jquery.cookie.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.waypoints.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/inview.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.maskedinput.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.suggestions.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.ui.touch-punch.min.js"></script>
        @visualcart_lib@