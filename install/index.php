<?
error_reporting(0);
$_classPath="../phpshop/";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
$version=substr($GLOBALS['SysValue']['upload']['version'],1,1);



// Апач
if(eregi('Apache', $_SERVER['SERVER_SOFTWARE'])) $API="............<img src=\"rewritemodtest/icon-activate.gif\" border=0 align=absmiddle> <b class='ok'>Ok</b>";
else $API="............<img src=\"rewritemodtest/errormessage.gif\"  border=0 align=absmiddle> <b class='error'>Error</b>";

// Версия PHP
$phpversion=substr(phpversion(),0,1);
if($phpversion >= 4) $php="............<img src=\"rewritemodtest/icon-activate.gif\" border=0 align=absmiddle> <b class='ok'>Ok</b>";
else $php="............<img src=\"rewritemodtest/errormessage.gif\"  border=0 align=absmiddle> <b class='error'>Error</b>";

// Версия MySQL
@mysql_connect ($SysValue['connect']['host'], $SysValue['connect']['user_db'],  $SysValue['connect']['pass_db']);
@mysql_select_db($SysValue['connect']['dbase']);
@mysql_query("SET NAMES 'cp1251'");

if(@mysql_get_server_info()) {
    $mysqlversion=substr(@mysql_get_server_info(),0,1);
    if($mysqlversion >= 4) $mysql="............<img src=\"rewritemodtest/icon-activate.gif\" border=0 align=absmiddle> <b class='ok'>Ok</b>";
    else $mysql="............<img src=\"rewritemodtest/errormessage.gif\"  border=0 align=absmiddle> <b class='error'>Error</b>";
}else $mysql="...............?";

// Rewrite
$path_parts = pathinfo($_SERVER['PHP_SELF']);
$filename =  "http://".$_SERVER['SERVER_NAME'].$path_parts['dirname']."/rewritemodtest/test.html";
if (@fopen($filename,"r")) $rewrite="............<img src=\"rewritemodtest/icon-activate.gif\" border=0 align=absmiddle> <b class='ok'>Ok</b>";
else $rewrite="............<img src=\"rewritemodtest/errormessage.gif\"  border=0 align=absmiddle> <b class='error'>Error</b>";

//GD Support
$GD=gd_info();
if($GD['GD Version']!="")
    $gd_support="............<img src=\"rewritemodtest/icon-activate.gif\" border=0 align=absmiddle> <b class='ok'>Ok</b>";
else  $gd_support="............<img src=\"rewritemodtest/errormessage.gif\"  border=0 align=absmiddle> <b class='error'>Error</b>";

//FreeType Support
if($GD['FreeType Support'] === true)
    $gd_freetype_support="............<img src=\"rewritemodtest/icon-activate.gif\" border=0 align=absmiddle> <b class='ok'>Ok</b>";
else  $gd_freetype_support="............<img src=\"rewritemodtest/errormessage.gif\"  border=0 align=absmiddle> <b class='error'>Error</b>";

//FreeType Linkage
if($GD['FreeType Linkage'] == "with freetype")
    $gd_freetype_linkage="............<img src=\"rewritemodtest/icon-activate.gif\" border=0 align=absmiddle> <b class='ok'>Ok</b>";
else  $gd_freetype_linkage="............<img src=\"rewritemodtest/errormessage.gif\"  border=0 align=absmiddle> <b class='error'>Error</b>";

// XML Support
if(function_exists("xml_parser_create"))
    $xml_support="............<img src=\"rewritemodtest/icon-activate.gif\" border=0 align=absmiddle> <b class='ok'>Ok</b>";
