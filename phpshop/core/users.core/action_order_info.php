<?php
/**
 * Вывод полной информации по заказу пользователя
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopCoreFunction
 * @param obj $obj объект класса
 * @param Int $tip флаг вызова [1 - личный кабинет], [2 - онлайн проверка заказа]
 */
function action_order_info($obj,$tip) {

    // Проверка личный кабинет 
    if($tip == 1) {
        $order_info=$_GET['order_info'];
        $where=array('uid'=>'="'.$order_info.'"');
    }
    // Он-лайн проверка заказа
    elseif($tip == 2) {
        $order_info=$_REQUEST['order'];
        $where=array('uid'=>'="'.$order_info.'"','user'=>'=0','datas'=>'<'.time("U")-($obj->order_live*2592000));
    }
    if(PHPShopSecurity::true_order($order_info)) {

        $PHPShopOrm = new PHPShopOrm($obj->getValue('base.orders'));
        $PHPShopOrm->debug=$obj->debug;
        $row=$PHPShopOrm->select(array('*'),$where,false,array('limit'=>1));

        // Библиотека работы с заказом
        $PHPShopOrderFunction= new PHPShopOrderFunction(false);

        // Валюта
        $currency=$PHPShopOrderFunction->default_valuta_code;

        // Стутусы заказов
        $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();

        if(is_array($row)) {

            // Импортируем данные
            $PHPShopOrderFunction->import($row);

            // Проверка он-лайн метода
            if($tip == 2){
                if(PHPShopSecurity::true_email($_REQUEST['mail']))
                    if($_REQUEST['mail'] != $PHPShopOrderFunction->getMail())
                            return $obj->action_index();
            }

            // Стутусы заказов
            $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();

            // Список покупок
            $cart=$PHPShopOrderFunction->cart('usercartforma',array('obj'=>$obj,'currency'=>$currency));

            // Заголовок
            $title=PHPShopText::div(PHPShopText::img('images/shop/icon_info.gif',5,'absmiddle').PHPShopText::b(__('Информация по заказу №').$row['uid']),$align="left",$style=false,$id='allspec');

            // Описание столбцов
            $caption=$obj->caption(__('Наименование'),__('Кол-во'),__('Сумма'));

            // Доставка
            $delivery=$PHPShopOrderFunction->delivery('userdeleveryforma',array('obj'=>$obj,'currency'=>$currency));

            // Итого
            $total=$obj->tr(PHPShopText::b(__('Итого с учетом скидки ').$PHPShopOrderFunction->getDiscount().'%'),
                    PHPShopText::b($PHPShopOrderFunction->getNum()+1).' '.__('шт.'),PHPShopText::b($PHPShopOrderFunction->getTotal()).' '.$currency);

            // Комментарии по заказу
            if($PHPShopOrderFunction->getSerilizeParam('status.maneger')!='')
            $comment=PHPShopText::p(PHPShopText::message($PHPShopOrderFunction->getSerilizeParam('status.maneger'),'images/shop/icon_info.gif'));
            else $comment=null;

            // Время обработки заказа
            $time=$obj->tr(PHPShopText::img('images/shop/icon_clock.gif',$hspace=5,$align='absmiddle').__('Время обработки заказа:').' '.
                    $PHPShopOrderFunction->getStatusTime().$comment,PHPShopText::b($PHPShopOrderFunction->getStatus($PHPShopOrderStatusArray),'color:'.$PHPShopOrderFunction->getStatusColor($PHPShopOrderStatusArray)),'-');

            // Способ оплаты
            $payment=$obj->tr(__('Способ оплаты'),userorderpaymentlink($obj,$PHPShopOrderFunction,$tip,$row),'-');

            // Документооборот
            $docs=userorderdoclink($row,$obj);

            // Таблица
            $slide=PHPShopText::slide('Order');
            $table=$slide.$title.PHPShopText::p(PHPShopText::table($caption.$cart.$delivery.$total.$time.$payment.$docs,3,1,'center','99%',false,0,'allspecwhite'));

            $obj->set('formaContent',$table,true);
        }
        else $obj->action_index();
    }
}

/**
 * Корзина покупок в заказе
 */
function usercartforma($val,$option) {
    $icon=PHPShopText::img('phpshop/lib/templates/icon/accept.png',$hspace=5,$align='absmiddle');
    $link='/shop/UID_'.$val['id'].'.html';
    $dis=$option['obj']->tr(PHPShopText::a($link,$icon.$val['name'],$val['name'],false,false,'_blank','b'),$val['num'],$val['total']);
    return $dis;
}

/**
 * Доставка
 */
function userdeleveryforma($val,$option) {
    $icon=PHPShopText::img('phpshop/lib/templates/icon/lorry.gif',$hspace=5,$align='absmiddle');
    $dis=$option['obj']->tr($icon.__('Доставка').' - '.$val['name'],1,$val['price'].' '.$option['currency']);
    return $dis;
}

/**
 * Документооборот
 */
