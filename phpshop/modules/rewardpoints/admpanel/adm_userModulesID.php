<?
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("date");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");

$PHPShopSystem = new PHPShopSystem();

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->debug_close_window=false;
$PHPShopGUI->reload='top';
$PHPShopGUI->ajax="'modules','rewardpoints'";
$PHPShopGUI->includeJava='<SCRIPT language="JavaScript" src="../../../lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>';
$PHPShopGUI->dir=$_classPath."admpanel/";

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    //if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;

    $action = 1;

    //return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm,$PHPShopModules;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Транзакции пользователя";
    $PHPShopGUI->size="650,540";

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Транзакции пользователя","",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    $PHPShopGUI->addJSFiles('../../../admpanel/java/jquery-1.11.0.min.js','../js/admin_rewardpoints.js');
    $PHPShopGUI->addCSSFiles('../css/admin_rewardpoints.css');

    // Пользователь
    $PHPShopOrmUser = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
    $shopuser = $PHPShopOrmUser->select(array('*'), array('id' => '="' . $_GET['id'] . '"'));

    $Tab1_2 = $PHPShopGUI->setLine() . $PHPShopGUI->setField('Информация о пользователе:', 
            '<p class="pu">Пользователь: <b>'.$shopuser['name'].'</b> - ('.$shopuser['mail'].')</p>'.
            '<p class="pu">Баллы: <b>'.$shopuser['point'].'</b></p>' . 
            '<input type="hidden" id="id_users" value="'.$_GET['id'].'">' .
            '<input type="hidden" id="mail_users" value="'.$shopuser['mail'].'">'
        , 'left', 0, 0, array('width' => '98%'));

    $PHPShopOrmTr = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_users_transaction"));
    $datatr = $PHPShopOrmTr->select(array('*'), array('id_users' => '="' . $_GET['id'] . '"'), array('order' => 'id DESC'), array('limit' => 300));

    if(isset($datatr)) {
        foreach ($datatr as $transaction) {

            //Статусы
            if($transaction['confirmation']==0)
                $confirmation = '<span class="minus">Ожидание</span>';

            if($transaction['confirmation']==1)
                $confirmation = '<span class="plus">Выполнено</span>';

            if($transaction['confirmation']==2)
                $confirmation = '<span class="minus">Отмена операции</span>';

            $maxTd .= '<tr>
                        <td>'.$transaction['id'].'</td>
                        <td>'.$transaction['date'].'</td>
                        <td>'.$confirmation.'</td>
                        <td class="'.($transaction['operation']==1 ? 'plus' : 'minus').'">'.($transaction['operation']==1 ? '+' : '-').' '.$transaction['number_points'].'</td>
                        <td>'.$transaction['balance_points'].'</td>
                        <td>'.$transaction['sum_orders'].'</td>
                        <td><i>'.$transaction['comment_admin'].'</i></td>
                    </tr>';

          $limit++;
        }


        $maxTdCase = '<div class="content-points"><table class="operationPoints" cellpadding="0" cellspacing="0">
        <thead>
        <tr class="titletd">
            <th>ID</th>
          <th>Дата</th>
          <th>Статус</th>
          <th>Кол-во баллов</th>
          <th>Баланс на момент</th>
          <th>Сумма заказа</th>
          <th>Комментарий</th>
        </tr>
        </thead>
        <tbody>'.$maxTd.'</tbody></table></div>';

        
        $Tab1 .= $PHPShopGUI->setInfo($maxTdCase, false, '98%');

    }
    else {
        $maxTdCase = '<div class="content-points"><table class="operationPoints" cellpadding="0" cellspacing="0">
        <thead>
        <tr class="titletd">
            <th>ID</th>
          <th>Дата</th>
          <th>Статус</th>
          <th>Кол-во баллов</th>
          <th>Баланс на момент</th>
          <th>Сумма заказа</th>
          <th>Комментарий</th>
        </tr>
        </thead>
        <tbody><tr class="no_data"><td colspan="7">Нет данных о транзакциях</td></tr></tbody></table></div>';
        $Tab1 .= $PHPShopGUI->setInfo($maxTdCase, false, '98%');
    }

    //Варианта статусов
    $vaStatTrans[] = array('Списание баллов', 0);  
    $vaStatTrans[] = array('Начисление баллов', 1, 'selected');
      
    //Добавить транзакцию
    $Tab1 .= $PHPShopGUI->setLine() . $PHPShopGUI->setField('Добавить транзакцию:', 
        $PHPShopGUI->setSelect('operation', $vaStatTrans, '120px', left) .
        $PHPShopGUI->setInputText('Кол-во баллов:', 'point', false, 35, false , left) . 
        $PHPShopGUI->setInputText('Комментарий:', 'comment_admin', false, 165, false , left) .
        "<div id='load_proc'><img src='../img/zoomloader.gif'></div>" .
        $PHPShopGUI->setInput("button","","Добавить","right",70,"addTransactionModules()","but")
    , 'left', 0, 0, array('width' => '98%'));
                            
    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Транзакции",$Tab1,400), array("Информация о пользователе",$Tab1_2,400));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            //$PHPShopGUI->setInput("button","","Отмена","right",70,"return onCancel();","but").
            //$PHPShopGUI->setInput("submit","delID","Удалить","right",70,"","but","actionDelete").
            $PHPShopGUI->setInput("submit","editID","ОК","right",70,"return onCancel();","but");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}


// Функция удаления
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id'=>'='.$_POST['newsID']));
    return $action;
}

if($UserChek->statusPHPSHOP < 2) {

    // Вывод формы при старте
    $PHPShopGUI->setAction($_GET['id'],'actionStart','none');

    // Обработка событий
    $PHPShopGUI->getAction();

}else $UserChek->BadUserFormaWindow();

?>


