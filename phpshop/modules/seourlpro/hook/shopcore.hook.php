<?php

/*
 * SEO обработка ссылок в списке товаров /shop/
 */

function CID_Product_seourlpro_hook($obj, $row, $rout) {
    global $seourl_option;

    $catalog_name = $obj->PHPShopCategory->getName();
    $seo_name = $obj->PHPShopCategory->getParam('cat_seo_name');
    $obj->seo_name = $seo_name;


    // Настройки модуля
    include_once(dirname(__FILE__) . '/mod_option.hook.php');
    $PHPShopSeourlOption = new PHPShopSeourlOption();
    $seourl_option = $PHPShopSeourlOption->getArray();

    // Проверка уникальности SEO ссылки
    if ($rout == 'START') {
        if (!empty($seo_name))
            $url_true = '/' . $seo_name;
        else
            $url_true = '/' . $GLOBALS['PHPShopSeoPro']->setLatin($catalog_name);

        $url = $obj->PHPShopNav->getName(true);
        $url_true_nav = $url_true . '-' . $obj->PHPShopNav->getPage();
        $url_pack = '/shop/CID_' . $obj->PHPShopNav->getId();
        $url_nav = '/shop/CID_' . $obj->PHPShopNav->getId() . '_' . $obj->PHPShopNav->getPage();
        $url_old_seo = '/shop/CID_' . $obj->PHPShopNav->getId() . '_' . str_replace("-", "_", PHPShopString::toLatin($catalog_name));
        $url_old_seo_nav = '/shop/CID_' . $obj->PHPShopNav->getId() . '_' . $obj->PHPShopNav->getPage() . '_' . str_replace("-", "_", PHPShopString::toLatin($catalog_name));

        // Query
        if (!empty($_SERVER["QUERY_STRING"]))
            $url_query = '?' . $_SERVER["QUERY_STRING"];
        else
            $url_query = null;

        // Если ссылка не сходится
        if ($url != $url_true and $url != $url_pack and $url != $url_true_nav and $url != $url_nav and $url != $url_old_seo and $url != $url_old_seo_nav) {
            $obj->ListInfoItems = parseTemplateReturn($obj->getValue('templates.error_page_forma'));
            $obj->set('breadCrumbs', null);
            $obj->set('odnotipDisp', null);
            $obj->setError404();
            return true;
        } elseif ($url == $url_pack or $url == $url_nav or $url == $url_old_seo or $url == $url_old_seo_nav) {
            header('Location: ' . $obj->getValue('dir.dir') . $url_true_nav . '.html' . $url_query, true, 301);
            return true;
        }
    }


    if ($rout == 'END') {

        $page = $obj->PHPShopNav->getPage();

        // Рекомендации BDBD
        if ($seourl_option['paginator'] == 2) {
            if ($page > 1) {

                // Отключение описания каталога в пагинаторе
                $obj->set('catalogContent', null);

                // Добавление номера страниц в имя каталога
                $obj->set('catalogCategory', ' - страница ' . $page, true);
            }

            // Создание переменной точного адреса canonical для отсеивания дублей
            if (!empty($_SERVER["QUERY_STRING"]))
                $obj->set('seourl_canonical', '<link rel="canonical" href="http://' . $_SERVER['SERVER_NAME'] . $obj->get('ShopDir') . '/shop/CID_' . $obj->PHPShopNav->getId() . '-' . $page . $seo_name . '.html">');

            if (empty($page))
                $obj->set('seourl_canonical', '<link rel="canonical" href="http://' . $_SERVER['SERVER_NAME'] . $obj->get('ShopDir') . '/shop/CID_' . $obj->PHPShopNav->getId() . '-1' . $seo_name . '.html">');
        }

        // Учет модуля Mobile
        if (!empty($_GET['mobile']) and $_GET['mobile'] == 'true' and !empty($GLOBALS['SysValue']['base']['mobile']['mobile_system'])) {
            header('Location: ' . $obj->getValue('dir.dir') . '/shop/CID_' . $obj->PHPShopNav->getId() . '.html', true, 302);
            return true;
        }
    }
}

/**
 * SEO Навигация списка каталогов
 */
