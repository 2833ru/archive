<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sitemap.sitemap_system"));

function sitemaptime($nowtime) {
    return PHPShopDate::dataV($nowtime, false, true);
}

// Создание sitemap
function setGeneration() {
    global $PHPShopModules;

    $stat_products = null;
    $stat_pages = null;
    $stat_news = null;
    $stat_catalog = null;
    $seourl_enabled = false;
    $seourlpro_enabled = false;


    // Учет модуля SEOURL
    if (!empty($GLOBALS['SysValue']['base']['seourl']['seourl_system'])) {
        $seourl_enabled = true;
    }

    // Учет модуля SEOURLPRO
    if (!empty($GLOBALS['SysValue']['base']['seourlpro']['seourlpro_system'])) {
        $seourlpro_enabled = true;
    }


    // Библиотека
    $title = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $title.= '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">' . "\n";

    // Страницы
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name11']);
    $data = $PHPShopOrm->select(array('id,datas,link'), array('enabled' => "!='0'", 'category' => '!=2000'), array('order' => 'datas DESC'), array('limit' => 10000));

    if (is_array($data))
        foreach ($data as $row) {
            $stat_pages.= '<url>' . "\n";
            $stat_pages.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/page/' . $row['link'] . '.html</loc>' . "\n";
            $stat_pages.= '<lastmod>' . sitemaptime($row['datas']) . '</lastmod>' . "\n";
            $stat_pages.= '<changefreq>weekly</changefreq>' . "\n";
            $stat_pages.= '<priority>1.0</priority>' . "\n";
            $stat_pages.= '</url>' . "\n";
        }

    // Страницы каталоги
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page_categories']);
    $data = $PHPShopOrm->select(array('id,name'), false, false, array('limit' => 10000));

    $seourl = null;
    if (is_array($data))
        foreach ($data as $row) {


            if ($seourl_enabled)
                $seourl = '_' . PHPShopString::toLatin($row['name']);

            $stat_pages.= '<url>' . "\n";
            $stat_pages.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/page/CID_' . $row['id'] . $seourl . '.html</loc>' . "\n";
            $stat_pages.= '<changefreq>weekly</changefreq>' . "\n";
            $stat_pages.= '<priority>0.5</priority>' . "\n";
            $stat_pages.= '</url>' . "\n";
        }

    // Новости
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name8']);
    $data = $PHPShopOrm->select(array('id,datas,zag'), false, array('order' => 'datas DESC'), array('limit' => 10000));

    $seourl = null;
    if (is_array($data))
        foreach ($data as $row) {

            if ($seourl_enabled)
                $seourl = '_' . PHPShopString::toLatin($row['zag']);

            $stat_news.= '<url>' . "\n";
            $stat_news.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/news/ID_' . $row['id'] . $seourl . '.html</loc>' . "\n";
            $stat_news.= '<lastmod>' . sitemaptime(PHPShopDate::GetUnixTime($row['datas'])) . '</lastmod>' . "\n";
            $stat_news.= '<changefreq>daily</changefreq>' . "\n";
            $stat_news.= '<priority>0.5</priority>' . "\n";
            $stat_news.= '</url>' . "\n";
        }

    // Товары
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $data = $PHPShopOrm->select(array('*'), array('enabled' => "='1'", 'parent_enabled' => "='0'"), array('order' => 'datas DESC'), array('limit' => 100000));


    if (is_array($data))
        foreach ($data as $row) {
            $stat_products.= '<url>' . "\n";


            // Стандартный урл
            $url = '/shop/UID_' . $row['id'];

            // SEOURL
            if (!empty($seourl_enabled))
                $url.= '_' . PHPShopString::toLatin($row['name']);

            //  SEOURLPRO
            if (!empty($seourlpro_enabled)) {
                if (empty($row['prod_seo_name']))
                    $url = '/id/' . str_replace("_", "-", PHPShopString::toLatin($row['name'])) . '-' . $row['id'];
                else
                    $url = '/id/' . $row['prod_seo_name'] . '-' . $row['id'];
            }


            $stat_products.= '<loc>http://' . $_SERVER['SERVER_NAME'] . $url . '.html</loc>' . "\n";
            $stat_products.= '<lastmod>' . sitemaptime($row['datas']) . '</lastmod>' . "\n";
            $stat_products.= '<changefreq>daily</changefreq>' . "\n";
            $stat_products.= '<priority>1.0</priority>' . "\n";
            $stat_products.= '</url>' . "\n";
        }

    // Каталоги
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
    $data = $PHPShopOrm->select(array('*'), array('skin_enabled' => "='0'"), false, array('limit' => 10000));

    $seourl = null;
    if (is_array($data))
        foreach ($data as $row) {

            // Стандартный урл
            $url = '/shop/CID_' . $row['id'];

            // SEOURL
            if ($seourl_enabled)
                $url.= '_' . PHPShopString::toLatin($row['name']);

            //  SEOURLPRO
            if (!empty($seourlpro_enabled)) {
                if (empty($row['cat_seo_name']))
                    $url = '/' . str_replace("_", "-", PHPShopString::toLatin($row['name']));
                else
                    $url = '/' . $row['cat_seo_name'];
            }

            $stat_products.= '<url>' . "\n";
            $stat_products.= '<loc>http://' . $_SERVER['SERVER_NAME'] . $url . '.html</loc>' . "\n";
            $stat_products.= '<changefreq>weekly</changefreq>' . "\n";
            $stat_products.= '<priority>0.5</priority>' . "\n";
            $stat_products.= '</url>' . "\n";
        }


    $sitemap = $title . $stat_pages . $stat_news . $stat_products . '</urlset>';

    // Запись в файл
    if(fwrite(@fopen('../../UserFiles/Files/sitemap.xml', "w+"), $sitemap))
             echo '<div class="alert alert-success" id="rules-message"  role="alert">Файл <strong>sitemap.xm</strong> успешно создан.</div>';
    else echo '<div class="alert alert-danger" id="rules-message"  role="alert">Ошибка сохранения файла в папке UserFiles/File !</div>';
}

// Функция обновления
function actionUpdate() {

    setGeneration();
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $TitlePage, $select_name;

    $PHPShopGUI->action_button['Создать'] = array(
        'name' => 'Создать Sitemap',
        'action' => 'saveID',
        'class' => 'btn  btn-default btn-sm navbar-btn',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-import'
    );

    $PHPShopGUI->action_button['Открыть'] = array(
        'name' => 'Открыть Sitemap',
        'action' => '../../UserFiles/Files/sitemap.xml',
        'class' => 'btn  btn-default btn-sm navbar-btn btn-action-panel-blank',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-export'
    );

    $PHPShopGUI->setActionPanel($TitlePage, $select_name, array('Создать', 'Открыть', 'Закрыть'));

// Выборка
    $data = $PHPShopOrm->select();

    $Info = "
   1. Для автоматического создания sitemap.xml установите модуль <kbd>Cron</kbd> и добавьте в него новую задачу с адресом
        исполняемого файла:<br>  <code>phpshop/modules/sitemap/cron/sitemap_generator.php</code>
        <p>
   2. В поисковиках укажите адрес <code>http://" . $_SERVER['SERVER_NAME'] . "/UserFiles/Files/sitemap.xml</code> для автоматической обработки поисковыми ботами.
       </p>
   3. Установите опцию CHMOD 775 на папку /UserFiles/Files/ для записи в нее файла sitemap.xml";
    $Tab1 = $PHPShopGUI->setInfo($Info);

    $Tab2 = $PHPShopGUI->setPay();

// Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("О Модуле", $Tab2));

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