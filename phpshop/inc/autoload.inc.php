<?php

/**
 * Автозагрузка элементов
 * @package PHPShopInc
 */
// Защищаем от дублей /index.php/index.php
if (strstr($_SERVER['REQUEST_URI'], 'index.php')) {
    header('Location: /error/');
    exit();
}

// Шаблон дизайна по умолчанмю
$PHPShopCoreElement = new PHPShopCoreElement();
$PHPShopCoreElement->init('skin', false, false);
$PHPShopCoreElement->init('checkskin');
$PHPShopCoreElement->init('setdefault');

// Выбор шаблона
$PHPShopSkinElement = new PHPShopSkinElement();
$PHPShopSkinElement->init('skinSelect');

// Стили шаблона дизайна
$PHPShopCoreElement->init('pageCss', false, false);

// Загрузка модулей
PHPShopObj::loadClass('modules');
$PHPShopModules = new PHPShopModules();
$PHPShopModules->doLoad();

// Подключаем файлы autoload
foreach ($GLOBALS['SysValue']['autoload'] as $val)
    if (is_file($val))
        include_once($val);

// Выбор валюты
$PHPShopCurrencyElement = new PHPShopCurrencyElement();
$PHPShopCurrencyElement->init('valutaDisp');

// Авторизация пользователей
$PHPShopUserElement = new PHPShopUserElement();
$PHPShopUserElement->init('usersDisp');
$PHPShopUserElement->init('wishlist');

// Новинки в центр
$PHPShopProductIndexElements = new PHPShopProductIndexElements();
$PHPShopProductIndexElements->init('specMain');

// Последние покупки
$PHPShopProductIndexElements->init('nowBuy');

// Меню каталогов
$PHPShopShopCatalogElement = new PHPShopShopCatalogElement();
$PHPShopShopCatalogElement->init('leftCatal');
$PHPShopShopCatalogElement->init('leftCatalTable');

// Новинки в колонку
$PHPShopProductIconElements = new PHPShopProductIconElements();
$PHPShopProductIconElements->init('specMainIcon');

// Меню каталогов страниц
$PHPShopPageCatalogElement = new PHPShopPageCatalogElement();
$PHPShopPageCatalogElement->init('pageCatal');

// Опрос
$PHPShopOprosElement = new PHPShopOprosElement();
$PHPShopOprosElement->init('oprosDisp');

// Мини-новости
$PHPShopNewsElement = new PHPShopNewsElement();
$PHPShopNewsElement->init('miniNews');

// Слайдер
$PHPShopSliderElement = new PHPShopSliderElement();
$PHPShopSliderElement->init('imageSlider');

// Баннер
$PHPShopBannerElement = new PHPShopBannerElement();
$PHPShopBannerElement->init('banersDisp');

// Аналитика
$PHPShopAnalitica = new PHPShopAnalitica();

// Облако тегов
$PHPShopCloudElement = new PHPShopCloudElement();
$PHPShopCloudElement->init('cloud');

// Текстовый блок
$PHPShopTextElement = new PHPShopTextElement();
$PHPShopTextElement->init('leftMenu', true);
$PHPShopTextElement->init('rightMenu', true);
$PHPShopTextElement->init('topMenu', true);

// Корзина
$PHPShopCartElement = new PHPShopCartElement();
$PHPShopCartElement->init('miniCart');

// Фотогалерея
$PHPShopPhotoElement = new PHPShopPhotoElement();
$PHPShopPhotoElement->init('getPhotos');

// Recaptcha
$PHPShopRecaptchaElement = new PHPShopRecaptchaElement();
$PHPShopRecaptchaElement->init('captcha');

// RSS грабер новостей
new PHPShopRssParser();
?>