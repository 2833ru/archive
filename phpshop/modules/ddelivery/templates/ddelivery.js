var topWindow = parent;

while (topWindow != topWindow.parent) {
    topWindow = topWindow.parent;
}

if (typeof(topWindow.DDeliveryIntegration) == 'undefined')
    topWindow.DDeliveryIntegration = (function() {
        var ddeliveryConfig;

        var style = document.createElement('STYLE');
        style.innerHTML = // РЎРєСЂС‹РІР°РµРј РЅРµРЅСѓР¶РЅСѓСЋ РєРЅРѕРїРєСѓ
                " #delivery_info_ddelivery_all a{display: none;} " +
                " #ddelivery_popup { display: inline-block; vertical-align: middle; margin: 10px auto; width: 1000px; height: 650px;} " +
                " #ddelivery_container {  z-index: 9999;display: none; width: 100%; height: 100%; text-align: center;  } " +
                " #ddelivery_container:before { display: inline-block; height: 100%; content: ''; vertical-align: middle;} " +
                " #ddelivery_cover {overflow: auto;position: fixed; top: 0; left: 0; right:0; bottom:0; z-index: 9000; width: 100%; height: 100%; background-color: #000; background: rgba(0, 0, 0, 0.5); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr = #7F000000, endColorstr = #7F000000); } ";
        var body = document.getElementsByTagName('body')[0];
        body.appendChild(style);
        var div = document.createElement('div');
        div.innerHTML = '<div id="ddelivery_popup"></div>';
        div.id = 'ddelivery_container';
        body.appendChild(div);


        function showPrompt() {
            var cover = document.createElement('div');
            cover.id = 'ddelivery_cover';
            cover.appendChild(div);
            document.body.appendChild(cover);
            document.getElementById('ddelivery_container').style.display = 'block';
            document.body.style.overflow = 'hidden';
            document.getElementById('ddelivery_popup').innerHTML = '';
        }

        function hideCover() {
            document.body.removeChild(document.getElementById('ddelivery_cover'));
            document.getElementsByTagName('body')[0].style.overflow = "";
        }

        function orderCallBack(data, dostavka_metod) {
            $('[name="fio_new"]').val(data.userInfo.firstName);
            $('[name="dop_info"]').text(data.comment);

            if (data.type == '2') {
                $('[name="index_new"]').val(data.userInfo.toIndex);
                $('[name="street_new"]').val(data.userInfo.toStreet);
                $('[name="house_new"]').val(data.userInfo.toHouse);
                $('[name="flat_new"]').val(data.userInfo.toFlat);

            }
            $('[name="tel_new"]').val(data.userInfo.toPhone);
            $('#ddelivery_id').val(data.orderId);
            $('.dd_comment').text(data.comment);

            /*
            // Отладка
            for (key in data) {
                alert( "Ключ: " + key + " значение: " + data[key] );
            }*/

            $('#DosSumma').text(data.clientPrice);
            $("#TotalSumma").text(Number($("#TotalSumma").text())+Number(data.clientPrice));

        }

        function getActiveDelivery() {
            var dostavka_metod = $('input[name="dostavka_metod"]:checked ').val();
            return dostavka_metod;
        }

        function disablePaymentDelivery(ids) {
            if (ids.length > 0) {
                $('input[name="order_metod"]').each(function() {
                    if (ids.indexOf($(this).val()) != -1) {
                        $(this).parent().parent().css('display', 'none');
                    }
                });
            }
        }

        function enablePayment() {
            $('input[name="order_metod"]').each(function() {
                $(this).parent().parent().css('display', 'block');
            });
            $('.dd_comment').text("");
        }

        return{
            openPopup: function() {
                showPrompt();
                var callback = {
                    close: function() {
                        hideCover();
                    },
                    change: function(data) {
                        var dostavka_metod = getActiveDelivery();
                        orderCallBack(data, dostavka_metod);
                        //UpdateDeliveryJq( dostavka_metod, '&order_id='+data.orderId, 'stophook' );
                        console.log(data);
                        disablePaymentDelivery(data.payment);
                        hideCover();
                    }
                };
                var forma_order = $('#forma_order').serialize();
                getActiveDelivery();

                DDelivery.delivery('ddelivery_popup', ddeliveryConfig.url + '?' + forma_order /*'@DDorderUrl@' + paramsString */, {}, callback);
                return void(0);
            },
            init: function(ddConfig) {

                $(document).ready(function() {
                    ddeliveryConfig = ddConfig;

                    $('#seldelivery').on('click', function() {
                        $('#ddelivery_id').val("");
                        enablePayment();
                    });

                    $('#forma_order').append('<input type="hidden" id="ddelivery_id" name="ddelivery_id" value="">');
                    $('#forma_order').submit(function() {
                        var dostavka_metod = getActiveDelivery(); //document.getElementById("dostavka_metod").value;
                        if (ddeliveryConfig.DDeliveryID.indexOf(parseInt(dostavka_metod)) != -1) {
                            if (parseInt($('#ddelivery_id').val()) > 0) {
                                return true;
                            }
                            alert('Уточните выбор доставки');
                            return false;
                        }
                        return true;
                    });



                });
            }
        }
    })();

DDeliveryIntegration.init(DDeliveryConfig);