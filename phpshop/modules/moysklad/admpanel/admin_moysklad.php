<?php

function actionStart() {
    global $PHPShopInterface,$PHPShopModules,$TitlePage, $select_name;

    $PHPShopInterface->checkbox_action=false;
    $PHPShopInterface->setCaption(array("Действие", "60%"),array("Дата", "20%"),  array("Статус", "20%"));

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.moysklad.moysklad_log"));
    $PHPShopOrm->debug = false;
            $PHPShopInterface->action_button['Синхронизировать'] = array(
        'name' => 'Синхронизировать',
        'action' => '../modules/moysklad/cron/stock.php',
        'class' => 'btn  btn-default btn-sm navbar-btn btn-action-panel-blank',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-refresh'
    );
    $PHPShopInterface->setActionPanel($TitlePage, $select_name, array('Синхронизировать'));

    // Сортировка по дате
    /*
    if (empty($_REQUEST['var3']))
        $pole1 = date("U") - 86400;
    else
        $pole1 = PHPShopDate::GetUnixTime($_REQUEST['var3']) - 86400;

    if (empty($_REQUEST['var4']))
        $pole2 = date("U");
    else
        $pole2 = PHPShopDate::GetUnixTime($_REQUEST['var4']) + 86400;

    $where['date'] = ' BETWEEN ' . $pole1 . ' AND ' . $pole2;*/


    $data = $PHPShopOrm->select(array('*'), $where=false, array('order' => 'id DESC'), array('limit' => 1000));

    if (is_array($data))
        foreach ($data as $row) {

            if ($data['status']== 1)
                $done = 'Выполнено';
            else
                $done = 'Ошибка';


            $PHPShopInterface->setRow(array('name'=>$row['order_id'],'link'=>'?path=modules.dir.moysklad&id=' . $row['id'], 'align' => 'left'),PHPShopDate::get($row['date'], true),  $done);
        }

    $PHPShopInterface->Compile();
}

?>