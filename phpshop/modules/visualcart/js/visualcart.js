// Обновление визуальной корзины
function visualCart(xid) {

    // Проверка даты добавления товара в корзину
    var cart_update = VisualCartGetCookie('cart_update_time');

    if (cart_update > 0 || xid > 0) {

        $.ajax({
            url: ROOT_PATH + '/phpshop/modules/visualcart/ajax/visualcart.php',
            type: 'post',
            data: 'xid=' + xid + '&type=json',
            dataType: 'json',
            success: function(json) {
                if (json['success']) {

                    $('#visualcart').html(json['visualcart']);

                    if (json['num'] == 0) {
                        $('#visualcart_order').hide();
                    }
                    else if ($('#visualcart_order') || json['visualcart'] != '') {
                        $('#visualcart_order').show();
                    }

                    // Синхронизация удаления
                    if (xid > 0 && $('#visualcart_order')) {
                        $('#num').html(json['num']);
                        $('#sum').html(json['sum']);
                    }



                }
            }
        });
    }
}

function VisualCartGetCookie(cookieName) {
    var cookieValue = '';
    var posName = document.cookie.indexOf(escape(cookieName) + '=');
    if (posName != -1) {
        var posValue = posName + (escape(cookieName) + '=').length;
        var endPos = document.cookie.indexOf(';', posValue);
        if (endPos != -1)
            cookieValue = unescape(document.cookie.substring(posValue, endPos));
        else
            cookieValue = unescape(document.cookie.substring(posValue));
    }
    return cookieValue;
}

// Проверка новой корзины через промежуток времени
setInterval("visualCart(0)", 1000);