else  $xml_support="............<img src=\"rewritemodtest/errormessage.gif\"  border=0 align=absmiddle> <b class='error'>Error</b>";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
    <head>
        <title>Установка <?= $SysValue['license']['product_name']." (сборка ". $SysValue['upload']['version'].")"?></title>
        <META http-equiv="Content-Type" content="text-html; charset=windows-1251">
        <style>
            body, pre, html, td
            {
                font-family: Tahoma;
                height:100%;
                margin:10px;
                padding:0px;
                font-size: 12px;
                background-color: #FFFFFF;
            }

            a{
                color: #013784;
            }

            a:hover{
                text-decoration: none;
            }


            .title{
                background-image: url(rewritemodtest/logo.jpg);
                background-repeat: no-repeat;
                font-size: 12px;
                color: #FFFFFF;
                height: 60px;
                background-color: #2162A4;
                display: block;
            }

            .footer{
                background-image: url(rewritemodtest/logo.jpg);
                background-repeat: no-repeat;
                font-size: 12px;
                color: #FFFFFF;
                background-color: #2162A4;
                padding: 10px;
            }

            .menu{
                background-color: #F89A29;
                color: white;
                padding:3px;
                clear:both;
            }

            .menu a{
                color: white;
            }

            .title h1{
                font-size: 20px;
                margin: 0px;
            }



            h2{
                background: #2162A4;
                color: ffffff;
                font-weight: bold;
                font-size: 120%;
                padding: 3px 20px;
                margin-bottom: 10px;
                border-bottom: 1px solid black;
                letter-spacing: 2px;
            }

            .v{
                font-size: 30px;
                font-weight: bold;
            }

            li{
                text-decoration: none;
                list-style: square;
            }
            pre p, p.pre{
                background: #F5F5F5;
                border-left-width: 1px;
                border-left-color: #000000;
                border-left-style: dashed;
                padding: 10px;
                font-size: 12px;
            }

            .info{
                border-width: 1px;
                background: #F5F5F5;
                border-color: #660033;
                border-style: dashed;
                padding: 10px;
            }
            .ok{
                color: green;
                font-weight: bold;
            }
            .error{
                color: red;
                font-weight: bold;
            }
        </style>
        <script>
            function miniWin(url,w,h)
            {
                window.open(url,"_blank","left=300,top=100,width="+w+",height="+h+",location=0,menubar=0,resizable=1,scrollbars=0,status=0,titlebar=0,toolbar=0");
            }
        </script>
    <body>
        <div class="title">
            <div style="float:left;padding: 10px;padding-left: 15px" >
                PHPShop&copy; Software -  PHPShop&copy; Enterprise
                <h1>Установка PHPShop Software</h1>
            </div><div style="float:right;padding: 10px; padding-right: 15px" >версия&nbsp;&nbsp;&nbsp;<span class="v" >3.<?=$version;?></span></div>
        </div>

        <div align="right" class="menu">
            <a href="http://www.phpshop.ru/" target="_blank" title="Разработчик">Домой</a> | <a href="http://help.phpshop.ru/" target="_blank" title="Техническая поддержка">Техническая поддержка</a> | <a href="http://www.phpshop.ru/help/" target="_blank" title="Учебник">Учебные материалы</a> | <a href="#" onclick="window.print();return false;" title="Печать">Печать страницы</a>
        </div><div style="clear: both"></div>

        <table>
            <tr>
                <td><img src="rewritemodtest/box.gif" alt="PHPShop SoftWare Box" width="120" height="143" border="0" align="left" hspace="10"></td>
                <td>
                    <p><strong>Установщик PHPShop SoftWare приветсвует Вас</strong>.<br>
	На этой странице вы найдет всю необходимую информацию, которая поможет вам установить и настроить Интернет-магазин на своем сайте.</p> 
	Ниже приведена инструкция для ручной установки PHPShop Software на виртуальный сервер <a href="http://www.phpshop.ru/help/Content/install/denwer_zend_optimizer.html" target="_blank">Denwer</a> или на хостинг провайдера.<br>
                    Для <strong>упрощенной установки скрипта</strong>  следует воспользоваться готовой программой <a href="http://www.phpshop.ru/help/Content/install/phpshop_server.html" target="_blank">PHPShop Installer</a> из оболочки Windows (для начинающих пользователей).<br>
                    Для упрощенной установки скрипта на Unix-сервер через SSH следует воспользоваться <a href="http://www.phpshop.ru/help/Content/install/phpshop_unix.html">PHPShop Unix Installer</a> (для опытных пользователей).
                </td>
            </tr>
        </table>



        <h2>Системные требования</h2>
        <p>
        <ol>
            <li> Apache => 1.3.*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$API?>
            <li> MySQL => 4.* <?=$mysql?>
            <li> PHP => 4.* &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$php?>
            <li> RewriteEngine ON для Apache&nbsp;&nbsp;&nbsp;<?=$rewrite?>
            <li>GD Support для PHP <?=$gd_support?>
            <li>FreeType Support для PHP <?=$gd_freetype_support?>
            <li>FreeType Linkage для PHP <?=$gd_freetype_linkage?>
            <li>XML Parser для PHP <?=$xml_support?>
                <p>* => означет, что версия приложения больше или равно указанного параметра.<br>
                    Расшифровка: <img src="rewritemodtest/icon-activate.gif" border=0 align=absmiddle> <b class='ok'>Ok</b> - тест пройден,
                    <img src="rewritemodtest/errormessage.gif"  border=0 align=absmiddle> <b class='error'>Error</b> - тест не пройден (возможны проблемы при работе скрипта, обратитесь к документации сервера или свяжитесь с администратором сервера)

                <p><img src="rewritemodtest/php.png" border=0 align=absmiddle> <a href="rewritemodtest/rewritemodtest.php" target="_blank">Показать информацию о сервере</a><br>
                    <img src="rewritemodtest/icon-activate.gif" border=0 align=absmiddle><a href="http://www.phpshop.ru/docs/hosting.html" target="_blank">Список протестированных хостингов </a></p>

                </p>
                </p>
        </ol>


    </p>

    <h2>Установка скрипта в ручном режиме</h2>
    <ol>
        <p>Если вы не хотите или по каким-то причинам не можете воспользоваться <strong>готовой программой для установки</strong> <a href="http://www.phpshop.ru/help/Content/install/phpshop_server.html" target="_blank">PHPShop Installer</a> на свой FTP - сервер  из оболочки Windows, то приведенная ниже информация поможет вам выполнить установку в ручном режиме (для опытных пользователей).</p>
        <li>Подключиться к своему серверу через FTP-клиент (CuteFTP, Total Commander и др.)
        <li>Загрузить распакованный архив в <strong>бинарном/двоичном режиме</strong> (задается в настройках FTP клиента)</a>
        <li>Создайте новую базу MySQL на своем сервере или узнайте пароли доступа к уже созданной базе у хост-провайдера.
        <li>

            Отредактируйте файл связи с базой MySQL "<strong>config.ini</strong>", лежащий в папке "ваш_сайт/phpshop/inc/config.ini". Изменить данные в кавычках " " на свои данные.

            <p class=pre>
                [connect]<br>
                host="localhost";             # имя хоста<br>
                user_db="user";         # имя пользователя<br>
                pass_db="mypas";            # пароль базы<br>
                dbase="mybase";           # имя базы</p>

        </li>
        <li>
            Воспользуйтесь встроенным PHP <img src="rewritemodtest/icon-setup.gif" border=0 align=absmiddle> <a href="javascript:miniWin('./install.php',600,570)">инсталлятором</a> (имя_сайта/install/install.php) для установки базы.<br>
            Внимание, инсталлятор запускать необходимо, в противном случае не будет создан образ БД. <br><br>
        </li>
        <li>В целях безопасности удалите папку /install
        <li>Установите опцию CMOD 777 (UNIX сервера) для папок:
            <br><br>
            <ol>
                <li>license
                <li>UserFiles/Image
                <li>UserFiles/File
                <li>phpshop/admpanel/csv
                <li>files/price
                <li>phpshop/admpanel/dumper/backup
                <li>payment/paymentlog.log
                <li>backup/backups
                <li>backup/cache
                <li>backup/temp
                <li>backup/upd_log.txt
                <li>backup/upd_log_backup.txt
            </ol>
            <br><br>
        <li>Для входа в <b>административную панель</b> нажмите комбинацию клавиш Ctrl + F12 или по ссылке: имя_сайта/phpshop/admpanel/<br>
            Пользователь и пароль задается при установке скрипта.<br>
            При установке пользователь и пароль задается в ручном режиме. По желанию, регистрационные данные отсылаются на e-mail. После смены пароля требуется перезапуск браузера.<br><br>

        <li>Реализована возможность <strong>размещение 2-х и более независимых интернет-магазинов</strong> в любых директориях домена. Данная особенность позволяет создавать многоязычные проекты и гиппермаркеты,&nbsp;используя одну <A href="../index.html">лицензию</A>.<BR><BR>Для задания папки размещения требуется выполнить всего несколько шагов:<BR><BR>

            <ol>
                <li>Копируем скрипт в любую директорию, например /market/<br>
                    Внимание, использование зарегистрированных ссылок с именами shop, news, gbook, spec, users -  <strong>запрещено.</strong>
                <li>Библиотеку /market/phpshop/lib/ копируем в корень /phpshop/lib/
                <li>В файле конфигурации /market/phpshop/inc/config.ini указываем имя директории, куда установлен скрипт
                    <p class=pre>[dir]
                        dir="/market";
                    </p>
                <li>В файле java/java2.js указываем имя директории, куда установлен скрипт
                    <p class=pre>var ROOT_PATH="/market";</p>


                <li>Скрипт запуcкается и работает независимо от остальных&nbsp;из папки /market/<br><br>
            </ol>
        <li>Таким образом, можно установить неограниченное кол-во интернет-магазинов на одном домене. Лицензионное соглашение <a href="../index.html">накладывает ограничение</a> на количество установленных магазинов на единую лицензию для технической поддержки.<br><br>
            Поддерживается возможность установки нескольких магазинов в единую базу, для этого служит опция <strong>префикс</strong> в названиях таблиц:
            <p>
            <ul>
                <li>phpshop_&nbsp;&nbsp; - 1 магазин
                <li>phpshop2_ - 2
                <li>phpshop3_ - 3 и т.д.
            </ul></p>
            Тип префикса задается в файле config.ini
            <p class=pre>[base]<br>
                table_name="<strong>phpshop_</strong>categories"; <br>
                table_name1="phpshop_orders";   <br>
                table_name2="phpshop_products";   <br>
                table_name3="phpshop_system";    <br>
                table_name5="phpshop_opros";        <br>
                table_name6="phpshop_opros_categories";<br>
                table_name7="phpshop_gbook";        <br>
                table_name8="phpshop_news";      <br>
                table_name9="phpshop_1c_docs";       <br>
                table_name10="phpshop_jurnal";     <br>
                table_name11="phpshop_page";       <br>
                table_name14="phpshop_menu";      <br>
                table_name15="phpshop_baners";     <br>
                table_name16="phpshop_cache";      <br>
                table_name17="phpshop_links";      <br>
                table_name18="phpshop_search_jurnal";<br>
                table_name19="phpshop_users";      <br>
                table_name20="phpshop_sort_categories";<br>
                table_name21="phpshop_sort";     <br>
                table_name22="phpshop_black_list";  <br>
                table_name23="phpshop_discount";    <br>
                table_name24="phpshop_valuta";      <br>
                table_name26="phpshop_search_base"; <br>
                table_name27="phpshop_shopusers";   <br>
                table_name28="phpshop_shopusers_status";<br>
                table_name29="phpshop_page_categories";<br>
                table_name30="phpshop_delivery";  <br>
                table_name31="phpshop_servers";   <br>
                table_name32="phpshop_order_status";   <br>
                table_name33="phpshop_payment";   <br>
                table_name34="phpshop_notice"; <br>
                table_name35="phpshop_foto";<br>
                table_name36="phpshop_comment";<br>
                table_name37="phpshop_messages";<br>
                table_name38="phpshop_rssgraber";<br>
                table_name39="phpshop_rssgraber_jurnal";<br>
                table_name50="phpshop_rating_categories";<br>
                table_name51="phpshop_rating_charact";<br>
                table_name52="phpshop_rating_votes";<br>
            </p>
    </ol>
