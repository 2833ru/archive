<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.seourlpro.seourlpro_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $action = $PHPShopOrm->update(array('version_new' => $new_version));
    //return $action;
}

// Преобразование символов в латиницу
function setLatin($str) {
    $str = strtolower($str);
    $str = str_replace("/", "", $str);
    $str = str_replace("\\", "", $str);
    $str = str_replace("(", "", $str);
    $str = str_replace(")", "", $str);
    $str = str_replace(":", "", $str);
    $str = str_replace(" ", "-", $str);
    $str = str_replace("\"", "", $str);
    $str = str_replace(".", "", $str);
    $str = str_replace("«", "", $str);
    $str = str_replace("»", "", $str);
    $str = str_replace("ь", "", $str);
    $str = str_replace("ъ", "", $str);

    $_Array = array(
        "а" => "a",
        "б" => "b",
        "в" => "v",
        "г" => "g",
        "д" => "d",
        "е" => "e",
        "ё" => "e",
        "ж" => "zh",
        "з" => "z",
        "и" => "i",
        "й" => "i",
        "к" => "k",
        "л" => "l",
        "м" => "m",
        "н" => "n",
        "о" => "o",
        "п" => "p",
        "р" => "r",
        "с" => "s",
        "т" => "t",
        "у" => "u",
        "ф" => "f",
        "х" => "h",
        "ц" => "c",
        "ч" => "ch",
        "ш" => "sh",
        "щ" => "sh",
        "ы" => "y",
        "э" => "e",
        "ю" => "uy",
        "я" => "ya",
        "А" => "a",
        "Б" => "b",
        "В" => "v",
        "Г" => "g",
        "Д" => "d",
        "E" => "e",
        "Ё" => "e",
        "Ж" => "gh",
        "З" => "z",
        "И" => "i",
        "Й" => "i",
        "К" => "k",
        "Л" => "l",
        "М" => "m",
        "Н" => "n",
        "О" => "o",
        "П" => "p",
        "Р" => "r",
        "С" => "s",
        "Т" => "t",
        "У" => "u",
        "Ф" => "f",
        "Х" => "h",
        "Ц" => "c",
        "Ч" => "ch",
        "Ш" => "sh",
        "Щ" => "sh",
        "Э" => "e",
        "Ю" => "uy",
        "Я" => "ya",
        "." => "",
        "," => "",
        "$" => "i",
        "%" => "i",
        "&" => "and");

    $chars = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);

    foreach ($chars as $val)
        if (empty($_Array[$val]))
            @$new_str.=$val;
        else
            $new_str.=$_Array[$val];

    return $new_str;
}

// Автогенерация урлов каталогов фото
function setGenerationPhoto() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name22']);
    $data = $PHPShopOrm->select(array('id', 'name'), false, false, array('limit' => 1000));

    if (is_array($data))
        foreach ($data as $val) {
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name22']);
            $array['seoname_new'] = setLatin($val['name']);
            $PHPShopOrm->update($array, array('id' => '=' . $val['id']));
        }
}

// Автогенерация урлов каталогов
function setGeneration() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name']);
    $data = $PHPShopOrm->select(array('id', 'name'), false, false, array('limit' => 1000));

    if (is_array($data))
        foreach ($data as $val) {
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name']);
            $array['seoname_new'] = setLatin($val['name']);
            $PHPShopOrm->update($array, array('id' => '=' . $val['id']));
        }
}

// Автогенерация урлов новостей
function setGenerationNews() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name8']);
    $data = $PHPShopOrm->select(array('id', 'title'), false, false, array('limit' => 1000));

    if (is_array($data))
        foreach ($data as $val) {
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name8']);
            $array['seo_name_new'] = setLatin($val['title']);
            $PHPShopOrm->update($array, array('id' => '=' . $val['id']));
        }
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    // Автогенерация урлов
    if (!empty($_POST['generation']))
        setGeneration();
    if (!empty($_POST['generationnews']))
        setGenerationNews();
    if (!empty($_POST['generationphoto']))
        setGenerationPhoto();

    // Обновление SEO ссылок брендов
    $PHPShopOrmVendor = new PHPShopOrm($GLOBALS['SysValue']['base']['sort']);
    foreach ($_POST['sort_seo_name'] as $id=>$seo_url){
        $PHPShopOrmVendor->update(array("sort_seo_name_new" => "$seo_url"), array('id' => '=' . $id));
    }
    unset($_POST['sort_seo_name']);

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id='.$_GET['id']);
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();


    // Содержание закладки
    $Info = '<p>При включенном режиме "SEO пагинация" можно добавить переменную <code>@seourl_canonical@</code> в содержание тега <kbd>head</kbd> шаблона <code>phpshop/templates/имя шаблона/main/shop.tpl</code>. В результате будет добавлена ссылка <code>link rel="canonical"</code> с точным адресом страницы для отсеивания дублей страниц описания списка товаров при использовании фильтра сортировок товаров. Тег Canonical полностью дублирует функционал <kbd>robots.txt</kbd> и не обязателен при использовани заводского robots.txt. </p>';

    $Tab1 = $PHPShopGUI->setField('SEO пагинация', $PHPShopGUI->setRadio('paginator_new', 2, 'Включить', $data['paginator']) . $PHPShopGUI->setRadio('paginator_new', 1, 'Выключить', $data['paginator']),false,'Добавляет в теги Title и Description нумерацию страниц для уникальности индексации');
    $Tab1.=$PHPShopGUI->setField('Описание каталога на внутренних страницах', $PHPShopGUI->setRadio('cat_content_enabled_new', 1, 'Включить', $data['cat_content_enabled']) . $PHPShopGUI->setRadio('cat_content_enabled_new', 2, 'Выключить', $data['cat_content_enabled']),false,'Убирает описание каталога для внутренних страниц для сохранения уникальности первой.');
    $Tab1.= $PHPShopGUI->setField('Совет',$PHPShopGUI->setInfo($Info));
    $Tab1 .= $PHPShopGUI->setField('SEO пагинация брендов', $PHPShopGUI->setRadio('seo_brands_enabled_new', 2, 'Включить', $data['seo_brands_enabled']) . $PHPShopGUI->setRadio('seo_brands_enabled_new', 1, 'Выключить', $data['seo_brands_enabled']),false, false);

    $Tab2 = $PHPShopGUI->setPay($serial = false, false, $data['version'], true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("О Модуле", $Tab2));

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