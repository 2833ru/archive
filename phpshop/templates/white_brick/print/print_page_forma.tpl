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

        <!-- Bootstrap -->
        <link id="bootstrap_theme" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/font-awesome.css">

    </head
    <body>

        <div class="container">
            <div class="row">
                <div class="span12">
                    <p>
                    <h3>@nameShop@</h3>
                    @descripShop@
                    <button onclick="window.print();" class="btn btn-default pull-right">
                        <span class="icon-print"></span> Печать
                    </button> 
                    </p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="span4">
                    <a href="http://@serverShop@/shop/UID_@productId@.html"><IMG src="http://@serverShop@@productImg@" alt="@productName@" title="@productName@" border="0" hspace="10"></a>
                </div>
                <div class="span8">
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <h3 class="media-heading">@productName@</h3>

                            <p>@vendorDisp@</p>
                            <p>@productDes@</p>
                            <h4>Цена: @productPrice@ @productValutaName@</h4>

                            <a href="http://@serverShop@/shop/UID_@productId@.html" title="Перейти по ссылке: @productName@" class="pull-right"><span class="icon-share-alt"></span> http://@serverShop@/shop/UID_@productId@.html</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="hide">