</p>

<h2>Обновление</h2>
<p>
    Обновление выполняется по инструкции:
    <br><br>
<ol >
    <li>Создайте копию текущей базы данных через утилиту "Резервные копи базы": База -> Резервные копи базы (Backup)
    <li>Создаем папку /old/ загружаем туда все файлы из корневой директории www
    <li>Загружаем в очищенную директорию www новые файлы из архива новой версии в <strong>бинарном режиме</strong>
    <li>Из старого файла config.ini берем параметры подключения к базе данных (первые 5 строк) и вставляем в новый конфиг (/phpshop/inc/config.ini)
    <li>Запускаем <a href="javascript:miniWin('update/install.php',600,570)">апдейтер баз данных</a> (ваш_сайт/install/update/install.php), выбираем текущую версию, если ее там нет, то обновлять базу не нужно. Стираем папку /install/
    <li>Из папки /old/ копируем папку /UserFiles и /license со старыми картинками и лицензией в обновленный скрипт в тоже место
    <li>По необходимости копируем старый шаблон /phpshop/templates/, но с учетом что в нем могли быть внесены изменения для новой версии (сравнить с оригиналом)
</ol>
</p>

<h2>Перенос данных с сервера</h2>
<p>
    Перенос возможен как с веб-сервера на веб-сервер, так и с локального сервера (<a href="http://www.phpshop.ru/help/Content/install/phpshop_server.html" target="_blank">PHPShop Software</a> или <a href="http://www.phpshop.ru/help/Content/install/denwer_zend_optimizer.html" target="_blank">Denwer</a>).<br>
    Перенос выполняется по инструкции:
    <br><br>
