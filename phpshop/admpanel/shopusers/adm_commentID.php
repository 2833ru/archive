<?php

$TitlePage = __('Редактирование комментария #' . $_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['comment']);
PHPShopObj::loadClass('user');

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules;

    // Выборка
    $PHPShopOrm->sql = 'SELECT a.*, b.name as product, b.pic_small, b.description FROM ' . $GLOBALS['SysValue']['base']['comment'] . ' AS a 
        JOIN ' . $GLOBALS['SysValue']['base']['products'] . ' AS b ON a.parent_id = b.id    
            WHERE a.id=' . intval($_REQUEST['id']) . ' limit 1';
    $result = $PHPShopOrm->select();

    $data = $result[0];

    // Нет данных
    if (!is_array($data)) {
        header('Location: ?path=' . $_GET['path']);
    }

    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->setActionPanel(__("Покупатели") . ' / ' . __('Комментарии') . ' / ' . $data['name'], array('Удалить'), array('Сохранить и закрыть'));

    $media = '<div class="media">
  <div class="media-left">
    <a href="?path=product&id=' . $data['parent_id'] . '&return=' . $_GET['path'] . '">
      <img src="' . $data['pic_small'] . '" onerror="imgerror(this)" class="media-object" lowsrc="./images/no_photo.gif">
    </a>
  </div>
  <div class="media-body">
    <a class="media-heading" href="?path=product&id=' . $data['parent_id'] . '&return=' . $_GET['path'] . '">' . $data['product'] . '</a>
    ' . $data['description'] . '
  </div>
</div>';

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setCollapse(__('Информация'), $PHPShopGUI->setField("ФИО", $PHPShopGUI->setInput('text.required', "name_new", $data['name'])) .
            $PHPShopGUI->setField("Название", $media) .
            $PHPShopGUI->setField("Комментарий", $PHPShopGUI->setTextarea('content_new', $data['content'], false, '100%', 200)) .
            $PHPShopGUI->setField("Статус", $PHPShopGUI->setRadio("enabled_new", 1, "Вкл.", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "Выкл.", $data['enabled'])
    ));


    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("hidden", "parentID", $data['parent_id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.shopusers.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.shopusers.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.shopusers.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));

    // Пересчет рейтинга товара
    ratingUpdate();

    return array('success' => $action);
}

/**
 * Экшен сохранения
 */
function actionSave() {

    // Сохранение данных
    actionUpdate();

    header('Location: ?path=' . $_GET['path']);
}

/**
 * Пересчет рейтинга товара
 */
function ratingUpdate() {

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['comment']);
    $PHPShopOrm->debug = false;

    // Активация из спиcка
    if (empty($_POST['parentID'])) {
        $data = $PHPShopOrm->select(array('parent_id'), array('id' =>'='.intval($_POST['rowID'])), false, array('limit' => 1));
        if (!empty($data['parent_id']))
            $_POST['parentID'] = $data['parent_id'];
        else
            return false;
    }


    $result = $PHPShopOrm->query("select avg(rate) as rate, count(id) as num from " . $GLOBALS['SysValue']['base']['comment'] . " WHERE parent_id=" . intval($_POST['parentID']) . " AND enabled='1' AND rate>0 group by parent_id LIMIT 1");
    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_array($result);
        $rate = round($row['rate'], 1);
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
        $PHPShopOrm->debug = false;
        $PHPShopOrm->update(array('rate_new' => $rate, 'rate_count_new' => $row['num']), array('id' => '=' . $_POST['parentID']));
    } else {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
        $PHPShopOrm->debug = false;
        $PHPShopOrm->update(array('rate_new' => 0, 'rate_count_new' => 0), array('id' => '=' . $_POST['parentID']));
    }
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    
    // Пересчет рейтинга товара
    ratingUpdate();
    
    return array('success' => $action);
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>