<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.socauth.socauth_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $upArr['authConfig_new'] = serialize($_POST['authConfig']);
    // запоминпаем данные по соц сетям
    $action = $PHPShopOrm->update($upArr, array('id' => '= 1'));
    header('Location: ?path=modules&install=check');
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;


// Выборка
    $data = $PHPShopOrm->select();

    $authConfig = unserialize($data['authConfig']);
    $fcCongif = $authConfig['facebook'];
    $twCongif = $authConfig['twitter'];
    $vkCongif = $authConfig['vk'];

    $Tab1 = $PHPShopGUI->setCollapse('Настройка Facebook', $PHPShopGUI->setField('App ID', $PHPShopGUI->setInput("text", "authConfig[facebook][appid]", $fcCongif['appid'], '', 300)) . $PHPShopGUI->setField('Secret ID', $PHPShopGUI->setInput("text", "authConfig[facebook][secret]", $fcCongif['secret'], '', 300)));

    $Tab1.= $PHPShopGUI->setCollapse('Настройка Twitter', $PHPShopGUI->setField('Consumer key', $PHPShopGUI->setInput("text", "authConfig[twitter][key]", $twCongif['key'], '', 300)) . $PHPShopGUI->setField('Consumer secret', $PHPShopGUI->setInput("text", "authConfig[twitter][secretkey]", $twCongif['secretkey'], '', 300)));

    $Tab1.= $PHPShopGUI->setCollapse('Настройка ВКонтакте', $PHPShopGUI->setField('ID приложения', $PHPShopGUI->setInput("text", "authConfig[vk][client_id]", $vkCongif['client_id'], '', 300)) . $PHPShopGUI->setField('Защищенный ключ', $PHPShopGUI->setInput("text", "authConfig[vk][client_secret]", $vkCongif['client_secret'], '', 300)));



    $Info = '<h4>Настройка шаблона</h4>
Чтобы добавить ссылки на авторизацию через социальные сети в форму авторизации на сайте, нужно в шаблоне <code>users/users_forma.tpl</code>
вставить метки:<br> <kbd>@facebookAuth@</kbd>, <kbd>@twitterAuth@</kbd>, <kbd>@vkontakteAuth@</kbd>.
<h4>Настройка twitter.com</h4>
<ol>
<li>авторизоваться в твиттере</li>
<li>перейти по ссылке <a href="https://dev.twitter.com/apps/new?from=phpshop" target="_blank">https://dev.twitter.com/apps/new</a></li>
<li>заполнить все поля на своё усмотрение, кроме поля "Callback URL:"</li>
<li>в "Callback URL:" необходимо указать ссылку возврата на Ваш сайт: <code>http://'.$_SERVER['SERVER_NAME'].'/socauth/twitter/</code>
<li>в поле "WebSite: *" нужно указать адрес Вашего сайта: <code>http://'.$_SERVER['SERVER_NAME'].'</code>
<li>после того, как приложение запустится, откроется страница настроек
<li>данные поля настраиваются на свое усмотрение
<li>со страницы настроек необходимо взять параметры <b>Consumer key</b>, <b>Consumer secret</b>
<li>указанные в предыдущем меню параметры, необходимо прописать в соответствующие поля в настройке модуля авторизации через соцсети во вкладке "основное"
</ol>
<h4>Настройка facebook.com</h4>
<ol>
<li>авторизоваться в фейсбуке
<li>перейти по ссылке <a href="https://developers.facebook.com/apps" target="_blank">https://developers.facebook.com/apps</a>
<li>создать приложение (App Name может быть любым)
<li>заполнить поля из раздела основной информации нужными данными
<li>в поле "App Domains" необходимо указать Ваш домен (если сайт лежит ну субдомене, нужно указывать только основной), например: 
сайт лежит на test.'.$_SERVER['SERVER_NAME'].', нужно указать '.$_SERVER['SERVER_NAME'].'
<li>все поля ниже раздела основных данных оставить без изменения, кроме поля "Site URL"<br><br>
<li>в поле "Site URL" необходимо указать полностью адрес Вашего домена: <code>http://'.$_SERVER['SERVER_NAME'].'</code>
<li>из созданного приложения взять данные <b>App ID</b>, <b>App Secret</b>
<li>указанные в предыдущем меню параметры, необходимо прописать в соответствующие поля в настройке модуля авторизации через соцсети во вкладке "основное"
</ol>
<h4>Настройка vk.com</h4>
<ol>
<li>авторизоваться в vk.com
<li>перейти по ссылке <a href="http://vk.com/editapp?act=create" target="_blank">http://vk.com/editapp?act=create</a>
<li>создать приложение типа веб-сайт (Название может быть любым)
<li> в поле "Адрес сайта" необходимо указать полностью адрес Вашего домена: <code>http://'.$_SERVER['SERVER_NAME'].'</code>
<li>в поле "Базовый домен" необходимо указать Ваш домен (если сайт лежит на субдомене, нужно указывать только основной), например: сайт лежит на test.'.$_SERVER['SERVER_NAME'].', нужно указать '.$_SERVER['SERVER_NAME'].'
<li>из созданного приложения взять данные <b>ID приложения</b>, <b>Защищенный ключ</b>
<li>указанные в предыдущем меню параметры необходимо прописать в соответствующие поля в настройке модуля авторизации через соцсети во вкладке "основное"
</ol>';

    $Tab2.=$PHPShopGUI->setInfo($Info);

    $Tab3 = $PHPShopGUI->setPay();

    // История изменений
    //$Tab3.= $PHPShopGUI->setHistory();


// Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Инструкции", $Tab2), array("О Модуле", $Tab3));

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
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>


