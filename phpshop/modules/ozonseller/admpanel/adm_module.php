<?php

include_once dirname(__FILE__) . '/../class/OzonSeller.php';

PHPShopObj::loadClass("order");
PHPShopObj::loadClass("delivery");

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $PHPShopOrm->update(array('version_new' => $new_version));
}

// Функция обновления
function actionUpdate() {
    global $PHPShopModules;

    if (!empty($_POST['load']))
           actionUpdateCategory();
    
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_system"));
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id=' . $_GET['id']);

    return $action;
}

function setChildrenCategory($tree_array,$parent_to) {
    global $PHPShopModules;

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_categories"));

    if (is_array($tree_array)) {
        foreach ($tree_array as $category) {
            $PHPShopOrm->insert(['name_new' => PHPShopString::utf8_win1251($category['title']), 'id_new' => $category['category_id'], 'parent_to_new' => $parent_to]);

            if (is_array($category['children'])) {
                foreach ($category['children'] as $children) {
                    $PHPShopOrm->insert(['name_new' => PHPShopString::utf8_win1251($children['title']), 'id_new' => $children['category_id'], 'parent_to_new' => $category['category_id']]);
                    if (is_array($children['children']))
                        setChildrenCategory($children['children'],$children['category_id']);
                }
            }
        }
    }
}

