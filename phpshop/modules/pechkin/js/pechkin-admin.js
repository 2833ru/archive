// A $( document ).ready() block.
$( document ).ready(function() {
    code_check = $('#code_check_new').prop('checked');
    if(code_check==false) {
      $("#delivery_method_check_new").addClass("readonly");
      $("#delivery_method_new").addClass("readonly");
      $("#code_tip_new").addClass("readonly");
      //$("#active_check_new").addClass("readonly");
      //$("#active_date_ot_new").addClass("readonly");
      //$("#active_date_do_new").addClass("readonly");
      $("#sum_order_check_new").addClass("readonly");
      $("#sum_order_new").addClass("readonly");

      //Сообщение при заблокрированном чекбоксе
      //$(':checkbox[readonly=readonly]').click(function(){
      //      alert('Данную галочку невозможно установить пока не введен «Код купона» на вкладке - «Условия»');
      //      return false;
      //  });
    }

    $( "#code_check_new" ).on( "click", function() {
      code_check = $('#code_check_new').prop('checked');
      if(code_check==false) {
        $("#delivery_method_check_new").addClass("readonly");
        $("#delivery_method_new").addClass("readonly");
        $("#code_tip_new").addClass("readonly");
        //$("#active_check_new").addClass("readonly");
        //$("#active_date_ot_new").addClass("readonly");
        //$("#active_date_do_new").addClass("readonly");
        $("#sum_order_check_new").addClass("readonly");
        $("#sum_order_new").addClass("readonly");
      }
      else {
        $("#delivery_method_check_new").removeClass("readonly");
        $("#delivery_method_new").removeClass("readonly");
        $("#code_tip_new").removeClass("readonly");
        //$("#active_check_new").removeClass("readonly");
        //$("#active_date_ot_new").removeClass("readonly");
        //$("#active_date_do_new").removeClass("readonly");
        $("#sum_order_check_new").removeClass("readonly");
        $("#sum_order_new").removeClass("readonly");

        //Сообщение при заблокрированном чекбоксе
        //$(':checkbox[readonly=readonly]').click(function(){
            //alert('Данную галочку невозможно установить пока не введен «Код купона» на вкладке - «Условия»');
            //return false;
        //});
      }
    });

    //$("#code_check_new").click(function() {
      
    //});

      //Сообщение при заблокрированном чекбоксе
      $(':checkbox').click(function(){
          if($(this).attr("class")=='readonly') {
            alert('Данную галочку невозможно установить пока не введен «Код купона» на вкладке - «Условия»');
            return false;
          }
      });

    

    $('#selectalloption').click( function() {
      option_check = $('#selectalloption').prop('checked');
      if(option_check==true) {
        $('#categories option').prop('selected', true);
      }
      else {
        $('#categories option').prop('selected', false);
      }
    });
});


function authPechkin() {
  var login = $('#login_p_new').val();
  var pass = $('#pass_p_new').val();

  if(login=='') {
    alert('Логин не может быть пустым!');
    return false;
  }
  if(pass=='') {
    alert('Пароль не может быть пустым!');
    return false;
  }

  $('#loader_auth').show('fast');

  $.ajax({
      url: '/phpshop/modules/pechkin/ajax/pechkin-admin.php',
      type: 'post',
      data: 'get=auth&login=' + login + '&pass=' + pass + '&type=json',
      dataType: 'json',
      success: function(json) {
          if (json['success']) {
            if(json['status']==1) {
              $('#loader_auth').hide('fast');
              $('.auth-pechkin').hide();
              $('.reg-pechkin').hide();
              $('.auth-pechkin-true').show('fast');
              $('.auth-pechkin-true b').html( $('#login_p_new').val() );
            }
            else {
              $('#loader_auth').hide('fast');
              alert('Ошибка авторизации! Логин или пароль не верны.');
            }                   
          }
      }
  });

  
}

function exitPechkin() {
  $('#loader_auth').show('fast');
  $.ajax({
      url: '/phpshop/modules/pechkin/ajax/pechkin-admin.php',
      type: 'post',
      data: 'get=exit&type=json',
      dataType: 'json',
      success: function(json) {
          if (json['success']) {
              $('#login_p_new').val('');
              $('#pass_p_new').val('');

              
              $('#loader_auth').hide('fast');
              $('.auth-pechkin-true').hide();
              $('.reg-pechkin').show('fast');
              $('.auth-pechkin').show('fast');                  
          }
      },
      error: function(json){
        alert("Сервис Печкин временно недоступен, попробуйте пожалуйста позже");
      }
  });  
}