<ol >
    <li>Создайте копию текущей базы данных на старом сервере через утилиту панели управления "Резервные копи базы": База -> Резервные копи базы (Backup)
    <li>Загружаем файлы переносимого скрипта из папки веб-файлов (www, htdocs, public_html) в одноименную папку на новом сервере в <strong>бинарном режиме</strong>.<br><br>
        Для мгновенного переноса файлов с сервера на сервер можно воспользоваться утилитой <a href="http://phpshop.ru/loads/ThLHDegJUj/putty.exe" target="_blank">PyTTY</a> и  протоколом SSH. <br>
        Комманды оболочки после подключения на старом сервере (www заменяется на имя своей папки хранения веб-файлов):
        <p class=pre>
            tar cvf file.tar www/<br>
            gzip file.tar<br>
            cp file.tar.gz www/
        </p>
        Комманды оболочки после подключения на новом сервере:
        <p class=pre>
            wget http://имя_домена/file.tar.gz<br>
            tar -zxf file.tar.gz<br>
            cp -rf file/ www/
        </p>

    <li>Восстанавливаем из архива скрипта папку install и копируем ее вместе с входящими в нее файлами на новый сервер.
    <li>Прописываем в файл конфигурации  /phpshop/inc/config.ini на новом сервере новые параметры доступа к базе данных MySQL.
        <p class=pre>
            [connect]<br>
            host="localhost";             # имя хоста<br>
            user_db="user";         # имя пользователя<br>
            pass_db="mypas";            # пароль базы<br>
            dbase="mybase";           # имя базы</p>
    <li>Запускаем инсталлятор http://имя_сайта/install/install.php. Производим установку баз с нуля, указываем пароли доступа к панели управления (временные, после завершения пароли будут идентичны старому серверу). Будет установлена тестовая база временно.
    <li>Удалаем папку /install
    <li>Авторизуемся в панели управления /phpshop/admpanel/, используя новые временные пароли доступа, введенные в предыдум шаге.
    <li>Восстанавливаем резервную копию базы через утилиту "Резервные копи базы": База -> Резервные копи базы (Backup). Перегружаем браузер.
    <li>Теперь для входа в панель управления следует вводить пароли со старого сервера.
