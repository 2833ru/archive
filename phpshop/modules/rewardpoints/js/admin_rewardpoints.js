function addTransactionModules() {
		
		var point = $("#point").val();
		var comment_admin = $("#comment_admin").val();
		var operation = $("#operation option:selected").val();
		var id_users = $("#id_users").val();
		var mail_users = $("#mail_users").val();
		var today = new Date();



		$("#load_proc").show('fast');

		$.ajax({
        url: '/phpshop/modules/rewardpoints/ajax/admin_rewardpoints.php',
        type: 'post',
        data: 'rewardpoints=1&id_users='+id_users+'&mail_users='+mail_users+'&operation='+operation+'&point='+point+'&comment_admin='+comment_admin+'&type=json',
        dataType: 'json',
        success: function(json) {
            if (json['success']==1) {
            		if(operation==1) {
            			var point_html = '<td class="plus">+ '+point+'</td>';
            		}
            		if(operation==0) {
            			var point_html = '<td class="minus">- '+point+'</td>';
            		}

                $("#load_proc").hide();
                $(".operationPoints tbody").prepend('<tr style="background:#eaffe4;"><td>new</td><td>'+today+'</td><td><span class="plus">Выполнено</span></td>'+point_html+'<td></td><td></td><td><i><b>Администрация:</b> '+comment_admin+'</i></td></tr>');
								
                $('.content-points').animate({
								   scrollTop: 0
								}, 'fast');

                $("#point").val('');
								$("#comment_admin").val('');
								$(".no_data").hide();

                setTimeout(function(){ $('.operationPoints tbody tr').css('background', ''); },2000);
            }
            else {
                $("#load_proc").hide();
                alert('Списывать уже нечего :) Баланс по нулям!');
            }
        }
    });

}