function userorderdoclink($val,$obj) {

    $PHPShopOrm = new PHPShopOrm($obj->getValue('base.1c_docs'));
    $PHPShopOrm->debug=$obj->debug;
    $data=$PHPShopOrm->select(array('*'),array('uid'=>'='.$val['id']),false,array('limit'=>1000));

    if(is_array($data)) {

        // Описание столбцов
        $dis=$obj->caption(__('Документооборот'),__('Дата'),__('Загрузка'));
        $n=$val['id'];
        foreach($data as $row) {

            // Счета
            if($obj->PHPShopSystem->ifValue('1c_load_accounts')) {
                $link_def='../files/docsSave.php?orderId='.$n.'&list=accounts&datas='.$row['datas'];
                $link_html='../files/docsSave.php?orderId='.$n.'&list=accounts&tip=html&datas='.$row['datas'];
                $link_doc='../files/docsSave.php?orderId='.$n.'&list=accounts&tip=doc&datas='.$row['datas'];
                $link_xls='../files/docsSave.php?orderId='.$n.'&list=accounts&tip=xls&datas='.$row['datas'];
                $dis.=$obj->tr(PHPShopText::a($link_def,__('Счет на оплату'),false,false,false,'_blank','b'),PHPShopDate::dataV($row['datas']),
                        PHPShopText::a($link_html,__('HTML'),__('Формат Web'),false,false,'_blank','b').' '.
                        PHPShopText::a($link_doc,__('DOC'),__('Формат Word'),false,false,'_blank','b').' '.
                        PHPShopText::a($link_xls,__('XLS'),__('Формат Excel'),false,false,'_blank','b'));
            }

            // Счета-фактуры
            if(!empty($row['datas_f']) and $obj->PHPShopSystem->ifValue('1c_load_invoice')) {
                $link_def='../files/docsSave.php?orderId='.$n.'&list=invoice&datas='.$row['datas'];
                $link_html='../files/docsSave.php?orderId='.$n.'&list=invoice&tip=html&datas='.$row['datas'];
                $link_doc='../files/docsSave.php?orderId='.$n.'&list=invoice&tip=doc&datas='.$row['datas'];
                $link_xls='../files/docsSave.php?orderId='.$n.'&list=invoice&tip=xls&datas='.$row['datas'];
                $dis.=$obj->tr(PHPShopText::a($link_def,__('Счет-фактура'),false,false,false,'_blank','b'),PHPShopDate::dataV($row['datas_f']),
                        PHPShopText::a($link_html,__('HTML'),__('Формат Web'),false,false,'_blank','b').' '.
                        PHPShopText::a($link_doc,__('DOC'),__('Формат Word'),false,false,'_blank','b').' '.
                        PHPShopText::a($link_xls,__('XLS'),__('Формат Excel'),false,false,'_blank','b'));
            }
        }
        return $dis;
    }
}

/**
 * Ссылка на оплату
 */
function userorderpaymentlink($obj,$PHPShopOrderFunction,$tip,$row) {
    global $PHPShopSystem,$PHPShopBase;

    $disp=null;
    PHPShopObj::loadClass('payment');
    $PHPShopPayment = new PHPShopPayment($PHPShopOrderFunction->order_metod_id);
    $path=$PHPShopPayment->getPath();
    $name=$PHPShopPayment->getName();
    $id=$row['id'];
    $datas=$row['datas'];

    // Ссылки на оплаты
    switch($path) {

        // Сообщение
        case("message"):
            $disp.=PHPShopText::b($name);
            break;

        // Счет в банк
        case("bank"):
            if(!$PHPShopSystem->ifValue('1c_load_accounts')) {
                $icon=PHPShopText::img('images/shop/interface_browser.gif',$hspace=5,$align='absmiddle');
                $disp=PHPShopText::a("phpshop/forms/account/forma.html?orderId=$id&tip=$tip&datas=$datas",$icon.$name,$name,false,false,'_blank','b');
            }else {
                $disp.=PHPShopText::b($name).'.<br>'.__('Ожидайте счета, после проведения документа<br> он автоматически появится в данном разделе<br> личного кабинета.');
            }
            break;

        // Квитанция Сбербанка
        case("sberbank"):
            $icon=PHPShopText::img('images/shop/interface_dialog.gif',$hspace=5,$align='absmiddle');
            $disp.=PHPShopText::a("phpshop/forms/receipt/forma.html?orderId=$id&tip=$tip&datas=$datas",$icon.$name,$name,false,false,'_blank','b');
            break;

        /*
         * Попытка подключить функцию обработчик [name]_users_repay() из папки с именем платежной системы /payment/[name]/users.php
         * Пример реализации /payment/webmoney/users.tpl
        */
        default:
            $users_file='./payment/'.$path.'/users.php';
            $users_function=$path.'_users_repay';
            if(is_file($users_file)) {
                include_once($users_file);
                if(function_exists($users_function)) {
                    $disp=call_user_func_array($users_function, array(&$obj, $PHPShopOrderFunction));
                }
            }
            else
                $disp.=PHPShopText::b($name);
            break;
    }

    return $disp;
}
?>