function importShopUsers(limitstartnew,adduserscount) {
  var progressBar = $('#percprogress');
  var list_id = $("#adress_base_new option:selected").val();
  $('#loader_auth').show('fast');
  $("#text_status_subscribe").hide();
  $(".but").prop('disabled', true);


  var statususers = ':';

  //Сериализуем статусы
  $( ".status_new" ).each(function( index ) {
    if($( this ).prop( "checked" )) {
      statususers = statususers + $( this ).val()+':';
    }
  });
  //Автозагрузка
  if($( "#autoload_new" ).prop( "checked" )) {
    var autoload = 1;
  }
  else {
    var autoload = 0;
  }

  //Параметры
  var merge1 = $( "#merge1_new option:selected" ).val();
  var merge2 = $( "#merge2_new option:selected" ).val();
  var merge3 = $( "#merge3_new option:selected" ).val();
  var merge4 = $( "#merge4_new option:selected" ).val();
  var merge5 = $( "#merge5_new option:selected" ).val();

  if(limitstartnew!== undefined) {
    var limitstart = limitstartnew;
  }
  else {
    var limitstart = 0;
  }

  if(adduserscount!== undefined) {
    var adduserscountnew = adduserscount;
  }
  else {
    var adduserscountnew = 0;
  }
  

  $.ajax({
    url: '/phpshop/modules/pechkin/ajax/pechkin-admin.php',
    type: 'post',
    data: 'get=importshopusers&list_id='+list_id+'&autoload='+autoload+'&merge1_new='+merge1+'&merge2_new='+merge2+'&merge3_new='+merge3+'&merge4_new='+merge4+'&merge5_new='+merge5+'&limitstart='+limitstart+'&add_users_count='+adduserscountnew+'&statususers='+statususers+'&type=json',
    dataType: 'json',
    success: function(json) {
        if (json['success']) {
          $("#text_status_subscribe").show();
          //$that.after(json);
            //$('#login_p_new').val('');
            //$('#pass_p_new').val('');

            $('#loader_auth').hide('fast');
            if(json['limit_start']!='break') {
              $('#text_status').html(' <i>(Обработано пользователей: <b>'+json['add_users_count']+'</b>)</i>');
              importShopUsers(json['limit_start'],json['add_users_count']);
            }
            if(json['limit_start']=='break') {
              $(".but").prop('disabled', false);
              $('#text_status').append(' <a href="'+json['link']+'">скачать лог</a>')
            }
            
            //$('.auth-pechkin-true').hide();
            //$('.reg-pechkin').show('fast');
            //$('.auth-pechkin').show('fast');                  
        }
    },
    error: function(json){
      alert("Сервис Печкин временно недоступен, попробуйте пожалуйста позже");
    }
  });
}

function importShopUsersSubscribe(limitstartnew,adduserscount) {
  var progressBar = $('#percprogress');
  var list_id = $("#adress_base_subscribe_new option:selected").val();
  $('#loader_auth_subscribe').show('fast');
  $(".but").prop('disabled', true);
  $("#text_status_subscribe").hide();


  var statususers = ':';

  //Сериализуем статусы
  $( ".status_subscribe_new" ).each(function( index ) {
    if($( this ).prop( "checked" )) {
      statususers = statususers + $( this ).val()+':';
    }
  });

  //Автозагрузка
  if($( "#autoload2_new" ).prop( "checked" )) {
    var autoload = 1;
  }
  else {
    var autoload = 0;
  }

  //Параметры
  var merge1 = $( "#merge1_subscribe_new option:selected" ).val();
  var merge2 = $( "#merge2_subscribe_new option:selected" ).val();
  var merge3 = $( "#merge3_subscribe_new option:selected" ).val();
  var merge4 = $( "#merge4_subscribe_new option:selected" ).val();
  var merge5 = $( "#merge5_subscribe_new option:selected" ).val();

  if(limitstartnew!== undefined) {
    var limitstart = limitstartnew;
  }
  else {
    var limitstart = 0;
  }

  if(adduserscount!== undefined) {
    var adduserscountnew = adduserscount;
  }
  else {
    var adduserscountnew = 0;
  }
  

  $.ajax({
    url: '/phpshop/modules/pechkin/ajax/pechkin-admin.php',
    type: 'post',
    data: 'get=importshopusers&subscribe=1&list_id='+list_id+'&autoload='+autoload+'&merge1_new='+merge1+'&merge2_new='+merge2+'&merge3_new='+merge3+'&merge4_new='+merge4+'&merge5_new='+merge5+'&limitstart='+limitstart+'&add_users_count='+adduserscountnew+'&statususers='+statususers+'&type=json',
    dataType: 'json',
    success: function(json) {
        if (json['success']) {
          $("#text_status_subscribe").show();
          //$that.after(json);
            //$('#login_p_new').val('');
            //$('#pass_p_new').val('');

            $('#loader_auth_subscribe').hide('fast');
            if(json['limit_start']!='break') {
              $('#text_status_subscribe').html(' <i>(Обработано пользователей: <b>'+json['add_users_count']+'</b>)</i>');
              importShopUsersSubscribe(json['limit_start'],json['add_users_count']);
            }
            if(json['limit_start']=='break') {
              $('#text_status_subscribe').append(' <a href="'+json['link']+'">скачать лог</a>');
              $(".but").prop('disabled', false);
            }
            
            //$('.auth-pechkin-true').hide();
            //$('.reg-pechkin').show('fast');
            //$('.auth-pechkin').show('fast');                  
        }
    },
    error: function(json){
      alert("Сервис Печкин временно недоступен, попробуйте пожалуйста позже");
    }
  });
}