</ol>
</p>

<h2>Коды ошибок</h2>
<ol>
    <li><b>101 Ошибка подключения к базе</b><br><br>
        <ul>
            <li>Проверьте настройки подключения к базе данных: <b>host, user_db, pass_db, dbase</b>.
            <li>Откройте файл phpshop/inc/config.ini и отредактируйте вышеописанные переменные под вашу базу (заменить данные между кавычками).<br>
                <p class=pre>
                    [connect]<br>
                    host="localhost";             # имя хоста<br>
                    user_db="user";         # имя пользователя<br>
                    pass_db="mypas";            # пароль базы<br>
                    dbase="mybase";           # имя базы</p>
        </ul>
    <li><b>102 Не установлены базы</b><br><br>
        <ul><li>Запустите <strong>инсталятор</strong> (имя_сайта/install/install.php) для установки БД.
        </ul><br>
    <li><b>103 Ошибка расположения папки с файлами</b><br><br>
        <ul><li>Проверьте настройки в установочном файле <strong>dafault_page_dir</strong>.
        </ul><br>
    <li><b>104 Ошибка расположения папки с шаблонами дизайна (скины)</b><br><br>
        <ul>
            <li>Не включена опция Register Globals ON
            <li>Проверьте существования папки с выбранным шаблоном: <strong>phpshop/templates/имя_шаблона</strong>.
            <li>Через <strong>панель администрирования</strong> (<b>"Настройка" => "Система"</b>) выберете существующий шаблон.
            <li>Имя шаблона должно совпадать с именем папки (см. выше)
        </ul><br>
    <li><b>105 Ошибка существования файла install.php</b><br><br>
        <ul>
            <li>В целях безопасности удалите папку <b>/install</b>
            <li>Для отключения этой проверки измените значение переменной  в установочном файле config.ini (не рекомендуется)
                <p class=pre>
                    check_install="false";
                </p>
        </ul>
</ol>

<div class="footer">Copyright © PHPShop Software. Все права защищены © 2003-<? echo date("Y") ?>. СГРПЭ PHPShop №2006614274.
</div>
</body>
</html>
