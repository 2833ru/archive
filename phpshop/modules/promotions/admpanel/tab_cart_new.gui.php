<?php

/**
 * Панель подробного описания товара
 * @param array $row массив данных
 * @return string 
 */
function tab_cart_new($data, $option = false) {
    global$PHPShopInterface;


    $PHPShopInterface->action_title['cart-value-edit'] = 'Редактировать';
    $PHPShopInterface->action_title['cart-value-remove'] = 'Удалить';


    // Библиотека заказа
    $PHPShopOrder = new PHPShopOrderFunction($data['id']);

    $order = unserialize($data['orders']);
    $status = unserialize($data['status']);

    $CART = $order['Cart'];
    $PERSON = $order['Person'];
    $cart = $CART['cart'];
    $_SESSION['selectCart']=$cart;
    $num = $data_id = $sum = null;
    $n = 1;
    
    // Знак рубля
    if ($PHPShopOrder->default_valuta_iso == 'RUB')
        $currency = ' <span class="rubznak">p</span>';
    else $currency = $PHPShopOrder->default_valuta_iso;

  


      $total = '<table class="pull-right totals">
      <tbody>
      <tr>
      <td>&nbsp;</td>
      <td class="text-right"><h4>Итого</h4></td>
      </tr>
      <tr>
      <td>Доставка:</td>
      <td class="text-right">
      ' . $PHPShopOrder->getDeliverySumma().  $currency. '
      </td>
      </tr>
      <tr>
      <td>Скидка:</td>
      <td class="text-right">
      ' . ($PERSON['tip_disc']==1 ? $PERSON['discount_promo'].'%' : $PERSON['discount_promo'].' руб.')  . '
      </td>
      </tr>
      <tr>
      <td><h5>Итого:</h5></td>
      <td class="text-right">
      <h5 class="text-success">' . ($PHPShopOrder->getTotal(false, ' ')) . $currency . '</h5>
      </td>
      </tr>
      </tbody>
      </table>';
    
    // Скидка
    if(!empty($PERSON['discount']))
        $discount = $PERSON['discount'];
    else $discount=null;
        


    $disp = '<table class="table table-hover cart-list">' . $PHPShopInterface->getContent() . '</table>
<div class="row">
  <div class="col-md-9">
   <button  class="btn btn-default btn-sm cart-add"><span class="glyphicon glyphicon-plus"></span> ' . __('Добавить товары') . '</button>
  </div>
  <div class="col-md-3">
    <div class="input-group ">
      <span class="input-group-addon input-sm">%</span>
      <input type="text" class="form-control input-sm discount-value" placeholder="'.__('Скидка').'" value="'.$discount.'"> 
      <span class="input-group-btn">
        <button class="btn btn-default btn-sm discount" type="button">'.__('Назначить').'</button>
     </span>
    </div>
  </div>
</div>
<p class="clearfix"> </p>
'.$total.'
<p class="clearfix"> </p>
<div class="row">
  <div class="col-md-6">
  <label for="dop_info">Примечания покупателя</label>
  <textarea class="form-control" id="dop_info" name="dop_info_new">'.$data['dop_info'].'</textarea>
  </div>
  <div class="col-md-6">
    <label for="status_maneger">Примечания администратора</label>
    <textarea class="form-control" id="status_maneger" name="status[maneger]">'.$status['maneger'].'</textarea>
  </div>
</div>
<style>
.sidebarcontainer .nav-pills li:first-child {
  display:none;
}
</style>
';

    return $disp;
}

?>
