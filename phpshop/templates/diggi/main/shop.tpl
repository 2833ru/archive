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
    <body id="body" data-dir="@ShopDir@" data-path="@php echo $GLOBALS['PHPShopNav']->objNav['path']; php@" data-id="@php echo $GLOBALS['PHPShopNav']->objNav['id']; php@" data-subpath="@php echo $GLOBALS['PHPShopNav']->objNav['name']; php@">

        <!-- jQuery -->
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery-1.11.0.min.js"></script>


        <!-- jQuery Plugins -->
        <script src="java/jqfunc.js"></script>
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/jquery.bxslider.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/jquery-ui.min.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bootstrap-select.min.css" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bar.css" rel="stylesheet">

        <!-- Template -->
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/animate.css" rel="stylesheet">
        <link href="@pageCss@" rel="stylesheet">
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/responsive.css" rel="stylesheet">

        <!-- Theme -->
        <link id="bootstrap_theme" data-name="@php echo $_SESSION['skin']; php@" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/@diggi_theme@.css" rel="stylesheet">

        <!-- Fonts -->
        <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/font-awesome.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700italic,700,400italic,300italic,300" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->


        <!-- Header Section Starts -->
        <header id="header-area" class="header-wrap inner">
            <!-- Header Top Starts -->
            <div class="header-top">
                <!-- Nested Container Starts -->
                <div class="container">
                    <!-- Row Starts -->
                    <div class="row">
                        <!-- Logo Starts -->
                        <div class="col-md-2 col-sm-12 col-xs-12 wrapper-fix">
                            <div id="logo">
                                <a href="/" title="@name@">
                                    <img src="@logo@" alt="@name@" class="img-responsive" /></a>
                            </div>
                        </div>
                        <!-- Logo Starts -->
                        <!-- Header Links Starts -->
                        <div class="col-sm-12 col-xs-12 col-md-6">
                            <div class="header-links">
                                <ul class="nav navbar-nav pull-left">
                                    <li>
                                        <a class="hidden-xs hidden-sm hidden-md link" href="/">
                                            <i class="fa fa-home" title="Домой"></i>
                                            <span class="hidden-sm hidden-xs">
                                                Домой
                                            </span>
                                        </a>                                       
                                    </li>
                                    @wishlist@
                                    <li>
                                        <a class="hidden-xs hidden-sm link" href="/compare/">
                                            <i class="fa fa-plus" title="Сравнить"></i>
                                            <span class="hidden-sm hidden-xs">Сравнить (<span id="numcompare">@numcompare@</span>)</span>
                                        </a>
                                        <a href="/compare/" class="btn btn-main btn-sm hidden-md hidden-lg">
                                            <i class="fa fa-plus" title="Сравнить"></i>
                                            Сравнить (<span id="numcompare">@numcompare@</span>)
                                        </a>
                                    </li>
                                    @usersDisp@
                                </ul>

                            </div>
                        </div>
                        <!-- Header Links Ends -->
                        <!-- Shopping Cart Starts -->
                        <div class="col-md-4 col-lg-2 visible-md hidden-sm hidden-xs visible-lg">
                            <div id="cart" class="btn-group pull-right">
                                <button id="cartlink" type="button" data-toggle="dropdown" class="btn btn-block btn-lg dropdown-toggle" data-trigger="click" data-container="body"  data-placement="bottom" data-html="true" data-url="/order/" data-content='@visualcart@'>
                                    <i class="fa fa-shopping-cart"></i>
                                    <span id="cart-total"><span><span id="num">@num@</span>шт.</span></span>
                                    <i class="fa fa-caret-down"></i>
                                </button>
                                @visualcart@
                            </div>
                        </div>
                        <!-- Shopping Cart Ends -->
                        <!-- Currency & Languages Starts -->
                        <div class="col-sm-4 col-md-2 hidden-xs hidden-sm hidden-md no-p">
                            <div class="pull-right">                           
                                <!-- Currency Starts -->
                                <div class="btn-group header-valuta-disp-wrapper">
                                    <!--@valutaDisp@-->
                                    <h4><i class="fa fa-phone-square" aria-hidden="true"></i> Тел: @telNumMobile@</h4>
                                </div>
                                <!-- Currency Ends -->                      
                            </div>
                        </div>
                        <!-- Currency & Languages Ends -->
                    </div>
                    <!-- Row Ends -->
                </div>
                <!-- Nested Container Ends -->
            </div>
            <!-- Header Top Ends -->
            <!-- Main Menu Starts -->
            <nav id="main-menu" class="navbar" role="navigation">
                <div class="container">
                    <!-- Nav Header Starts -->
                    <div class="navbar-header">
                        <a class="navbar-brand visible-xs pull-right" href="tel:@telNumMobile@">
                            <span class="glyphicon glyphicon-phone"></span> @telNumMobile@
                        </a>
                        <button type="button" class="btn btn-navbar navbar-toggle main-menu-button" data-toggle="collapse" data-target=".navbar-cat-collapse">
                            <span class="sr-only">Toggle Navigation</span>
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                    <!-- Nav Header Ends -->
                    <!-- Navbar Cat collapse Starts -->
                    <div class="collapse navbar-collapse navbar-cat-collapse">
                        <ul class="nav navbar-nav main-navbar-top">
                            <li class="main-navbar-top-catalog">
                                <a href="#" id="nav-catalog-dropdown-link" class="nav-catalog-dropdown-link" aria-expanded="false">Каталог
                                </a>
                                <ul class="main-navbar-list-catalog-wrapper fadeIn animated">
                                    @leftCatal@
                                </ul>
                            </li>
                            @topBrands@
                            @topMenu@
                        </ul>
                        <form id="search_form" class="navbar-form navbar-right hidden-sm hidden-xs" action="/search/" role="search" method="post">
                            <div class="input-group">
                                <input class="form-control input-lg" name="words" maxlength="50" id="search"  placeholder="Искать..." required="" type="search" data-trigger="manual" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true"  data-content="">
                                <span class="input-group-btn">
                                    <button class="btn btn-lg" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>
                    </div>
                    <!-- Navbar Cat collapse Ends -->
                </div>
            </nav>
            <!-- Main Menu Ends -->
        </header>
        <!-- Header Section Ends -->

        <!-- Breadcrumb Starts -->
        <div class="breadcrumb-wrap">
            <div class="container">
                <!-- Breadcrumb Starts -->
                <ol class="breadcrumb">
                    @breadCrumbs@
                </ol>
                <!-- Breadcrumb Ends -->
            </div>
        </div>
        <!-- Main Container Starts -->
        <div class="main-container container">
            <!-- Nested Row Starts -->
            <div class="row">
                <!-- Sidebar Starts -->
                <div class="col-md-3 visible-lg visible-md" id="sidebar-right">
                    <!-- Categories Links Starts -->
                    <h3 class="side-heading">Категории</h3>
                    <ul class="list-group sidebar-nav">
                        @leftCatal@
                    </ul>
                    <!-- Categories Links Ends -->
                    <!-- Фасетный фильтр -->
                    <div class="hide" id="faset-filter">
                        <h3 class="side-heading">Фильтр товаров</h3>                    
                        <div class="list-group">
                            <div id="faset-filter-body">Загрузка...</div>
                            <div id="price-filter-body">
                                <h4>Цена</h4>
                                <form method="get" id="price-filter-form">
                                    <div class="row">
                                        <div class="col-md-6" id="price-filter-val-min">
                                            <span>от</span>
                                            <input type="text" class="form-control input-sm" name="min" value="@price_min@" > 
                                        </div>
                                        <div class="col-md-6" id="price-filter-val-max">
                                            <span>до</span>
                                            <input type="text" class="form-control input-sm" name="max" value="@price_max@"> 
                                        </div>
                                    </div>
                                    <p></p>
                                    <div id="slider-range"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--/ Фасетный фильтр -->
                    @productDay@
                    <h3 class="side-heading">Полезная информация</h3>
                    <div class="list-group sidebar-nav">
                        @pageCatal@
                    </div>
                    @rightMenu@
                </div>
                <!-- Sidebar Ends -->
                <!-- Primary Content Starts -->
                <div class="col-md-9 col-xs-12">
                    @DispShop@
                </div>
                <!-- Primary Content Ends -->
            </div>
            <!-- Nested Row Ends -->
        </div>
        <!-- Main Container Ends -->

        <!-- toTop -->
        <div class="visible-lg visible-md">
            <a href="#" id="toTop"><span id="toTopHover"></span></a>
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
                        <h5>Информация</h5>
                        <ul>
                            @topMenu@
                        </ul>
                    </div>
                    <!-- Information Links Ends -->
                    <!-- My Account Links Starts -->
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <h5>Личный кабинет</h5>
                        <ul>
                            <li><a href="/users/">@UsersLogin@</a></li>
                            <li><a href="/users/order.html">Отследить заказ</a></li>
                            <li><a href="/users/notice.html">Уведомления о товарах</a></li>
                            <li><a href="/users/message.html">Связь с менеджерами</a></li>
                            @php if($_SESSION['UsersId']) echo '<li><a href="?logout=true">Выйти</a></li>'; php@
                        </ul>
                    </div>
                    <!-- My Account Links Ends -->
                    <!-- Customer Service Links Starts -->
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <h5>Навигация</h5>
                        <ul>
                            <li><a href="/price/" title="Прайс-лист">Прайс-лист</a></li>
                            <li><a href="/news/" title="Новости">Новости</a></li>
                            <li><a href="/gbook/" title="Отзывы">Отзывы</a></li>
                            <li><a href="/map/" title="Карта сайта">Карта сайта</a></li>
                            <li><a href="/forma/" title="Форма связи">Форма связи</a></li>
                        </ul>
                    </div>
                    <!-- Customer Service Links Ends -->
                    <!-- Contact Us Starts -->
                    <div class="col-md-3 col-sm-8 col-xs-12">
                        <h5>Контакты</h5>
                        <ul>
                            <li class="footer-map">@streetAddress@</li>
                            <li class="footer-email">@adminMail@</li>                              
                        </ul>
                        <h4 class="lead">
                            Тел: <span>@telNum@</span>
                        </h4>
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
                    <p itemscope itemtype="http://schema.org/Organization">© <span itemprop="name">@company@</span> @year@, Тел: <span itemprop="telephone">@telNum@</span>, <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">Адрес: <span itemprop="streetAddress">@streetAddress@</span></span><span itemprop="email" class="hide">@adminMail@</span></p>
                </div>
                <!-- Container Ends -->
            </div>
            <!-- Copyright Area Ends -->
        </footer>
        <!-- Footer Section Ends -->

        @editor@

        <!-- Fixed mobile bar -->
        <div class="bar-padding-fix visible-xs"> </div>
        <nav class="navbar navbar-default navbar-fixed-bottom bar bar-tab visible-xs visible-sm" role="navigation">
            <a class="tab-item active" href="/">
                <span class="icon icon-home"></span>
                <span class="tab-label">Домой</span>
            </a>
            <a class="tab-item @user_active@" @user_link@ data-target="#userModal">
                <span class="icon icon-person"></span>
                <span class="tab-label">Кабинет</span>
            </a>
            <a class="tab-item @cart_active@" href="/order/" id="bar-cart">
                <span class="icon icon-download"></span> <span class="badge badge-positive" id="mobilnum">@cart_active_num@</span>
                <span class="tab-label">Корзина</span>
            </a>
            <a class="tab-item" href="#" data-toggle="modal" data-target="#searchModal">
                <span class="icon icon-search"></span>
                <span class="tab-label">Поиск</span>
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
                        <h4 class="modal-title">Авторизация</h4>
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
                                <label>Пароль</label>
                                <input type="password" name="password" class="form-control" placeholder="Пароль..." required="">
                                <span class="glyphicon glyphicon-remove form-control-feedback hide" aria-hidden="true"></span>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="1" name="safe_users" @UserChecked@> Запомнить
                                </label>
                            </div>

                            @facebookAuth@ @twitterAuth@
                        </div>
                        <div class="modal-footer">
                            <span class="pull-left"><a href="/users/sendpassword.html" class="btn btn-default">Забыли?</a>
                            </span>
                            <input type="hidden" value="1" name="user_enter">
                            <button type="submit" class="btn btn-primary">Войти</button>
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
                        <h4 class="modal-title">Поиск</h4>
                    </div>
                    <div class="modal-body">
                        <form  action="/search/" role="search" method="post">
                            <div class="input-group">
                                <input name="words" maxlength="50" class="form-control" placeholder="Искать.." required="" type="search">
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
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/swiper.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/diggi.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/bootstrap-select.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@/js/phpshop.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery-ui.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.bxslider.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@/js/jquery.cookie.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.waypoints.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/inview.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.maskedinput.min.js"></script>
        @visualcart_lib@