// Синхронизация категорий
function actionUpdateCategory() {
    global $PHPShopModules;

    $OzonSeller = new OzonSeller();
    $getTree = $OzonSeller->getTree();
    $tree_array = $getTree['result'];

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_categories"));
    $PHPShopOrm->debug = false;

    // Очистка
    $PHPShopOrm->query('TRUNCATE TABLE `' . $PHPShopModules->getParam("base.ozonseller.ozonseller_categories") . '`');
    
    if (is_array($tree_array)) {
        foreach ($tree_array as $category) {
            $PHPShopOrm->insert(['name_new' => PHPShopString::utf8_win1251($category['title']), 'id_new' => $category['category_id'], 'parent_to_new' => 0]);
            
            if (is_array($category['children'])) {
                foreach ($category['children'] as $children) {
                    $PHPShopOrm->insert(['name_new' => PHPShopString::utf8_win1251($children['title']), 'id_new' => $children['category_id'], 'parent_to_new' => $category['category_id']]);
                    if (is_array($children['children']))
                        setChildrenCategory($children['children'],$children['category_id']);
                }
            }
        }
    }

}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm,$PHPShopModules;

    $PHPShopGUI->field_col = 4;

    // Выборка
    $data = $PHPShopOrm->select();

    // Статус
    $status[] = [__('Новый заказ'), 0, $data['status']];
    $statusArray = (new PHPShopOrm('phpshop_order_status'))->getList(['id', 'name']);
    foreach ($statusArray as $statusParam) {
        $status[] = [$statusParam['name'], $statusParam['id'], $data['status']];
    }

    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('Новый заказ'), 0, $data['status']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $data['status']);


    $Tab1 = $PHPShopGUI->setField('Пароль защиты файла', $PHPShopGUI->setInputText($_SERVER['SERVER_NAME'] . '/yml/?marketplace=ozon&pas=', 'password_new', $data['password'],'100%'));
    $Tab1 .= $PHPShopGUI->setField('Client id', $PHPShopGUI->setInputText(false, 'client_id_new', $data['client_id'], '100%'));
    $Tab1 .= $PHPShopGUI->setField('API key', $PHPShopGUI->setInputText(false, 'token_new', $data['token'], '100%'));
    $Tab1 .= $PHPShopGUI->setField('Статус нового заказа', $PHPShopGUI->setSelect('status_new', $order_status_value, '100%'));
    
    $PHPShopOrmCat = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_categories"));
    $category = $PHPShopOrmCat->select(['COUNT(`id`) as num']);

    $Tab1 .= $PHPShopGUI->setField('База категорий', $PHPShopGUI->setText($category['num'].' '.__('записей в локальной базе'),null, false, false).'<br>'.$PHPShopGUI->setCheckbox('load', 1, 'Обновить базу категорий товаров для OZON', 0));

    $Tab1 = $PHPShopGUI->setCollapse('Настройки', $Tab1);

    $info = '<h4>Настройка модуля</h4>
    <ol>
        <li>Зарегистрироваться в <a href="https://seller.ozon.ru" target="_blank">OZON Seller</a></li>
        <li>В личном кабинете OZON Seller открыть <a href="https://seller.ozon.ru/app/settings/api-keys" target="_blank">Настройки - API ключи</a>, создать новый API ключ с правами Администратор (Обычный токен, дает доступ ко всем методам API). Необходимо скопировать значение ключа в поле <kbd>API key</kbd> и  значение Client Id в поле <kbd>Client Id</kbd> в настройках модуля.
        </li>
        <li>В настройках модуля включить опцию обновления базу категорий товаров для OZON и нажать <kbd>Сохранить</kbd>. Загрузка базы категорий может занять несколько секунд по причине большого количества категорий в OZON.</li>
        <li>В настройках модуля выбрать статус заказов, поступающих с OZON.</li>
    </ol>
    <h4>Настройка OZON Seller</h4>
    <ol>
      <li>Настроить <a href="https://seller.ozon.ru/app/products/import/products-feed-update" target="_blank">обновление данных</a> о товарах через фид. Добавить в качестве ссылки на фид адрес <code>http://'.$_SERVER['SERVER_NAME'].'/yml/?marketplace=ozon</code></li>
      <li>В личном кабинете OZON Seller в разделе <a href="https://seller.ozon.ru/app/warehouse" target="_blank">FBS - Логистика</a> добавить склад с именем "Основной".</li>
    </ol>
    
   <h4>Выгрузка товаров в OZON</h4>
   Ozon принимает данные по товарам с четко указанными данными по категориям и характеристикам из базы OZON.
   <ol>
    <li>В карточке редактирования категории в магазине сопоставить выбранную свою категорию с категорией OZON в закладке <kbd>OZON</kbd>, поле <kbd>Размещение в OZON</kbd>. Если выбор категорий пустой, то требуется загрузить базу категорий из настроек модуля. Сохранить выбор и перегрузить страницу, после чего появится блок "Сопоставление характеристик с OZON".</li>
    <li>Сопоставить или создать необходимые характеристики с указанными значениями. Проверить все доступные значения выбранной характеристики можно по ссылке "Доступные значения" под описанием характеристики OZON.</li>
    <li>В карточке редактирования товара в магазине через закладку "Модули - OZON" включить опцию <kbd>Включить экспорт в OZON</kbd> и сохранить данные. Если перегрузить сразу страницу, то выгрузка товара в OZON произойдет сразу и в поле "Статус товара" будет проставлен статус успешной выгрузки товара или выведена ошибка с описанием. Список товаров для выгрузки в OZON доступен в разделе "Модули - OZON Seller - Товары для OZON".</li>
    <li>После успешной выгрузки товары появятся в разделе <a href="https://seller.ozon.ru/app/products?filter=all" target="_blank">Список товаров</a> в OZON.</li>
  </ol>
  
  <h4>Загрузка заказов с OZON</h4>
   <ol>
    <li>Список заказов для загрузки из OZON доступен в разделе "Модули - OZON Seller - Заказы из OZON". По клику на номер заказа откроется карточка с описанием данных по заказу с OZON. Для загрузки заказа используется кнопка <kbd>Загрузить заказ</kbd>. Загруженный заказ будет иметь статус, выбранный в настройках модуля. В поле "Примечания администратора" загруженного заказа будет информация о загрузке с OZON и его номер. Для повторной загрузки заказа следует удалить его из базы заказов в магазине.</li>
    <li>В закладке "Дополнительно" предпросмотра заказа с OZON выводится полная информация по заказу в виде массива данных.</li>
  </ol>
';
    
    if($data['fee_type'] == 1){
        $status_pre='-';
    }
 
    else {
        $status_pre='+';
    }
    
    $Tab3= $PHPShopGUI->setCollapse('Настройка цен',
        $PHPShopGUI->setField('Колонка цен OZON', $PHPShopGUI->setSelect('price_new', $PHPShopGUI->setSelectValue($data['price'], 5), 100)) .
        $PHPShopGUI->setField('Наценка', $PHPShopGUI->setInputText($status_pre, 'fee_new', $data['fee'], 100, '%')).
        $PHPShopGUI->setField('Действие', $PHPShopGUI->setRadio("fee_type_new", 1, "Понижение", $data['fee_type']) . $PHPShopGUI->setRadio("fee_type_new", 2, "Повышение", $data['fee_type'])) 
            );

    $Tab2 = $PHPShopGUI->setInfo($info);

    // Форма регистрации
    $Tab4 = $PHPShopGUI->setPay(false, false, $data['version'], true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1.$Tab3, true, false, true), array("Инструкция", $Tab2), array("О Модуле", $Tab4));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
