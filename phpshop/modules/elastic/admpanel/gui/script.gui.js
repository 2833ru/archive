$(document).ready(function () {
    $('button[name="importProducts"]').on('click', function (e) {
        e.preventDefault();

        if($('.process-import').length > 0) {
            $('.process-import').remove();
        }
        if($('.elastic-info-container').length > 0) {
            $('.elastic-info-container').remove();
        }

        $('.main').prepend('<div class="elastic-info-container"></div>');
        $('.elastic-info-container').prepend('<div class="alert alert-info" role="alert">Выполняется экспорт данных в поисковую систему. Пожалуйста, не закрывайте вкладку браузера до завершения операции.</div>');
        $('.elastic-info-container').append('<div class="progress process-import"><div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div></div>');

        elasticImportData([], 1);
    });
});

function elasticImportData(data, initial)
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
        mimeType: 'text/html; charset=' + locale.charset,
        url: '/phpshop/modules/elastic/admpanel/ajax/admin.ajax.php',
        type: 'post',
        data: {
            from: from,
            total_documents: totalDocuments,
            total_imported: totalImported,
            total_categories: totalCategories,
            total_products: totalProducts,
            documents: documents,
            initial: initial
        },
        dataType: "json",
        async: false,
        success: function(json) {
            if(json['success']) {
                if(json.hasOwnProperty('message')) {
                    $('.elastic-info-container')
                        .append('<div class="alert alert-info" role="alert">' + json['message'] + '</div>');
                }
                if(json.hasOwnProperty('finished')) {
                    $('.process-import .progress-bar')
                        .css('width', '100%')
                        .attr('aria-valuenow', 100)
                        .html('100%');
                    $('.elastic-info-container')
                        .append('<div class="alert alert-success elastic-message" role="alert">Данные успешно экспортированы.</div>');
                    setTimeout(function () {
                        $('.elastic-info-container').remove();
                    }, 5000);
                } else {
                    $('.process-import .progress-bar')
                        .css('width',  json['percent'] + '%')
                        .attr('aria-valuenow', json['percent'])
                        .html(json['percent'] + '%');

                    elasticImportData(json, 0);
                }
            } else {
                $('.process-import').remove();
                $('.elastic-info-container')
                    .append('<div class="alert alert-danger" role="alert">' + json['message'] + '</div>');
            }
        }
    });
}