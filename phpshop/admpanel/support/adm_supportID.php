<?php

$TitlePage = __('Тикет в поддержку') . ' №' . $_GET['id'];

$licFile = PHPShopFile::searchFile('../../license/', 'getLicense', true);
@$License = parse_ini_file_true("../../license/" . $licFile, 1);

function actionStart() {
    global $PHPShopGUI, $License;

    $PHPShopGUI->addCSSFiles('./css/support.css');
    $PHPShopGUI->addJSFiles('./js/jquery.waypoints.min.js', './support/gui/support.gui.js');
    PHPShopObj::loadClass('xml');

    // Обшая инормация
    $path = 'https://help.phpshop.ru/base-xml-manager/search/xml.php?s=' . $License['License']['Serial'] . '&u=' . $License['License']['DomenLocked'] . '&id=' . intval($_GET['id']) . '&do=track';
    if(function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        $dataArrayTrack = readDatabase($data, 'row', false);
    } else {
        $dataArrayTrack = readDatabase($path, "row");
    }

    // Статус
    $status = $dataArrayTrack[0]['status'];
    $user = $dataArrayTrack[0]['name'];

    if ($status == 3)
        $status_off = 'hide';
    else
        $status_off = null;

    $status_array = array(
        1 => '<span class="text-warning">Ожидание ответа</span>',
        2 => '<span class="text-success">Есть ответ</span>',
        3 => '<span class="text-muted">Выполнено</span>',
    );

    $PHPShopGUI->action_button['Выполнено'] = array(
        'name' => 'Заявка выполнена',
        'class' => 'btn btn-default btn-sm navbar-btn support-close ' . $status_off,
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-ok',
        'tooltip' => 'data-toggle="tooltip" data-placement="bottom" title="' . __('Мой вопрос решен') . '"'
    );

    $PHPShopGUI->action_button['PUSH'] = array(
        'name' => 'Подписаться на уведомления',
        'class' => 'btn btn-default btn-sm navbar-btn btn-action-panel-blank '. $status_off,
        'type' => 'button',
        'action'=>'https://help.phpshop.ru/ticket/?track='.$dataArrayTrack[0]['trackid'],
        'icon' => 'glyphicon glyphicon-bell',
        'tooltip' => 'data-toggle="tooltip" data-placement="left" title="' . __('Перейдите по ссылке и разрешите получать уведомления') . '"'
    );

    // Нет данных
    if (!is_array($dataArrayTrack)) {
        header('Location: ?path=' . $_GET['path']);
        exit();
    }
    
    // Файлы
    if (!empty($dataArrayTrack[0]['attachments'])) {

        $attachments = explode(",", $dataArrayTrack[0]['attachments']);

        foreach ($attachments as $f) {

            $files = explode("#", $f);
            if (!empty($f))
                if (in_array(PHPShopSecurity::getExt($files[1]), array('gif', 'png', 'jpg', 'jpeg'))) {
                    $flist .= '<div class="col-xs-6 col-md-2">
                             <a href="https://help.phpshop.ru/download_attachment.php?att_id=' . $files[0] . '&track=' . $dataArrayTrack[0]['trackid'] . '" class="thumbnail" target="_blank" title="' . $files[1] . '"><img src="https://help.phpshop.ru/download_attachment.php?att_id=' . $files[0] . '&track=' . $dataArrayTrack[0]['trackid'] . '" alt="' . $files[1] . '" ></a></div>';
                } else {
                    $flist .= '<div class="col-xs-6 col-md-6"><a title="' . $files[1] . '" target="_blank" href="https://help.phpshop.ru/download_attachment.php?att_id=' . $files[0] . '&track=' . $dataArrayTrack[0]['trackid'] . '"><span class="glyphicon glyphicon-paperclip"></span> ' . $files[1] . '</a></div>';
                }
        }
    } else
        $flist = null;

    $message = ' 
          <div class="incoming_msg">
              <div class="received_msg">
                <div class="received_withd_msg">
                   <span class="time_date">' . $dataArrayTrack[0]['dt'] . '</span>
                  <p>' . preg_replace_callback("/@([a-zA-Z0-9_]+)@/", 'set_kbd', $dataArrayTrack[0]['message']) . '</p>
                    <span class="time_date">' . $flist . '</span>
                 </div>
              </div>
            </div>';

    // Переписка
    $path = 'https://help.phpshop.ru/base-xml-manager/search/xml.php?s=' . $License['License']['Serial'] . '&u=' . $License['License']['DomenLocked'] . '&id=' . intval($_GET['id']) . '&do=id';
    if(function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        $dataArray = readDatabase($data, 'row', false);
    } else {
        $dataArray = readDatabase($path, "row");
    }

    $PHPShopGUI->setActionPanel(__("Тикет") . " №" . $_GET['id'] . ' / ' . $dataArrayTrack[0]['subject'], false, array('PUSH','Выполнено'));

    if (is_array($dataArray))
        foreach ($dataArray as $row) {

            if (empty($row['message']))
                continue;
            else
                $row['message'] = preg_replace_callback("/@([a-zA-Z0-9_]+)@/", 'set_kbd', $row['message']);

            // Файлы
            if (!empty($row['attachments'])) {

                $attachments = explode(",", $row['attachments']);

                foreach ($attachments as $k => $f) {

                    $files = explode("#", $f);
                    if (!empty($f)) {

                        if (in_array(PHPShopSecurity::getExt($files[1]), array('gif', 'png', 'jpg', 'jpeg'))) {
                            $flist .= '<div class="col-xs-6 col-md-2">
                             <a href="https://help.phpshop.ru/download_attachment.php?att_id=' . $files[0] . '&track=' . $dataArrayTrack[0]['trackid'] . '" class="thumbnail" target="_blank" title="' . $files[1] . '"><img src="https://help.phpshop.ru/download_attachment.php?att_id=' . $files[0] . '&track=' . $dataArrayTrack[0]['trackid'] . '" alt="' . $files[1] . '" ></a></div>';
                        } else {
                            $flist .= '<div class="col-xs-6 col-md-6"><a title="' . $files[1] . '" target="_blank" href="https://help.phpshop.ru/download_attachment.php?att_id=' . $files[0] . '&track=' . $dataArrayTrack[0]['trackid'] . '"><span class="glyphicon glyphicon-paperclip"></span> ' . $files[1] . '</a></div>';
                        }
                    }
                }
            } else
                $flist = null;


            if (empty($row['staffid'])) {
                $message .= '
             <div class="incoming_msg">
              <div class="received_msg">
                <div class="received_withd_msg">
                   <span class="time_date">' . $row['dt'] . '</span>
                    <p>' . $row['message'] . '</p>
                    <span class="time_date"><div class="row">' . $flist . '</div></span>
                 </div>
              </div>
            </div>';
            } else {
                $message .= '
            <div class="outgoing_msg">
              <div class="sent_msg">
                <span class="time_date text-right">' . $row['name'] . ': ' . $row['dt'] . '</span>
                <p>' . $row['message'] . '</p>
                <span class="time_date"><div class="row">' . $flist . '</div></span>
               </div>
            </div>';
            }
        }

    $PHPShopGUI->_CODE = '

          ' . $message . '

          <a id="m"></a>
          <div class="' . $status_off . '">
          <p>
          <textarea class="form-control" name="message" 
          <textarea class="form-control" name="message" id="message" placeholder="' . __('Введите текст') . '"></textarea>
          </p>
          <p>
             <button class="btn btn-default pull-right send-message disabled" type="button" data-name="' . $user . '"><span class="glyphicon glyphicon-send"></span> ' . __('Отправить сообщение') . '</button>
             <a id="attachment-disp" class="text-muted" href="#f"><span class="glyphicon glyphicon-paperclip"></span> ' . __('Прикрепить файл') . '</a>
             <a id="f"></a>
             <div id="attachment" class="hide" style="max-width:70%">
             ' . $PHPShopGUI->setIcon(null, "attachment", false, array('load' => false, 'server' => true, 'url' => false, 'multi' => false, 'view' => false)) . '
             </div>
          </p>
          </div>
     
      ';


    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", $_GET['id']);

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция закрытия тикета
function actionClose() {
    global $License;

    $path = 'https://help.phpshop.ru/base-xml-manager/search/xml.php?s=' . $License['License']['Serial'] . '&u=' . $License['License']['DomenLocked'] . '&id=' . intval($_GET['id']) . '&do=close';

    if(function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_exec($ch);
        curl_close($ch);
    } else {
        readDatabase($path, "row");
    }

    return array("success" => true);
}

// Функция ответа на тикет
function actionReplies() {
    global $License;

    $path = 'https://help.phpshop.ru/base-xml-manager/search/xml.php?s=' . $License['License']['Serial'] . '&u=' . $License['License']['DomenLocked'] . '&id=' . intval($_GET['id']) . '&do=insert';
    $ch = curl_init();

    if (!empty($_POST['attachment'])) {
        $pathinfo = pathinfo($_POST['attachment']);
        $_POST['message'] .= '

<a href="http://' . $_SERVER['SERVER_NAME'] . $_POST['attachment'] . '" target="_blank"><span class="glyphicon glyphicon-paperclip"></span> ' . $pathinfo['basename'] . '</a>';
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
    curl_setopt($ch, CURLOPT_TIMEOUT, 600);
    curl_setopt($ch, CURLOPT_URL, $path);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $res = curl_exec($ch);
    curl_close($ch);
    return array("success" => true);
}

function set_kbd($var) {
    return '<kbd>' . $var[0] . '</kbd>';
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>