function CID_Category_seourlpro_hook($obj, $dataArray, $rout) {

    $catalog_name = $obj->PHPShopCategory->getName();
    $seo_name = $obj->PHPShopCategory->getParam('cat_seo_name');

    if ($rout == 'START') {

        $getNav = $GLOBALS['PHPShopSeoPro']->getNav();
        $file = $getNav['file'];
        $page = $getNav['page'];

        if (!empty($page)) {
            $obj->setError404();
            return true;
        }

        if (!empty($seo_name))
            $url_true = '/' . $seo_name;
        else
            $url_true = '/' . $GLOBALS['PHPShopSeoPro']->setLatin($catalog_name);

        $url = $obj->PHPShopNav->getName(true);
        $url_pack = '/shop/CID_' . $obj->PHPShopNav->getId();
        $url_old_seo = '/shop/CID_' . $obj->PHPShopNav->getId() . '_' . str_replace("-", "_", PHPShopString::toLatin($catalog_name));


        // Если ссылка не сходится
        if ($url != $url_true and $url != $url_pack and $url != $url_old_seo) {
            $obj->ListInfoItems = parseTemplateReturn($obj->getValue('templates.error_page_forma'));
            $obj->set('breadCrumbs', null);
            $obj->set('odnotipDisp', null);
            $obj->setError404();
            return true;
        } elseif ($url == $url_pack or $url == $url_old_seo) {
            header('Location: ' . $obj->getValue('dir.dir') . $url_true . '.html', true, 301);
            return true;
        }
    }

    if ($rout == 'END') {

        if (is_array($dataArray))
            foreach ($dataArray as $row) {

                if (!empty($row['cat_seo_name']))
                    $GLOBALS['PHPShopSeoPro']->setMemory($row['id'], $row['cat_seo_name']);
                else
                    $GLOBALS['PHPShopSeoPro']->setMemory($row['id'], $row['name']);
            }

        // Учет модуля Mobile
        if (!empty($_GET['mobile']) and $_GET['mobile'] == 'true' and !empty($GLOBALS['SysValue']['base']['mobile']['mobile_system'])) {
            header('Location: ' . $obj->getValue('dir.dir') . '/shop/CID_' . $obj->PHPShopNav->getId() . '.html', true, 302);
            return true;
        }
    }
}

/**
 * Проверка уникальности SEO имени товара
 */
function UID_seourlpro_hook($obj, $row, $rout) {
    if ($rout == 'END') {
        if (!empty($row['prod_seo_name']))
            $url_true = '/id/' . $row['prod_seo_name'] . '-' . $row['id'];
        else
            $url_true = '/id/' . $GLOBALS['PHPShopSeoPro']->setLatin($row['name']) . '-' . $row['id'];


        $url = $obj->PHPShopNav->getName(true);
        $url_pack = '/shop/UID_' . $obj->PHPShopNav->getId();
        $url_old_seo = '/shop/UID_' . $obj->PHPShopNav->getId() . '_' . str_replace("-", "_", PHPShopString::toLatin($row['name']));

        // Если ссылка не сходится
        if ($url != $url_true and $url != $url_pack and $url != $url_old_seo) {
            $obj->ListInfoItems = parseTemplateReturn($obj->getValue('templates.error_page_forma'));
            $obj->set('breadCrumbs', null);
            $obj->set('odnotipDisp', null);
            $obj->setError404();
        } elseif ($url == $url_pack or $url == $url_old_seo) {
            header('Location: ' . $url_true . '.html', true, 301);
            return true;
        }

        // Учет модуля Mobile
        if (!empty($_GET['mobile']) and $_GET['mobile'] == 'true' and !empty($GLOBALS['SysValue']['base']['mobile']['mobile_system'])) {
            header('Location: ' . $obj->getValue('dir.dir') . '/shop/UID_' . $obj->PHPShopNav->getId() . '.html', true, 302);
            return true;
        }
    }
}

$addHandler = array(
    'UID' => 'UID_seourlpro_hook',
    'CID_Category' => 'CID_Category_seourlpro_hook',
    'CID_Product' => 'CID_Product_seourlpro_hook',
);
?>