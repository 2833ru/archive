<?php

/**
 * Панель подробного описания товара
 * @param array $row массив данных
 * @return string 
 */
function tab_cart($data, $option = false) {
    global $PHPShopInterface;


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

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->dropdown_action_form = false;
    $PHPShopInterface->setCaption(array("Наименование", "50%"), array("Цена", "15%"), array('Кол-во', "10%", array('align' => 'center')), array(null, "10%"), array('Сумма', '15%', array('align' => 'right')));

    if (sizeof($cart) != 0)
        if (is_array($cart))
            foreach ($cart as $key=>$val) {

                if (!empty($val['id'])) {
                    
                    if (!empty($val['uid']))
                        $code = 'Артикул: ' . $val['uid'];
                    else
                        $code = 'Код: ' . $val['id'];

                    $name = '
<div class="media">
  <div class="media-left">
    <a href="?path=product&id=' . $val['id'] . '" >
      <img class="media-object" src="' . $val['pic_small'] . '" alt="' . $val['name'] . '" onerror="imgerror()">
    </a>
  </div>
   <div class="media-body">
    <div class="media-heading"><a href="?path=product&id=' . $val['id'] . '&return=order.'.$data['id'].'" >' . $val['name'] . '</a></div>
    ' . $code . '
  </div>
</div>';


                    $PHPShopInterface->setRow(array('name' => $name, 'align' => 'left'), $PHPShopOrder->ReturnSumma($val['price'], 0, ' '), array('name' => $val['num'], 'align' => 'center'), array('action' => array('cart-value-edit', '|', 'cart-value-remove', 'id' => $key), 'align' => 'center'), array('name' => $PHPShopOrder->ReturnSumma($val['price'] * $val['num'], 0, ' '). $currency, 'align' => 'right'));

                    $n++;
                    $num+=$val['num'];
                    $sum+=$val['price'] * $val['num'];
                }
            }


  
      $total = '<table class="pull-right totals">
      <tbody>
      <tr>
      <td>&nbsp;</td>
      <td class="text-right"><h4>Итого</h4></td>
      </tr>
      <tr>
      <td width="100">Сумма:</td>
      <td class="text-right">
      ' . ($PHPShopOrder->returnSumma($sum, 0, ' ') ) . $currency . '
      </td>
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
      ' . $PERSON['discount'] . '%
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
  <div class="col-lg-9 col-md-8 col-xs-6">
   <button  class="btn btn-default btn-sm cart-add"><span class="glyphicon glyphicon-plus"></span> ' . __('Добавить товары') . '</button>
  </div>
  <div class="col-lg-3 col-md-4 col-xs-6">
    <div class="input-group">
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
';

    return $disp;
}

?>
