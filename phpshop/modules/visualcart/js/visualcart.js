// Обновление визуальной корзины
function visualCart(xid) {
    if (document.getElementById('visualcart')) {

        // Проверка даты добавления товара в корзину
        var cart_update = VisualCartGetCookie('cart_update_time');

        if (cart_update > 0 || xid > 0) {
            var req = new Subsys_JsHttpRequest_Js();

            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.responseJS) {

                        
                         // т.к. может быть несколько елементов, заполняем через цикл
                         var els = document.getElementsByClassName('visualcart');
                         // тогда можно будет пробежаться по всем элементам в цикле
                         for (var i = 0; i < els.length; ++i) {
                         var el = els[i];
                         el.innerHTML = (req.responseJS.visualcart || '');
                         }
                         


                        if (req.responseJS.num == 0)
                            document.getElementById('visualcart_order').style.display = 'none';
                        else if (document.getElementById('visualcart_order') || req.responseJS.visualcart != '')
                            document.getElementById('visualcart_order').style.display = 'block';

                        // Синхронизация удаления
                        if (xid > 0 && document.getElementById('visualcart_order')) {
                            document.getElementById('num').innerHTML = (req.responseJS.num || '');
                            document.getElementById('sum').innerHTML = (req.responseJS.sum || '');
                        }

                    }
                }
            }
            req.caching = false;
            // Подготваливаем объект.
            // Реальное размещение
            req.open('POST', ROOT_PATH + '/phpshop/modules/visualcart/ajax/visualcart.php', true);
            req.send({
                xid: xid
            });
        }
    }
}


// Фотогалерея
function visualCartJQ(xid) {

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
                        //document.getElementById('visualcart_order').style.display = 'none';
                    }
                    else if ($('#visualcart_order') || json['visualcart'] != '') {
                        //document.getElementById('visualcart_order').style.display = 'block';
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
if (window.jQuery)
    setInterval("visualCartJQ(0)", 3000);
else
    setInterval("visualCart(0)", 3000);