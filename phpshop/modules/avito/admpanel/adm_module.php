<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.avito.avito_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate(number_format($option['version'], 1, '.', false));
    $PHPShopOrm->clean();
    $PHPShopOrm->update(array('version_new' => $new_version));
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $data = $PHPShopOrm->select();

    $Tab1 = $PHPShopGUI->setField('Пароль защиты XML файла', $PHPShopGUI->setInputText(
        'http://'.$_SERVER['SERVER_NAME'].'/phpshop/modules/avito/xml/appliances.php?pas=', 'password_new', $data['password'], 600)
    );
    $Tab1 .= $PHPShopGUI->setField('ФИО менеджера', $PHPShopGUI->setInputText( false, 'manager_new', $data['manager'], 600));
    $Tab1 .= $PHPShopGUI->setField('Телефон менеджера', $PHPShopGUI->setInputText( false, 'phone_new', $data['phone'], 600));
    $Tab1 .= $PHPShopGUI->setField('Адрес', $PHPShopGUI->setInputText( false, 'address_new', $data['address'], 600));
    $Tab1 .= $PHPShopGUI->setField('Шаблон генерации описания', '<div id="avitotitleShablon">
<textarea class="form-control avito-shablon" name="preview_description_template_new" rows="3" style="max-width: 600px;height: 70px;">' . $data['preview_description_template'] . '</textarea>
    <div class="btn-group" role="group" aria-label="...">
    <input  type="button" value="'.__('Описание').'" onclick="AvitoShablonAdd(\'@Content@\')" class="btn btn-default btn-sm">
    <input  type="button" value="'.__('Краткое описание').'" onclick="AvitoShablonAdd(\'@Description@\')" class="btn btn-default btn-sm">
    <input  type="button" value="'.__('Характеристики').'" onclick="AvitoShablonAdd(\'@Attributes@\')" class="btn btn-default btn-sm">
<input  type="button" value="'.__('Каталог').'" onclick="AvitoShablonAdd(\'@Catalog@\')" class="btn btn-default btn-sm">
<input  type="button" value="'.__('Подкаталог').'" onclick="AvitoShablonAdd(\'@Subcatalog@\')" class="btn btn-default btn-sm">
<input  type="button" value="'.__('Товар').'" onclick="AvitoShablonAdd(\'@Product@\',)" class="btn btn-default btn-sm">
<input  type="button" value="'.__('Артикул').'" onclick="AvitoShablonAdd(\'@Article@\',)" class="btn btn-default btn-sm">
    </div>
</div>
<script>function AvitoShablonAdd(variable) {
    var shablon = $(".avito-shablon").val() + " " + variable;
    $(".avito-shablon").val(shablon);
}</script>');

    // Инструкция
    $Tab2 = $PHPShopGUI->loadLib('tab_info', $data,'../modules/'.$_GET['id'].'/admpanel/');

    $Tab3 = $PHPShopGUI->setPay(false, false, $data['version'], true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1,true),array("Инструкция", $Tab2), array("О Модуле", $Tab3));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopModules, $PHPShopOrm;

    // Настройки витрины
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    $PHPShopOrm->debug = false;
    $_POST['region_data_new']=1;

    if (empty($_POST["use_params_new"]))
        $_POST["use_params_new"] = 0;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id='.$_GET['id']);
    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>