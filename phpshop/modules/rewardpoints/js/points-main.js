function UpdateRewardpoints() {
    $.ajax({
        url: '/phpshop/modules/rewardpoints/ajax/rewardpoints.php',
        type: 'post',
        data: 'rewardpoints=1&type=json',
        dataType: 'json',
        success: function(json) {

            if (json['success']) {
                var nn = 3;
                for (var key in json['pointscart']) { 
                    var idProd = $(".img_fix table tr:nth-child( "+nn+" ) td:nth-child( 4 ) input[name=id_edit]").val();
                    var point = json['pointscart'][idProd]['point'];
                    
                    $( ".img_fix table tr:nth-child( "+nn+" ) td:nth-child( 7 )" ).html("<span class='point-cart'>"+point+" бал.</span>");
                    var nn = nn + 1;
                }
                nn = nn + 1;
                $( ".img_fix table tr:nth-child( "+nn+" ) td:nth-child( 7 )" ).html("<span class='point-cart'>"+json['sumpoints']+" бал.</span>");

                //Доступные баллы
                $("#pointBalanceOrder").html(json['pointBalance']);
                //Возможно потратить
                $("#point-itog").html(json['pointOk']);
                $("#points-trat").val(json['pointOkNo']);
                $("#point-eqv").html(json['pointEqv']);

                if(json['pointOkNo']==0) {
                    $(".okpoints").hide();
                }
            }
        }
    });
}
function valPoints() {
    $.ajax({
        url: '/phpshop/modules/rewardpoints/ajax/rewardpoints.php',
        type: 'post',
        data: 'rewardpoints=1&type=json',
        dataType: 'json',
        success: function(json) {

            if (json['success']) {
                var pointrat = $("#points-trat").val();

                if(pointrat>json['pointOkNo']) {
                    alert('Вы не можете поратить больше '+json['pointOkNo']+'бал. !');
                    $("#points-trat").val(json['pointOkNo']);
                }
            }
        }
    });
}

$(document).ready(function() {
    $("#check-points").click(function() {
        if($(this).prop("checked")) {
            $(".load-okp").show('fast');
            var pointrat = $("#points-trat").val();
            $.ajax({
                url: '/phpshop/modules/rewardpoints/ajax/okpoints.php',
                type: 'post',
                data: 'okpoints=1&pointrat='+pointrat+'&type=json',
                dataType: 'json',
                success: function(json) {

                    if (json['success']==1) {
                        $(".load-okp").hide();
                        $(".true-okp").show('slow');
                        $(".text-okpoints").hide();
                        $(".points-info-okpoints").hide();
                        $(".points-itog-order").show();
                        $(".totalsum-itog-order").hide();
                        $("#TotalSummaPoint").html(json['sumitog']);

                        $(".ajax-info").show();
                        $(".ajax-info").html('К оплате с учетом покупки за баллы <i>(без учета доставки)</i>: <b>'+json['sumitog']+'</b><br>С вашего счет после покупки будет списано <b>'+json['pointOk']+'</b>');
                        
                        var nn = 3;
                        for (var key in json['pointscart']) { 
                            $( ".img_fix table tr:nth-child( "+nn+" ) td:nth-child( 7 )" ).html("<span class='point-cart'>0 бал.</span>");
                            var nn = nn + 1;
                        }
                        nn = nn + 1;
                        $( ".img_fix table tr:nth-child( "+nn+" ) td:nth-child( 7 )" ).html("<span class='point-cart'>0 бал.</span>");

                    }
                    else {
                        $(".load-okp").hide();
                        $(".true-okp").show('slow');
                        $(".text-okpoints").hide();
                        $(".points-info-okpoints").hide();

                        $(".ajax-info").show();
                        $(".ajax-info").html('Недостаточно баллов для покупки');
                    }
                }
            });
        }
        else {
            $(".true-okp").hide();
            $(".load-okp").show('fast');
            $.ajax({
                url: '/phpshop/modules/rewardpoints/ajax/okpoints.php',
                type: 'post',
                data: 'okpoints=0&type=json',
                dataType: 'json',
                success: function(json) {

                    if (json['success']==1) {
                        $(".load-okp").hide();
                        
                        $(".text-okpoints").show();
                        $(".points-info-okpoints").show();
                        $(".ajax-info").hide();
                        $(".points-itog-order").hide();
                        $(".totalsum-itog-order").show();

                        var nn = 3;
                        for (var key in json['pointscart']) { 
                            var idProd = $(".img_fix table tr:nth-child( "+nn+" ) td:nth-child( 4 ) input[name=id_edit]").val();
                            var point = json['pointscart'][idProd]['point'];
                            
                            $( ".img_fix table tr:nth-child( "+nn+" ) td:nth-child( 7 )" ).html("<span class='point-cart'>"+point+" бал.</span>");
                            var nn = nn + 1;
                        }
                        nn = nn + 1;
                        $( ".img_fix table tr:nth-child( "+nn+" ) td:nth-child( 7 )" ).html("<span class='point-cart'>"+json['sumpoints']+" бал.</span>");
                    }
                    else {
                        $(".load-okp").hide();
                        
                        $(".text-okpoints").show();
                        $(".points-info-okpoints").show();
                        $(".ajax-info").hide();
                    }
                }
            });
        }
    });
});

