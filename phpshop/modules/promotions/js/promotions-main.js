
    
    function UpdatePromotion(promo) {

        var sum = document.getElementById('OrderSumma').value;
        var ssum = $("#SkiSumma").html();
        var promocode = $("#promocode").val(); 
        var tipoplcheck = $("#order_metod:checked").val();
        var dostavka = $("#dostavka_metod:checked").val();        
        var wsum = document.getElementById('WeightSumma').innerHTML;

        if(typeof promocode=="undefined") {
            promocodei = promo;
        }
        else {
            promocodei = promocode;   
        }
        //var dir = dirPath();



        $("#promotion_load").show();

        $.ajax({
            url: '/phpshop/modules/promotions/ajax/promotions.php',
            type: 'post',
            data: 'promocode=' + promocodei + '&sum=' + sum + '&type=json&ssum=' + ssum + '&tipoplcheck=' + tipoplcheck + '&dostavka=' + dostavka + '&wsum=' + wsum,
            dataType: 'json',
            success: function(json) {

                if (json['success']) {
                    var messageBox = '.success-notification';

                    //если нет элемента для всплывающих сообщий, выводим обычным alert
                    if ($(messageBox).length < 1) {
                        json['mes'] = json['mesclean'];
                    }
                    
                    //Если положительный ответ
                    if(json['status']==1) {
                        //Сравним итоговые суммы
                        var totalsum = parseFloat($("#TotalSumma").html());
                        var totalajax = parseFloat(json['total']);

                        if( parseInt(totalsum) > parseInt(totalajax) ) {

                            document.getElementById('TotalSumma').innerHTML = (json['total'] || '');
                            $("#SkiSumma").html(json['discount']);
                            document.getElementById('OrderSumma').value = json['totalsummainput'];
                            $("#SkiSummaAll").html(json['discountall']);

                            //Бесплатная доставка
                            if(json['freedelivery']==0) {
                                document.getElementById('DosSumma').innerHTML = (json['freedelivery'] || '');
                            }
                            $("#promocodei").addClass("true-promo");
                            $("#promocodei").removeClass("false-promo");
                            $(".paymOneEl").addClass("paymOneElr");
                            $(".paymOneEl").removeClass("paymOneEl");

                            if(json['deliverymethodcheck']==1) {
                                $('input[name=order_metod]').attr("disabled",true);
                                $('input[name=order_metod]:checked').attr("disabled",false);
                                $(".paymOneElr").click(function() {
                                    showAlertMessage('Для данного промо-кода невозможно выбрать другой тип оплаты!');
                                });
                            }

                            $("#promotion_load").hide();



                            var sum_itog = 0;
                                var nn = 3;
                                for (var key in json['discountcart']) { 
                                    var n = json['discountcart'][key]['n'];


                                    var sumold =  $( ".img_fix table tr:nth-child( "+n+" ) td:nth-child( 5 ) strike" ).html();
                                    var sumnumold = $( ".img_fix table tr:nth-child( "+n+" ) td:nth-child( 6 ) div" ).html();

                                    if(sumnumold) {
                                        $( ".img_fix table tr:nth-child( "+n+" ) td:nth-child( 5 )" ).html(sumold);
                                        $( ".img_fix table tr:nth-child( "+n+" ) td:nth-child( 6 )" ).html(sumnumold);
                                    }  
                                    
                                    var nn = nn + 1;
                                    var sum_itog_num = nn;

                                        //var sum_itog_num_valut = sum_html_t_all[1];
                                        
                                        //sum_dis = sum_discount_all+" "+sum_html_t_all[1];
                                        //var sum_itog_num = nn+1;
                                        //var sum_itog = parseFloat(sum_itog) + parseFloat(sum_dis);
                                }

                                
                                

                                for (var i = 0; i < 5; i++) {
                                    var doscount_itog_num = sum_itog_num+i;
                                    text = $( ".img_fix table tr:nth-child( "+doscount_itog_num+" ) td:nth-child( 1 )" ).html();
                                    //text = text.replace('/\s+/g', '');
                                    if(text=='<b>Итого:</b>') {
                                        var sumnum_itog_old = $( ".img_fix table tr:nth-child( "+doscount_itog_num+" ) td:nth-child( 6 ) div" ).html();
                                        if(sumnum_itog_old) {
                                            $( ".img_fix table tr:nth-child( "+doscount_itog_num+" ) td:nth-child( 6 )" ).html(sumnum_itog_old);
                                        }
                                    }
                                    if(text=='Скидка:') {
                                        $( ".img_fix table tr:nth-child( "+doscount_itog_num+" )" ).show();
                                    }
                                    
                                    n += i;
                                }





                            $('#promocodei').attr("disabled",true);
                            $('#promocodeButton').attr("disabled",true);

                            //выводим сообщение
                            if(json['mes']!='') {
                                showAlertMessage(json['mes']);
                            }

                        }
                        else {
                            showAlertMessage('Для данного промо-кода скидка является меньшей чем изначальная скидка');
                            $("#promotion_load").hide();
                        }
                        
                    }
                    else if(json['status']==9) {

                        document.getElementById('TotalSumma').innerHTML = (json['total'] || '');
                        $("#SkiSumma").html(json['discount']);
                        document.getElementById('OrderSumma').value = json['totalsummainput'];
                        $("#SkiSummaAll").html(json['discountall']);

                        //Бесплатная доставка
                        if(json['freedelivery']==0) {
                            $("#DosSumma").html(json['freedelivery']);
                        }
                        $("#promocodei").removeClass("true-promo");
                        $("#promocodei").removeClass("false-promo");

                        if(json['deliverymethodcheck']==1) {
                            $('input[name=order_metod]').attr("disabled",true);
                            $('input[name=order_metod]:checked').attr("disabled",false);
                            $(".paymOneElr").click(function() {
                                showAlertMessage('Для данного промо-кода невозможно выбрать другой тип оплаты!');
                            });
                        }

                        $("#promotion_load").hide();

                            var sum_itog = 0;
                            var nn = 3;
                            for (var key in json['discountcart']) { 
                                var n = json['discountcart'][key]['n'];
                                var discount = json['discountcart'][key]['discount'];
                                var discount_tip = json['discountcart'][key]['discount_tip'];
                                var num_product = json['discountcart'][key]['num_product'];


                                if(discount<1) {
                                    discount = 0;
                                }
                                    var sum =  parseFloat($( ".img_fix table tr:nth-child( "+n+" ) td:nth-child( 5 )" ).html());
                                    var sum_html =  $( ".img_fix table tr:nth-child( "+n+" ) td:nth-child( 5 )" ).html();

                                    var sum_all =  parseFloat($( ".img_fix table tr:nth-child( "+n+" ) td:nth-child( 6 )" ).html());
                                    var sum_html_all =  $( ".img_fix table tr:nth-child( "+n+" ) td:nth-child( 6 )" ).html();

                                    sum_html_t = sum_html.split(' ');
                                    sum_html_t_all = sum_html_all.split(' ');

                                    if(discount_tip=='%') {
                                        discountn = discount/100;
                                        sum_disc = sum * discountn;
                                        sum_discount = sum-sum_disc;

                                        sum_disc_all = sum_all * discountn;
                                        sum_discount_all = sum_all-sum_disc_all;
                                    }
                                    else {
                                        sum_discount = sum - discount;

                                        if(sum_discount<0) {
                                            sum_discount = 0;
                                        }

                                        sum_discount_all = sum_all - (discount*num_product);

                                        if(sum_discount_all<0) {
                                            sum_discount_all = 0;
                                        }

                                        discount = discount*num_product;
                                    }

                                    if(discount>=1) {
                                        $( ".img_fix table tr:nth-child( "+n+" ) td:nth-child( 5 )" ).html("<strike style='font-size:11px; color: grey;'>"+sum_html+"</strike> "+sum_discount+" "+sum_html_t[1]);
                                        $( ".img_fix table tr:nth-child( "+n+" ) td:nth-child( 6 )" ).html(sum_discount_all+" "+sum_html_t_all[1]+"<br><i style='font-size:10px; color: black;'>Скидка - "+discount+discount_tip+"</i><div style='display:none;'>"+sum_html_all+"</div>");
                                    }
                                    var nn = nn + 1;

                                    var sum_itog_num_valut = sum_html_t_all[1];
                                    
                                    sum_dis = sum_discount_all+" "+sum_html_t_all[1];
                                    var sum_itog_num = nn;
                                    var sum_itog = parseFloat(sum_itog) + parseFloat(sum_dis);
                            }

                            


                          
                            for (var i = 0; i < 5; i++) {
                                var doscount_itog_num = sum_itog_num+i;
                                text = $( ".img_fix table tr:nth-child( "+doscount_itog_num+" ) td:nth-child( 1 )" ).html();
                                //text = text.replace('/\s+/g', '');
                                if(text=='<b>Итого:</b>') {
                                    var sumitog_html = $( ".img_fix table tr:nth-child( "+doscount_itog_num+" ) td:nth-child( 6 )" ).html();
                                    $( ".img_fix table tr:nth-child( "+doscount_itog_num+" ) td:nth-child( 6 )" ).html(sum_itog+" "+sum_itog_num_valut+"<div style='display:none;'>"+sumitog_html+"</div>");
                                }
                                if(text=='Скидка:') {
                                    $( ".img_fix table tr:nth-child( "+doscount_itog_num+" )" ).hide();
                                }
                                
                                n += i;
                            }

                        
                    }
                    else {
                        //Если отрицательный ответ
                        $("#promocodei").addClass("false-promo");
                        $("#promocodei").removeClass("true-promo");
                        $("#promotion_load").hide();

                        //перенаправляем на список оплат
                        if(json['action']==1) {
                            $("html, body").delay(2000).animate({scrollTop: $('#order_metod').offset().top }, 2000);

                        }
                        //выводим сообщение
                        if(json['mes']!='')
                            showAlertMessage(json['mes']);
                    }

                    
                }
            }
        });
    }
    function showAlertMessage(message) {
        var messageBox = '.success-notification';
        var innerBox = '#notification .notification-alert';

        //если нет элемента для всплывающих сообщий, выводим обычным alert
        if ($(messageBox).length > 0) {
            $(innerBox).html(' ');
            $(innerBox).html(message);
            $(messageBox).fadeIn('slow');

            setTimeout(function() {
                $(messageBox).delay(500).fadeOut(1000);
            }, 10000);
        }
        else
            alert(message);
    }
