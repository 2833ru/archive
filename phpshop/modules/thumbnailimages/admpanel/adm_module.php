<?php

include_once dirname(__DIR__) . '/class/ThumbnailImages.php';

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.thumbnailimages.thumbnailimages_system"));
$PHPShopOrm->debug=false;

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id=' . $_GET['id']);
    return $action;
}

function actionGenerateOriginal() {
    global $PHPShopOrm;

    $data = $PHPShopOrm->select();
    $thumbnailImages = new ThumbnailImages();
    $result = $thumbnailImages->generateOriginal();

    if('original' !== $data['last_operation']) {
        $data['processed'] = 0;
    }

    echo '<div class="alert alert-success" id="rules-message"  role="alert">' .
        __(sprintf('Выполнено. Обработано изображений: с %s до %s', (int) $data['processed'], (int) $data['processed'] + (int) $result['count']))
        . '</div>';

    if(count($result['skipped']) > 0) {
        $skipped = '';
        foreach ($result['skipped'] as $file) {
            $skipped .= 'Не найден файл: ' . $file . '<br>';
        }
        echo '<div class="alert alert-warning" id="rules-message"  role="alert">' .
            $skipped
            . '</div>';
    }
}
function actionGenerateThumbnail() {
    global $PHPShopOrm;

    $data = $PHPShopOrm->select();
    $thumbnailImages = new ThumbnailImages();
    $result = $thumbnailImages->generateThumbnail();

    if('thumb' !== $data['last_operation']) {
        $data['processed'] = 0;
    }

    echo '<div class="alert alert-success" id="rules-message"  role="alert">' .
        __(sprintf('Выполнено. Обработано изображений: с %s до %s', (int) $data['processed'], (int) $data['processed'] + (int) $result['count']))
        . '</div>';

    if(count($result['skipped']) > 0) {
        $skipped = '';
        foreach ($result['skipped'] as $file) {
            $skipped .= 'Не найден файл: ' . $file . '<br>';
        }
        echo '<div class="alert alert-warning" id="rules-message"  role="alert">' .
            $skipped
            . '</div>';
    }
}

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;

    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $PHPShopOrm->update(['version_new' => $new_version]);
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopOrm, $TitlePage, $select_name;
    
    // Выборка
    $data = $PHPShopOrm->select();

    $PHPShopGUI->action_button['Сгенерировать превью'] = [
        'name' => 'Сгенерировать превью',
        'action' => 'saveIDthumb',
        'class' => 'btn  btn-default btn-sm navbar-btn',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-import'
    ];

    $PHPShopGUI->action_button['Сгенерировать большие'] = [
        'name' => 'Сгенерировать большие',
        'action' => 'saveIDorig',
        'class' => 'btn  btn-default btn-sm navbar-btn',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-import'
    ];

    $PHPShopGUI->setActionPanel($TitlePage, $select_name, ['Сгенерировать превью','Сгенерировать большие', 'Сохранить и закрыть']);

    $Tab1 = '<div class="alert alert-info" role="alert">' .
               __('Пожалуйста, ознакомьтесь с информацией на вкладке <kbd>Описание</kbd> перед использованием модуля.')
            . '</div>';

    $Tab1 .= $PHPShopGUI->setField('Генерировать изображений за шаг', $PHPShopGUI->setInputText(false, 'limit_new', $data['limit'], 150));

    $Info = '<p>
        Модуль позволяет сгенерировать новые картинки по указанным в <kbd>Настройки</kbd> &rarr; <kbd>Изображения</kbd> параметрам.<br>
        Превью картинки для товаров в каталоге генерируются по такому сценарию:
        <ul>
            <li>Проверяется настройка <kbd>Сохранять исходное изображение при ресайзинге</kbd></li>
            <li>Если настройка включена - проверяется наличие файла картинки с суффиксом <code>_big</code>, это сохраненная картинка в оригинальном размере, для создания превью используется она.</li>
            <li>Если настройка отключена или изображения с суффиксом <code>_big</code> нет - для генерации превью изображения используется большая картинка товара, обрезанная согласно настройкам 
                <kbd>Макс. ширина оригинала</kbd> и <kbd>Макс. высота оригинала</kbd>.
            </li>
            <li>Все изображения товаров с суффиксом <code>_s</code> будут заменены новыми сгенерированными изображениями.</li>
        </ul>
        
       Генерация больших изображений возможна только, если включена настройка <kbd>Сохранять исходное изображение при ресайзинге</kbd> или уменьшены размеры 
       <kbd>Макс. ширина оригинала</kbd> и <kbd>Макс. высота оригинала</kbd> и необходимо сгенерировать меньшие изображения.
        </p>';

    $Tab2 = $PHPShopGUI->setInfo($Info);


    // Содержание закладки 2
    $Tab3 = $PHPShopGUI->setPay(false, true, $data['version'], true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(["Основное", $Tab1, true], ["Описание", $Tab2], ["О Модуле", $Tab3]);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveIDthumb", "Применить", "right", 80, "", "but", "actionGenerateThumbnail.modules.edit").
            $PHPShopGUI->setInput("submit", "saveIDorig", "Применить", "right", 80, "", "but", "actionGenerateOriginal.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>