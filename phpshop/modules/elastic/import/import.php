<?php

session_start();

$_classPath = "../../../";
include_once($_classPath . "class/obj.class.php");
include_once($_classPath . "modules/elastic/class/include.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass('modules');
PHPShopObj::loadClass('orm');
PHPShopObj::loadClass('system');
PHPShopObj::loadClass('security');
PHPShopObj::loadClass('order');


if($_REQUEST['token'] !== Elastic::getOption('api')) {
    echo 'Access Denied!'; exit;
}

echo "
<script src='/phpshop/admpanel/js/jquery-1.11.0.min.js' data-rocketoptimized='false' data-cfasync='false'></script>
<div class='elastic-info-container'></div>
<div>Экспортировано: <div class='elastic-imported'>0</div></div>
<input type='hidden' name='elastica-token' value='" . $_REQUEST['token'] . "'>
<script>
$(document).ready(function () {
    elasticImport([], 1);
});

function elasticImport(data, initial)
{
    var from = 0;
    if(data.hasOwnProperty('from')) {
        from = data.from;
    }
    var totalDocuments = 0;
    if(data.hasOwnProperty('total_documents')) {
        totalDocuments = data.total_documents;
    }
    var totalImported = 0;
    if(data.hasOwnProperty('total_imported')) {
        totalImported = data.total_imported;
    }
    var totalCategories = 0;
    if(data.hasOwnProperty('total_categories')) {
        totalCategories = data.total_categories;
    }
    var totalProducts = 0;
    if(data.hasOwnProperty('total_products')) {
        totalProducts = data.total_products;
    }
    var documents = 0;
    if(data.hasOwnProperty('documents')) {
        documents = data.documents;
    }

    $.ajax({
        mimeType: 'text/html; charset=windows-1251',
        url: '/phpshop/modules/elastic/admpanel/ajax/admin.ajax.php',
        type: 'post',
        data: {
            token: $('[name=\"elastica-token\"]').val(),
            from: from,
            total_documents: totalDocuments,
            total_imported: totalImported,
            total_categories: totalCategories,
            total_products: totalProducts,
            documents: documents,
            initial: initial
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            if(json['success']) {
                if(json.hasOwnProperty('message')) {
                    $('.elastic-info-container').append(json['message'] + '<br>');
                    $('.elastic-imported').html(json['total_imported']);
                }
                if(json.hasOwnProperty('finished')) {
                    $('.elastic-info-container').append('Данные успешно экспортированы');
                    $('.elastic-imported').html(json['total_imported']);
                } else {
                    $('.elastic-imported').html(json['total_imported']);
                    elasticImport(json, 0);
                }
            } else {
               $('.elastic-info-container').append(json['message'] + '<br>');
            }
        }
    });
}
</script>";