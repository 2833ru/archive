<?php

include_once dirname(__DIR__) . '/class/include.php';

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.elastic.elastic_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;

    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();

    return $PHPShopOrm->update(['version_new' => $new_version]);
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // Настройки витрины
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    if (empty($_POST["filter_show_counts_new"]))
        $_POST["filter_show_counts_new"] = 0;
    if (empty($_POST["filter_update_new"]))
        $_POST["filter_update_new"] = 0;
    if (empty($_POST["search_show_informer_string_new"]))
        $_POST["search_show_informer_string_new"] = 0;
    if (empty($_POST["ajax_search_categories_new"]))
        $_POST["ajax_search_categories_new"] = 0;
    if (empty($_POST["available_sort_new"]))
        $_POST["available_sort_new"] = 0;
    if (empty($_POST["use_additional_categories_new"]))
        $_POST["use_additional_categories_new"] = 0;
    if (empty($_POST["use_proxy_new"]))
        $_POST["use_proxy_new"] = 0;
    if (empty($_POST["search_uid_first_new"]))
        $_POST["search_uid_first_new"] = 0;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id=elastic');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $TitlePage, $select_name, $PHPShopBase;

    $PHPShopGUI->action_button['Экспортировать данные'] = [
        'name' => 'Экспортировать данные',
        'action' => 'importProducts',
        'class' => 'btn  btn-default btn-sm navbar-btn',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-import'
    ];

    $PHPShopGUI->addJSFiles('../modules/elastic/admpanel/gui/script.gui.js?v=1.0');
    $PHPShopGUI->setActionPanel($TitlePage, $select_name, ['Экспортировать данные', 'Сохранить и закрыть']);
    $Elastic = new Elastic();
    $client = null;
    $error = null;
    $info = null;

    // Выборка
    $data = $PHPShopOrm->select();

    if(!empty($data['api'])) {
        try {
            $client = $Elastic->client->getClientInfo();
        } catch (\Exception $exception) {
            $error = $exception->getMessage();
        }
        if(isset($client['data']['blocked']) && (bool) $client['data']['blocked'] === true) {
            $error = $client['data']['block_reason'];
        }
    } else {
        $info = __('Введите API ключ и нажмите "Сохранить" для доступа к настройкам.');
    }

    $registerLink = null;
    if(empty($data['api'])) {
        $registerLink = '<a href="https://elastica.host/register" target="_blank">' . __('Регистрация') . '</a>';
    }

    if ($PHPShopBase->getParam('template_theme.demo') == 'true') {
        $data['api'] = '';
    }

    $Tab1 = $PHPShopGUI->setField('API идентификатор', $PHPShopGUI->setInputText(false, 'api_new', $data['api'], 300, $registerLink));
    $Tab1 .= $PHPShopGUI->setField('Использовать Proxy', $PHPShopGUI->setCheckbox('use_proxy_new', 1, 'Использовать альтернативный адрес подключения', $data['use_proxy']), 1, 'Используйте только в том случае, если у вас наблюдаются проблемы с подключением к поисковому серверу.');

    if(!empty($error)) {
        $Tab1 .= sprintf('<div class="alert alert-danger" role="alert">%s</div>', $error);
    }
    if(!empty($info)) {
        $Tab1 .= sprintf('<div class="alert alert-info" role="alert">%s</div>', $info);
    }

    if(isset($client['data']['tariff']['filter_available']) && (bool) $client['data']['tariff']['filter_available'] === true) {
        $filterSettings = $PHPShopGUI->setField('Количество товаров', $PHPShopGUI->setCheckbox('filter_show_counts_new', 1, 'Добавлять количество товаров к значению характеристики', $data['filter_show_counts'])) .
            $PHPShopGUI->setField('Динамическое обновление', $PHPShopGUI->setSelect('filter_update_new', [
                ['Отключить', 0, $data['filter_update']],
                ['Блокировать значения', 1, $data['filter_update']],
                ['Скрывать значения', 2, $data['filter_update']]
            ], 300));
    } else {
        $filterSettings = sprintf('<div class="alert alert-danger" role="alert">%s</div>', __('Улучшенный фильтр товаров недоступен в вашем тарифном плане.'));
    }

    if(is_null($error) && is_null($info)) {
        $Tab1 .= $PHPShopGUI->setCollapse('Настройки поиска',
            $PHPShopGUI->setField('Товаров в ряд', $PHPShopGUI->setSelect('search_page_row_new', [
                [1, 1, $data['search_page_row']],
                [2, 2, $data['search_page_row']],
                [3, 3, $data['search_page_row']],
                [4, 4, $data['search_page_row']],
                [5, 5, $data['search_page_row']]
            ], 300)) .
            $PHPShopGUI->setField('Блок "Найдено в категориях"', $PHPShopGUI->setSelect('find_in_categories_new', [
                ['Не использовать', 0, $data['find_in_categories']],
                ['Отображение плитками', 1, $data['find_in_categories']],
                ['Отображение списком', 2, $data['find_in_categories']]
            ], 300)) .
            $PHPShopGUI->setField('Максимальное кол-во категорий в блоке "Найдено в категориях"', $PHPShopGUI->setInputText(false, 'max_categories_new', $data['max_categories'], 300)) .
            $PHPShopGUI->setField('Товаров на странице', $PHPShopGUI->setInputText(false, 'search_page_size_new', $data['search_page_size'], 300)) .
            $PHPShopGUI->setField('Количество опечаток', $PHPShopGUI->setInputText(false, 'misprints_new', $data['misprints'], 300)) .
            $PHPShopGUI->setField('Опечаток в быстром поиске', $PHPShopGUI->setInputText(false, 'misprints_ajax_new', $data['misprints_ajax'], 300)) .
            $PHPShopGUI->setField('Учитывать опечатку при длине поискового запроса от', $PHPShopGUI->setInputText(false, 'misprints_from_cnt_new', $data['misprints_from_cnt'], 300)) .
            $PHPShopGUI->setField('Информационная строка', $PHPShopGUI->setCheckbox('search_show_informer_string_new', 1, 'Отображать строку "Найдено XX результатов в XX категориях."', $data['search_show_informer_string'])) .
            $PHPShopGUI->setField('Дополнительные категории', $PHPShopGUI->setCheckbox('use_additional_categories_new', 1, 'Отображать дополнительные категории товаров', $data['use_additional_categories'])) .
            $PHPShopGUI->setField('Категории в быстром поиске', $PHPShopGUI->setCheckbox('ajax_search_categories_new', 1, 'Добавить в результаты быстрого поиска категории соответствующие поисковому запросу', $data['ajax_search_categories'])) .
            $PHPShopGUI->setField('Товаров в быстром поиске', $PHPShopGUI->setInputText(false, 'ajax_search_products_cnt_new', $data['ajax_search_products_cnt'], 300)) .
            $PHPShopGUI->setField('Файл фильтр поиска', $PHPShopGUI->setInputText(false, 'search_filter_new', $data['search_filter'], 300), 1, 'Файл с уникальной логикой поиска. Должен имплементировать интерфейс ElasticSearchFilterInterface.php') .
            $PHPShopGUI->setField('Файл фильтр быстрого поиска', $PHPShopGUI->setInputText(false, 'ajax_search_filter_new', $data['ajax_search_filter'], 300), 1, 'Файл с уникальной логикой быстрого поиска. Должен имплементировать интерфейс ElasticAjaxSearchFilterInterface.php') .
            $PHPShopGUI->setField('Сначала в наличии', $PHPShopGUI->setCheckbox('available_sort_new', 1, 'Выводить сначала товары в наличии', $data['available_sort'])) .
            $PHPShopGUI->setField('Искать сначала по артикулу', $PHPShopGUI->setCheckbox('search_uid_first_new', 1, 'Сначала искать по точному совпадению артикула', $data['search_uid_first']))
        );

        $Tab1 .= $PHPShopGUI->setCollapse('Настройки фильтра товаров', $filterSettings);
    }

    $Tab2 = '<div class="form-group form-group-sm"><div class="col-sm-12" style="padding-left: 20px;padding-right: 20px;">
                ' . $PHPShopGUI->setTextarea('synonyms_new', $data['synonyms'], true, '100%', 300,
                        'Через запятую слово и синоним. Каждая новая пара с новой строки. Например:<br> томат, помидор <br> 
                            ryzen, райзен') .
              '</div></div>';

    $info = '<h4>Настройка модуля</h4>
    <ol>
        <li><a href="https://elastica.host/register" target="_blank">Зарегистрировать аккаунт</a> 
        и выбрать тарифный план. После выбора тарифного плана будет автоматически активирован демонстрационный режим длительностью 14 дней.</li>
        <li>В <a href="https://elastica.host/personal" target="_blank">Личном кабинете</a> открыть "Параметры интеграции" и скопировать <kbd>API идентификатор</kbd> в поле <kbd>API идентификатор</kbd> настроек модуля. Нажать кнопку "Сохранить".</li>
        <li>Настроить количество товаров в ряд, количество товаров на странице поиска.</li>
        <li>Над списком товаров в результатах поиска доступен блок <kbd>Найдено в категориях</kbd>. Это список категорий с количеством найденных в них товаров. По нажатию на каталог поиск будет ограничен выбранной категорией. 
        Блок <kbd>Найдено в категориях</kbd> доступен в двух вариантах <kbd>Отображение плитками</kbd> с картинками и <kbd>Отображение списком</kbd> для компактного вывода в интернет-магазинах с очень большим ассортиментом.</li>
        <li>Включение опции <kbd>Дополнительные категории</kbd> позволяет добавить вывод дополнительных категорий товара в блок <kbd>Найдено в категориях</kbd>.</li>
        <li>Нажать <kbd>Экспортировать данные</kbd> и дождаться выполнения экспорта данных в поисковый сервис.</li>
    </ol>
    <h4>Фильтр товаров</h4>
    <ol>
      <li>Улучшенный фильтр товаров доступен в тарифных планах <kbd>Бизнес</kbd> и <kbd>Мега</kbd>.</li>
        <li>В фильтре товаров скрываются значения характеристик, под которые нет товара в каталоге. 
        Пользователь не увидит и не сможет выбрать характеристику, под которую нет товаров, а вам не нужно будет создавать 
        одинаковые характеристики, например "Производитель", под каждую категорию.</li>
        <li>Доступна опция подсчета количества товаров для каждого значения характеристики.</li>
        <li>Доступна опция автоматического обновления фильтра товаров при выборе значений. Если пользователь выбрал значение 
        характеристики в фильтре - значения других характеристик, под которые нет товаров совместно с выбранной характеристикой, будут заблокированы или скрыты. 
        Если включена опция <kbd>Количество товаров</kbd> количество товаров будет автоматически пересчитано.</li>
    </ol>';

    $Tab3 = $PHPShopGUI->setInfo($info);

    // Форма регистрации
    $Tab4 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(["Основное", $Tab1, true], ["Синонимы", $Tab2], ["Инструкция", $Tab3], ["О Модуле", $Tab4]);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>