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
        <meta name="engine-copyright" content="PHPSHOP.RU, @pageProduct@">
        <meta name="domen-copyright" content="@pageDomen@">
        <meta content="General" name="rating">
        <meta name="ROBOTS" content="ALL">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="icon" href="/favicon.ico"> 

        <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@css/jquery.jqzoom.css" type="text/css">

        <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/customize.css">
        <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/font-awesome.css">
        <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/style.css">
        <!-- flexslider css-->
        <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/flexslider.css">
        <!-- fancybox -->
        <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@/js/fancybox/jquery.fancybox.css">
        <!--[if lt IE 9]>
                <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
                <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
                <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@/font-awesome-ie7.css">
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="java/highslide/highslide.css"/>
        <LINK href="@pageCss@" type="text/css" rel="stylesheet">
        <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/style.css">
        <link id="bootstrap_theme"  href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/@variaty_theme@.css" rel="stylesheet">
        <link id="bootstrap_theme2" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/@variaty_theme2@.css" rel="stylesheet">
        <link id="bootstrap_theme3" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/@variaty_theme3@.css" rel="stylesheet">

    </HEAD>
    <body class="bod">
        <div id="mainContainer" class="clearfix">

            <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
            <script src="//code.jquery.com/jquery-migrate-1.2.1.js"></script>
            <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery-1.7.1.min.js"></script>

            <!--begain header-->
            <header>
                <div class="upperHeader">
                    <div class="container">@usersDisp@  <div class="top_wishlist">@wishlist@</div> 
                    </div><!--end container-->
                </div><!--end upperHeader-->

                <div class="middleHeader">
                    <div class="container">

                        <div class="middleContainer clearfix">

                            <div class="siteLogo pull-left">
                                <a title="@name@" href="/"><img src="@logo@" alt="@name@"></a>
                            </div>

                            <div class="pull-right" >

                                <form method="post" name="forma_search" action="/search/" onSubmit="return SearchChek()" class="siteSearch">
                                    <div class="input-append">
                                        <input name="words" maxlength="50" id="search" class="form-control span2" placeholder="Искать.." required="" type="search" data-trigger="manual" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true"  data-content="">
                                        <button class="btn btn-primary" type="submit" name="submit"><i class="icon-search"></i></button>
                                    </div>
                                </form>
                            </div>

                            <div class="pull-right">
                                <div class="btn-group">
                                    @valutaDisp@
                                </div>

                                <div class="btn-group">
                                    <a class="btn dropdown-toggle goToCompare" data-toggle="dropdown" title="Сравнить товары" href="/compare/">
                                        <i class="icon-refresh"></i>
                                        В сравнении <span id="numcompare">@numcompare@</span> тов.
                                    </a>

                                </div>
                                <div class="btn-group">
                                    <a class="btn dropdown-toggle minicart" data-toggle="dropdown" href="#">
                                        <i class="icon-shopping-cart"></i> <span id="num">@num@</span> тов. - <span id="sum">@sum@</span> @productValutaName@
                                        <span class="caret"></span>
                                    </a>
                                    <div class="dropdown-menu cart-content pull-right">@visualcart@</div>
                                </div>
                            </div><!--end pull-right-->

                        </div><!--end middleCoontainer-->

                    </div><!--end container-->
                </div><!--end middleHeader-->

                <div class="mainNav">
                    <div class="container">
                        <div class="navbar">

                            <ul class="nav">
                                <li class="active"><a href="#"><i class="icon-home"></i></a></li>
                                @leftCatalNtTop@
                                @topBrands@
                            </ul><!--end nav-->
                        </div>
                    </div><!--end container-->
                </div><!--end mainNav-->

            </header>
            <!-- end header -->



            <div class="container">

                <div class="row">

                    <div class="span8">
                        <div class="flexslider">
                            <ul class="slides">
                                @imageSlider@
                            </ul>
                        </div><!--end flexslider-->
                    </div><!--end span8-->


                    <div class="span4 hidden-phone">

                        <div id="homeSpecial">
                            <div class="titleHeader clearfix">
                                <a href="/newtip/"><h3>Новинки</h3></a>
                                <div class="pagers">
                                    <div class="btn-toolbar">
                                        <div class="btn-group">
                                            <button class="btn btn-mini vPrev"><i class="icon-caret-down"></i></button>
                                            <button class="btn btn-mini vNext"><i class="icon-caret-up"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end titleHeader-->



                            <ul class="vProductItems cycle-slideshow vertical clearfix"
                                data-cycle-fx="carousel"
                                data-cycle-timeout=0
                                data-cycle-slides="> li"
                                data-cycle-next=".vPrev"
                                data-cycle-prev=".vNext"
                                data-cycle-carousel-visible="2"
                                data-cycle-carousel-vertical="true"
                                >
                                @specMainIcon@


                            </ul>
                        </div><!--end homeSpecial-->

                    </div><!--end span4-->

                </div><!--end row-->



                <div class="row">
                    <div>
                        <div >
                            <!-- <div class="span12"> -->
                            <div class="titleHeader clearfix">
                                <a href="/spec/"><h3>Спецпредложения</h3></a>

                            </div><!--end titleHeader-->
                            <!-- </div> -->

                            <div class="row">
                                <ul class="hProductItems clearfix">
                                    @specMain@
                                </ul>
                            </div><!--end row-->
                        </div><!--end featuredItems-->
                    </div><!--end span12-->

                </div><!--end row-->


                <div class="row">

                    <div>
                        <div id="aboutUs">
                            <div class="titleHeader clearfix">
                                <h3>@mainContentTitle@</h3>
                            </div><!--end titleHeader-->

                            @mainContent@
                        </div>
                    </div><!--end span4-->


                    <!--end span4-->

                </div><!--end row-->


            </div><!--end conatiner-->


            <!--begain footer-->
            <footer>
                <div class="footerOuter">
                    <div class="container">
                        <div id="footer_info_content" class="row">
                            <div class="usefullLinks">
                                <div class="span3">
                                    <div class="titleHeader clearfix">
                                        <h3>Меню</h3>
                                    </div>
                                    <ul class="unstyled">
                                        <li><a class="invarseColor" href="/page/purchase.html" title="Купить">Купить</a></li>
                                        <li><a class="invarseColor" href="/page/developers.html" title="Разработчикам">Разработчикам</a></li>
                                        <li><a class="invarseColor" href="/page/admin.html" title="Администрирование">Администрирование</a></li>
                                        <li><a class="invarseColor" href="/page/design.html" title="Дизайн">Дизайн</a></li>
                                    </ul>
                                </div>
                                <div class="span3">
                                    <div class="titleHeader clearfix">
                                        <h3>Навигация</h3>
                                    </div>
                                    <ul class="unstyled">
                                        <li><a class="invarseColor" href="/price/" title="Прайс-лист">Прайс-лист</a></li>
                                        <li><a class="invarseColor" href="/news/" title="Новости">Новости</a></li>
                                        <li><a class="invarseColor" href="/gbook/" title="Отзывы">Отзывы</a></li>
                                        <li><a class="invarseColor" href="/links/" title="Полезные ссылки">Полезные ссылки</a></li>
                                        <li><a class="invarseColor" href="/map/" title="Карта сайта">Карта сайта</a></li>
                                        <li><a class="invarseColor" href="/forma/" title="Форма связи">Форма связи</a></li>
                                    </ul>
                                </div>
                                <div class="span3">
                                    <div class="titleHeader clearfix">
                                        <h3>Каталог статей</h3>
                                    </div>
                                    <ul class="unstyled">
                                        <li><a class="invarseColor" href="/page/CID_4.html">Учебные материалы</a></li><li><a class="invarseColor" href="/page/CID_3.html">Наши бренды</a></li>
                                    </ul>
                                </div>
                                <div class="span3">
                                    <div class="titleHeader clearfix">
                                        <h3>Мой аккаунт</h3>
                                    </div>
                                    <ul class="unstyled">
                                        @topMenu@
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div id="notification">
                            <div class="success-notification" style="display: none;">  <img src="images/close.png" alt="" class="close"><span class="notification-alert"> </span> </div>
                        </div>

                        <div id="modal" class="modal hide modal-lg">	
                            <div class="modal-body fastViewContent"></div> 
                        </div>


                    </div><!--end container-->
                    <div id="footer_cr"><div style="padding-right:10px;text-align:right;" >@button@</div>
                    </div><!--end footerOuter-->


            </footer>
            <!--end footer-->

        </div><!--end mainContainer-->

        <!-- JS
        ================================================== -->
        <!-- jQuery.Cookie -->
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.cookie.js"></script>

        <!-- bootstrap -->
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/bootstrap.min.js"></script>

        <!-- flexslider -->
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.flexslider-min.js"></script>

        <!-- cycle2 -->
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.cycle2.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.cycle2.carousel.min.js"></script>

        <!-- fancybox -->
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/fancybox/jquery.fancybox.js"></script>

        <!-- custom function-->
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.dcjqaccordion.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.jqzoom-core.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/custom.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jqfunc.js"></script>
        <script src="java/jqfunc.js"></script>
        <script src="java/phpshop.js"></script>
        <script src="phpshop/lib/Subsys/JsHttpRequest/Js.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/js.js"></script>
    </div>