
$().ready(function() {

    // Чек возврата
    $("body").on('click', "#atol", function(event) {
        event.preventDefault();
        
        var operation = $(this).attr('data-operation');
        if(operation == 'sell')
            text = locale.confirm_sell;
        else text = locale.confirm_refund;

        if (confirm(text)) {
            var data = [];
            data.push({name: 'operation', value:  operation});
            data.push({name: 'ajax', value: 1});
            data.push({name: 'id', value: $(this).attr('data-id')});
            $.ajax({
                mimeType: 'text/html; charset=windows-1251',
                url: '../modules/atol/api.php',
                type: 'post',
                data: data,
                dataType: "json",
                async: false,
                success: function(json) {
                    if (json['status'] == 1) {
                        window.location.href += '&tab=7';
                    } else {
                        alert(locale.save_false);
                    }
                }

            });
        }
    });
});