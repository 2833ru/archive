<?php

/**
 * ƒобавление кнопки быстрого заказа
 */
function order_pickpoint_hook($obj, $row, $rout) {

    if ($rout == 'MIDDLE') {
        $order_action_add = "
<script type=\"text/javascript\" src=\"//pickpoint.ru/select/postamat.js\"></script>
<script>
function pickpoint_phpshop(result){
// устанавливаем в скрытое поле ID терминала
document.getElementById('pickpoint_id').value=result['id'];
// показываем пользователю название точки и адрес доствки
document.getElementById('dop_info').value=result['name']+', '+result['address'];
}

 $(document).ready(function() {
        $('<input type=\"hidden\" name=\"pickpoint_id\" id=\"pickpoint_id\">').insertAfter('#d');
    });   
</script>";
        
        $obj->set('order_action_add',$order_action_add,true);
        
    }
    

    /*
      if($rout =='END') {

      // ‘орма личной информации по заказу
      $cart_min=$obj->PHPShopSystem->getSerilizeParam('admoption.cart_minimum');
      if($cart_min <= $obj->PHPShopCart->getSum(false)) {
      $obj->set('orderContent',parseTemplateReturn('phpshop/modules/pickpoint/templates/main_order_forma.tpl',true));
      }
      else {
      $obj->set('orderContent',$obj->message($obj->lang('cart_minimum').' '.$cart_min,$obj->lang('bad_order_mesage_2')));
      }

      } */
}

$addHandler = array
    (
    'order' => 'order_pickpoint_hook'
);
?>