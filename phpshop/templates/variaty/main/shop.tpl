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
        <!-- jquery ui css -->
        <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/jquery-ui-1.10.1.min.css">
        <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/customize.css">
        <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/font-awesome.css">

        <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/dcaccordion.css">
        <link rel="stylesheet" type="text/css" href="java/highslide/highslide.css"/>

        <!-- fancybox -->
        <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@/js/fancybox/jquery.fancybox.css">

        <!-- Font-awesome -->
        <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0css/font-awesome.min.css" rel="stylesheet">

        <!--[if lt IE 9]>
                <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
                <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
                <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@/font-awesome-ie7.css">
        <![endif]-->



        <LINK href="@pageCss@" type="text/css" rel="stylesheet">
        <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/style.css">
        <link id="bootstrap_theme"  href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/@variaty_theme@.css" rel="stylesheet">
        <link id="bootstrap_theme2" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/@variaty_theme2@.css" rel="stylesheet">
        <link id="bootstrap_theme3" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/@variaty_theme3@.css" rel="stylesheet">
    </HEAD>
    <BODY class="bod">

        <div id="mainContainer" class="clearfix">

            <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
            <script src="//code.jquery.com/jquery-migrate-1.2.1.js"></script>
            <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery-1.7.1.min.js"></script>
            <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.dcjqaccordion.js"></script>
            <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jqfunc.js"></script>
            <script src="java/jqfunc.js"></script>

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
                                        <input name="words" maxlength="50" id="search"  class="form-control span2" placeholder="Искать.." required="" type="search" data-trigger="manual" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true"  data-content="">
                                        <button class="btn btn-primary" type="submit" ><i class="icon-search"></i></button>
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
                                <li class=""><a href="/"><i class="icon-home"></i></a></li>
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
                    <aside class="span3 @UidLeftColHide@_hide" id="column-left"> @skinSelect@ <div class="vProductItems">@specMainIconUID@</div>
                        <div class="box">
                            <div class="box-heading @UidLeftColHide@">
                                <h2>Категории</h2>
                            </div>
                            <div class="box-content @UidLeftColHide@">
                                <div class="box-category">
                                    <div style="clear:both"></div>
                                    <ul class="accordion"  id="accordion-1">
                                        @leftCatalNt@
                                    </ul>
                                    <script type="text/javascript">
                                    opendcAccordion('@thisCat@');
                                    opendcAccordion('@thisCat1@');
                                    opendcAccordion('@thisCat2@');
                                    </script>
                                    <div style="clear:both"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Фасетный фильтр -->

                        <div class="box" id="faset-filter">
                            <div class="box-heading">                      
                                <h2>Фильтр товаров <a href="?" class="pull-right" id="faset-filter-reset" data-original-title="Сбросить фильтр"  rel="tooltip"><span class="icon-remove"></span></a></h2>
                            </div>

                            <div class="box-content">
                                <div class="box-category">
                                    <div style="clear:both"></div>
                                    <div>

                                        <div id="faset-filter-body">Загрузка...</div>

                                        <div id="price-filter-body">
                                            <h5>Цена</h5>
                                            <form method="get" id="price-filter-form">
                                                <div class="row-fluid">
                                                    <div class="span6" id="price-filter-val-min">
                                                        от <input type="text"   name="min" value="@price_min@" style="width:60px"> 
                                                    </div>

                                                    <div class="span6" id="price-filter-val-max">
                                                        до <input type="text"  name="max" value="@price_max@" style="width:60px"> 
                                                    </div>
                                                </div>
                                            </form>
                                            <p>
                                            <div id="slider-range"></div>

                                        </div>


                                    </div>
                                    <div style="clear:both"></div>
                                </div>
                            </div>
                        </div>
                        <!--/ Фасетный фильтр -->

                        <div class="box-heading"> <h2>Опрос</h2></div>@oprosDisp@
                        @rightMenu@

                        @leftMenu@
                        @calendar@
                        @cloud@ </aside><!--end aside-->

                    <div class="span9 @UidLeftColHide@">@DispShop@
                    </div><!--end row-->
                </div>
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
                                    <ul class="unstyled">@topMenu@

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
                                      @pageCatal@
                                    </ul>
                                </div>
                                <div class="span3">
                                    <div class="titleHeader clearfix">
                                        <h3>Мой аккаунт</h3>
                                    </div>
                                    <ul class="unstyled">
                                        <li><a class="invarseColor" href="/users/order.html">Отследить заказ</a></li>
                                        <li><a class="invarseColor" href="/users/notice.html">Уведомления о товарах</a></li>
                                        <li><a class="invarseColor" href="/users/message.html">Связь с менеджерами</a></li>

                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div id="notification">
                            <div class="success-notification" style="display: none;"><img src="images/close.png" alt="" class="close"> <span class="notification-alert"> </span>  </div>
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

        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.jqzoom-core.js"></script>
        <script src="phpshop/lib/Subsys/JsHttpRequest/Js.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/js.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.waypoints.min.js"></script>
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/inview.min.js"></script>
        <script src="java/phpshop.js"></script>

        <!-- jQuery.Cookie -->
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery.cookie.js"></script>

        <!-- bootstrap -->
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/bootstrap.min.js"></script>

        <!-- fancybox -->
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/fancybox/jquery.fancybox.js"></script>

        <!-- UI -->
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/jquery-ui.min.js"></script>
        <script>

                                    $(function() {
                                        $("a#catt@thisCat@").addClass("active");
                                        $("a#catt@thisCat@ + div ul").show();
                                    });

        </script>
