<?php

$TitlePage = __("Отчеты по заказам");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopOrm, $PHPShopInterface, $PHPShopSystem;

    PHPShopObj::loadClass('valuta');
    PHPShopObj::loadClass('user');
    PHPShopObj::loadClass('order');

    $PHPShopGUI->action_select['Линейная диаграмма'] = array(
        'name' => 'Линейная диаграмма',
        'action' => 'canvas-line',
        'class' => 'disabled'
    );

    $PHPShopGUI->action_select['Гистограмма'] = array(
        'name' => 'Гистограмма',
        'action' => 'canvas-bar'
    );

    $PHPShopGUI->action_select['Радар диаграмма'] = array(
        'name' => 'Радар диаграмма',
        'action' => 'canvas-radar'
    );


    $Months = array("01" => "января", "02" => "февраля", "03" => "марта",
        "04" => "апреля", "05" => "мая", "06" => "июня", "07" => "июля",
        "08" => "августа", "09" => "сентября", "10" => "октября",
        "11" => "ноября", "12" => "декабря");

    // Статусы заказов
    PHPShopObj::loadClass('order');
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $status_array = $PHPShopOrderStatusArray->getArray();
    $status[] = __('Новый заказ');

    $order_status_value[] = array(__('Все заказы'), 0, $_GET['where']['statusi']);
    if (is_array($status_array))
        foreach ($status_array as $status_val) {
            $status[] = $status_val['name'];
            $order_status_value[] = array($status_val['name'], $status_val['id'], $_GET['where']['statusi']);
        }

    // Поиск
    $where = $clean = null;


    if (is_array($_GET['where'])) {
        foreach ($_GET['where'] as $k => $v) {
            if (!empty($v))
                $where.= ' ' . $k . ' = "' . $v . '" or';
        }

        if ($where){
            $where = 'where' . substr($where, 0, strlen($where) - 2);
            $clean = true;
        }
    }


    $time = time();

    // Дата
    if (!empty($_GET['date_start']) and !empty($_GET['date_end'])) {
        
        $clean = true;
        
        if ($where)
            $where.=' and ';
        else
            $where = ' where ';
        $where.=' a.datas between ' . (PHPShopDate::GetUnixTime($_GET['date_start']) - 1) . ' and ' . (PHPShopDate::GetUnixTime($_GET['date_end']) + 259200 / 2) . '  ';
    }
    else {
        
        $where.=' and a.datas between ' . ($time - 2592000) . ' and ' . ($time + 259200 / 2) . '  ';
    }

    if (isset($_GET['date_start']))
        $date_start = $_GET['date_start'];
    else
        $date_start = PHPShopDate::get(time() - 2592000);

    if (isset($_GET['date_end']))
        $date_end = $_GET['date_end'];
    else
        $date_end = PHPShopDate::get(time() - 1);
    
     $TitlePage.=' с ' . $date_start . ' по ' . $date_end ;


    // Размер названия поля
    $PHPShopGUI->field_col = 3;
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js', './js/bootstrap-datetimepicker.ru.js', 'js/chart.min.js', 'report/gui/report.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');
    $PHPShopGUI->setActionPanel($TitlePage, array('Линейная диаграмма', 'Гистограмма', 'Радар диаграмма', '|', 'Export'), false);

    // Таблица с данными
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
    $PHPShopOrm->Option['where'] = ' or ';
    $PHPShopOrm->debug = false;
    $PHPShopOrm->sql = 'SELECT a.*, b.mail FROM ' . $GLOBALS['SysValue']['base']['orders'] . ' AS a 
        JOIN ' . $GLOBALS['SysValue']['base']['shopusers'] . ' AS b ON a.user = b.id  ' . $where . ' 
            order by a.id ';
    $canvas_value = $canvas_label = $canvas_export = $alert =  null;
    $data = $PHPShopOrm->select();
    if (is_array($data))
        foreach ($data as $row) {

            // Библиотека заказа
            if(empty($row['sum'])){
            $PHPShopOrder = new PHPShopOrderFunction($row['id'], $row);
            $row['sum'] = $PHPShopOrder->getTotal(false);
            }

            if (empty($row['fio'])) {
                $row['fio'] = $row['mail'];
            }

            
            $canvas_value.='"' . $row['sum'] . '",';
            $canvas_export.='"' . $row['id'] . '",';


            $d_array = array(
                'm' => date("m", $row['datas']),
                'd' => date("d", $row['datas'])
            );

            $canvas_label.='"' . $d_array['d'] . ' ' . $Months[$d_array['m']] . '",';
        }

    if(empty($canvas_value))
        $alert ='<p class="text-warning">'.__('Нет данных...').'</p>';
        

    $PHPShopGUI->_CODE.=' 
         <div class="panel panel-default">
                <div class="panel-body">'.$alert.'
                            <!-- Progress -->
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                    Загрузка...
                                </div>
                            </div>   
                            <!--/ Progress -->
                 <div>
                     <canvas id="canvas" data-currency="' . $PHPShopSystem->getDefaultValutaCode() . '"  data-value=\'[' . substr($canvas_value, 0, strlen($canvas_value) - 1) . ']\' data-label=\'[' . substr($canvas_label, 0, strlen($canvas_label) - 1) . ']\'></canvas>
                 </div>
          </div>
       </div>
       <span id="export" data-export=\'[' . substr($canvas_export, 0, strlen($canvas_export) - 1) . ']\' data-path="exchange.export.order&return='.$_GET['path'].'"></spam>';
    


    // Статус заказа
    $PHPShopInterface->field_col = 1;
    $searchforma.=$PHPShopInterface->setInputDate("date_start", $date_start, 'margin-bottom:10px', null, 'Дата начала отбора');
    $searchforma.=$PHPShopInterface->setInputDate("date_end", $date_end, false, null, 'Дата конца отбора');

    $searchforma.= $PHPShopInterface->setSelect('where[statusi]', $order_status_value, 180);
    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'where[a.fio]', 'placeholder' => 'ФИО Покупателя', 'value' => $_GET['where']['a.io']));
    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'where[b.mail]', 'placeholder' => 'E-mail', 'value' => $_GET['where']['b.mail']));
    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'where[a.tel]', 'placeholder' => 'Телефон', 'value' => $_GET['where']['a.tel']));
    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'where[a.street]', 'placeholder' => 'Улица', 'value' => $_GET['where']['a.street']));

    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'hidden', 'name' => 'path', 'value' => $_GET['path']));
    $searchforma.=$PHPShopInterface->setButton(__('Показать'), 'search', 'btn-order-search pull-right');

    if ($clean)
        $searchforma.=$PHPShopInterface->setButton(__('Сброс'), 'remove', 'btn-order-cancel pull-left');

    $sidebarright[] = array('title' => 'Отчеты', 'content' => $PHPShopGUI->loadLib('tab_menu', false, './report/'));
    $sidebarright[] = array('title' => 'Интервал', 'content' => $PHPShopInterface->setForm($searchforma, false, "order_search", false, false, 'form-sidebar'));
    $PHPShopGUI->setSidebarRight($sidebarright, 2);

        // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);
    
    // Футер
    $PHPShopGUI->Compile($form = false);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();
?>