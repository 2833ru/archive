<?php
error_reporting(0);
$_classPath = "../phpshop/";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("mail");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini",false);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<title><?php echo $SysValue['license']['product_name'] ?> -> Установка</title>
<META http-equiv=Content-Type content="text/html; charset=windows-1251">
<META name="ROBOTS" content="NONE">
<link href="<?php echo $SysValue['dir']['dir'] ?>/phpshop/admpanel/skins/1c/texts.css" type=text/css rel=stylesheet>
<script>
    function Warning() {
        return alert("Внимание!\nНе забудьте поменять префиксы баз данных в установочном файле config.ini");
    }

    function InstallOk() {
        try {
            window.opener.location.replace("../");
        } catch (e) {
            window.location.replace("../");
        }
        window.close();
    }

    function Readonlys() {
        return alert("Внимание!\nДанные редактирируются только в файле /phpshop/inc/config.ini");
    }

    function ChekRegLic() {
        var s1 = window.document.forms.regForma.selectLic.checked;
        if (s1 === false) {
            if (confirm("Внимание!\nВы  согласны с  лицензионным соглашением?"))
                window.document.forms.regForma.selectLic.checked = true;
        }
        else
            document.regForma.submit();
    }


    function ChekApi() {
        var s1 = window.document.forms.regForma.errors.value;
        if (s1 === 1) {
            if (confirm("Внимание!\nТест системных требований не пройден, настоятельно рекомендуем добиться прохождения теста.\nВсе равно продолжить установку?"))
                window.document.forms.regForma.submit();
        }
        else
            document.regForma.submit();
    }

    function GenPassword(a) {
        document.getElementById("pas1").value = a;
        document.getElementById("pas2").value = a;
        alert("Сгенерирован пароль: " + a);
    }

    function TestPas() {
        var pas1 = document.getElementById("pas1").value;
        var pas2 = document.getElementById("pas2").value;
        var login = document.getElementById("login").value;
        var mail = document.getElementById("mail").value;
        var mes_zag = "Внимание, обнаружены ошибки при заполнении формы:\n";
        var mes = "";
        var pattern = /\w+@\w+/;
        if (pas1.length < 6 || pas2.length < 6)
            mes += "-> Пароль должен содержать не менее 6 символов\n";
        if (pas1 != pas2)
            mes += "-> Пароли должны совпадать\n";
        if (login.length < 4)
            mes += "-> Логин должен содержать не менее 4 символов\n";
        if (pattern.test(mail) === false)
            mes += "-> Не правильно указано поле 'E-mail'\n";
        if (mes != "")
            alert(mes_zag + mes);
        else
            document.install.submit();
    }

    // Копировать в буфер
    function copyToClipboard() {
        try {
            document.getElementById('adm_option').select();
            var CopiedTxt = document.selection.createRange();
            CopiedTxt.execCommand("Copy");
            alert("Данные скопированы в буфер обмена.");
        } catch (e) {
            alert("Функция копирования доступна только для браузера IE");
        }
    }

</script>
</head>
<body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0">

    <table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
        <tr bgcolor="#ffffff">
            <td style="padding:10">
                <b>Установка  <?php echo $SysValue['license']['product_name'] ?></b><br>
                &nbsp;&nbsp;&nbsp;Настройка параметров и лицензионное соглашение
            </td>
            <td align="right">
                <img src="<?php echo $SysValue['dir']['dir'] ?>/phpshop/admpanel/img/i_server_info_med[1].gif" border="0" hspace="10">
            </td>
        </tr>
    </table>
    <?

    // Выбор файла
    function GetFile() {
        $dir = "./";
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                $fstat = explode(".", $file);
                if ($fstat[1] == "sql")
                    $disp = $file;
            }
            closedir($dh);
        }
        return $disp;
    }

    // Устанавливаем базу
    if ($_POST['install'] == 3) {
        
        $PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");

        //Отправка почты
        if ($_POST['pas_send'] == 1 and !empty($_POST['pas_send'])) {

            $zag = "PHPShop: данные установки на сервер " . $_SERVER['SERVER_NAME'];
            $content = "
Доброго времени!
---------------------------------------------------------

" . $SysValue['license']['product_name'] . " упешно установлен на сервер " . $_SERVER['SERVER_NAME'] . "

Административная панель доступна по адресу:  http://" . $_SERVER['SERVER_NAME'] . "/phpshop/admpanel/
или нажатием Ctrl + F12

Логин: " . $_POST['user'] . "
Пароль: " . $_POST['password'] . "

---------------------------------------------------------
" . $SysValue['license']['product_name'];

            new PHPShopMail($_POST['mail'], 'no_reply@phpshop.ru', $zag, $content);
        }

        @$fp = fopen(GetFile(), "r");

        if ($fp) {
            stream_set_write_buffer($fp, 0);
            $fstat = fstat($fp);
            $CsvContent = fread($fp, $fstat['size']);
            $CsvContent = str_replace("phpshop_", $_POST['prefix'], $CsvContent);
            fclose($fp);
        }

        $IdsArray2 = explode(";\n", $CsvContent);
        if (count($IdsArray2) < 5) {
            $IdsArray2 = explode(";\r\n", $CsvContent);
        } // \r\n
        array_pop($IdsArray2);
        while (list($key, $val) = each($IdsArray2))
            $result = mysql_query($val);
        $result = mysql_query("INSERT INTO " . $_POST['prefix'] . "users VALUES (1, 0x613a32343a7b733a353a2267626f6f6b223b733a353a22312d312d31223b733a343a226e657773223b733a353a22312d312d31223b733a373a2276697369746f72223b733a373a22312d312d312d31223b733a353a227573657273223b733a373a22312d312d312d31223b733a393a2273686f707573657273223b733a353a22312d312d31223b733a383a226361745f70726f64223b733a31313a22312d312d312d312d312d31223b733a363a22737461747331223b733a353a22312d312d31223b733a353a227275706179223b733a353a22302d302d30223b733a31313a226e6577735f777269746572223b733a353a22312d312d31223b733a393a22706167655f73697465223b733a353a22312d312d31223b733a393a22706167655f6d656e75223b733a353a22312d312d31223b733a353a2262616e6572223b733a353a22312d312d31223b733a353a226c696e6b73223b733a353a22312d312d31223b733a333a22637376223b733a353a22312d312d31223b733a353a226f70726f73223b733a353a22312d312d31223b733a363a22726174696e67223b733a353a22312d312d31223b733a333a2273716c223b733a353a22302d312d31223b733a363a226f7074696f6e223b733a333a22302d31223b733a383a22646973636f756e74223b733a353a22312d312d31223b733a363a2276616c757461223b733a353a22312d312d31223b733a383a2264656c6976657279223b733a353a22312d312d31223b733a373a2273657276657273223b733a353a22312d312d31223b733a31303a227273736368616e656c73223b733a353a22312d312d31223b733a363a2275706c6f6164223b693a313b7d, '" . $_POST['user'] . "', '" . base64_encode($_POST['password']) . "', '" . $_POST['mail'] . "', '1', '', '', '1', '', '1');");

        if ($result) {
            $copy = "
Данные доступа к PHPShop
------------------------
Административная панель доступна по адресу: http://" . $_SERVER['SERVER_NAME'] . "/phpshop/admpanel/ или нажатием Ctrl + F12
Логин: " . $_POST['user'] . "
Пароль: " . $_POST['password'] . "
";
            $disp = "<h4>Базы установлены полностью. Сайт готов к запуску.</h4>
<FIELDSET id=fldLayout>
<DIV style=\"padding: 10px;>
Административная панель доступна по адресу:  <br>
<a href=\"http://" . $_SERVER['SERVER_NAME'] . "/phpshop/admpanel/\" target=\"_blank\">http://" . $_SERVER['SERVER_NAME'] . "/phpshop/admpanel/</a><br>
или нажатием  Ctrl + F12<br><br>
Логин: <strong>" . $_POST['user'] . "</strong><br>
Пароль: <strong>" . $_POST['password'] . "</strong><br><br>
</DIV>
</FIELDSET>
                    ";
            $d = "";
        } else {
            $disp = "<h4>Ошибка установки: " . mysql_error() . " </h4>";
            $d = "disabled";
        }
        ?>
        <TABLE cellSpacing=0 cellPadding=0 width="100%"><TBODY>
                <TR>
                    <TD vAlign=top>
                        <TABLE cellSpacing=1 cellPadding=5 width="100%" align=center border=0>
                            <FORM method=post>
                                <TBODY>
                                    <TR class=adm vAlign=top align=middle>
                                        <TD align=left>
                                            <FIELDSET >
                                                <div align="center" style="padding:10px;">
                                                    <?php echo @$disp ?>
                                                </div>
                                            </FIELDSET>
                                        </TD>
                                    </TR></FORM></TBODY></TABLE></TD></TR></TBODY></TABLE>
    <table cellpadding="0" cellspacing="0" width="100%" height="50" style="margin-top:170px">
        <tr>
            <td><hr></td>
        </tr>
        <tr>
            <td align="right" style="padding:10">
                <INPUT class=but type=button value="&laquo; Назад" onclick="history.back(1)">
                <INPUT class=but type=button id="close" value="Готово" onclick="InstallOk()" <?php echo $d ?>>
            </td>
        </tr>
    </form>
    </table>
    <?
} elseif ($_POST['install'] == 2) {
    ?>
    <div style="padding:5px">
        <FIELDSET>
            <LEGEND id=lgdLayout >MySQL</LEGEND>
            <br>
            <table cellSpacing=1 cellPadding=5  border=0 width="100%">
                <FORM method="post" name="install">
                    <tr>
                        <td align="right" width="100">Хост:</td>
                        <td align="left"><input type="text" style="width:300" onclick="Readonlys()" readonly value="<?php echo $SysValue['connect']['host'] ?>" name="host"></td>
                    </tr>
                    <tr>
                        <td align="right">Пользователь:</td>
                        <td align="left"><input type="text" style="width:300" onclick="Readonlys()" readonly name="user_db" value="<?php echo $SysValue['connect']['user_db'] ?>"></td>
                    </tr>
                    <tr>
                        <td align="right">Пароль базы:</td>
                        <td align="left"><input type="text" style="width:300" onclick="Readonlys()" readonly name="pass_db" value="<?php echo $SysValue['connect']['pass_db'] ?>"></td>
                    </tr>
                    <tr>
                        <td align="right">Имя базы:</td>
                        <td align="left"><input type="text" style="width:300" onclick="Readonlys()" readonly name="dbase" value="<?php echo $SysValue['connect']['dbase'] ?>"></td>
                    </tr>
                    <tr>
                        <td align="right">Префикс:</td>
                        <td align="left"><input type="text" style="width:300" name="prefix" value="phpshop_" onchange="Warning()"></td>
                    </tr>
            </table>
            <br>

        </FIELDSET>
        <FIELDSET>
            <LEGEND id=lgdLayout >Администрирование</LEGEND>
            <br>
            <table cellSpacing=1 cellPadding=5  border=0 width="100%">
                <tr>
                    <td align="right" width="100">Пользователь</td>
                    <td align="left"><input type="text" id="login" style="width:150" value="root" name="user"> ( не менее 4 символов )</td>
                </tr>
                <tr>
                    <td align="right">E-mail:</td>
                    <td align="left"><input type="text" style="width:150" name="mail" id="mail" value=""> <input type="checkbox" value="1" name="pas_send" id="pas_send" checked> отослать рег. данные на почту</td>
                </tr>
                <tr>
                    <td align="right">Пароль:</td>
                    <td align="left"><input type="password" id="pas1" style="width:150" name="password" value=""> ( не менее 6 символов )</td>
                </tr>
                <tr>
                    <td align="right">Пароль еще раз:</td>
                    <td align="left"><input type="password" id="pas2" style="width:150" value="">

                        <INPUT class=but type=button value="Сгенерировать" style="width:100" onclick="GenPassword('<?php echo "P" . substr(md5(date("U")), 0, 6) ?>')">
                    </td>
                </tr>
            </table>
            <br>
        </FIELDSET>

    </div>
    <table cellpadding="0" cellspacing="0" width="100%" height="50" style="margin-top:5px">
        <tr>
            <td><hr></td>
        </tr>
        <tr>
            <td align="right" style="padding:10px">
                <INPUT class=but type=button value="&laquo; Назад" onclick="history.back(1)">
                <INPUT class=but type=button value="Отмена" onclick="window.close()">
                <INPUT class=but type="button" value="Далее &raquo;" onclick="TestPas()">
                <input type="hidden" name="install" value="3">
            </td>
        </tr>
    </table>
    </form>

    <?
} elseif ($_POST['install'] == 1) {

    while (list($val) = each($SysValue['base']))
        @$bases.=$SysValue['base'][$val] . ", ";

    $bases = substr($bases, 0, strlen($bases) - 2);
    $bases2 = preg_replace("phpshop_system", "", $bases);
    ?>
    <TABLE cellSpacing=1 cellPadding=5 width="100%" align=center border=0>
        <FORM method="post" name="regForma">
            <TR class=adm vAlign=top align=middle>
                <TD align=left width="100%">
                    <FIELDSET><LEGEND id=lgdLayout><u>Л</u>ицензионное соглашение</LEGEND>
                        <DIV style="PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 10px; PADDING-TOP: 10px">


                            <textarea style="width:99%;height:300">
ЛИЦЕНЗИОННОЕ СОГЛАШЕНИЕ НА ИСПОЛЬЗОВАНИЕ ПРОГРАММНОГО ПРОДУКТА "PHPShop"

Настоящее Лицензионное Соглашение заключается между пользователем программного продукта "PHPShop" (далее Пользователь) и ООО "ПХПШОП" (далее Автор). Перед использованием продукта внимательно ознакомьтесь с условиями данного соглашения. Если вы не согласны с условиями данного соглашения, вы не можете использовать данный продукт. Установка и использование продукта (в том числе просмотр исходного кода) означает ваше полное согласие со всеми пунктами настоящего соглашения. Соглашение относится ко всем коммерчески распространяемым версиям и модификациям программного продукта PHPShop.

Основные термины настоящего соглашения: ЭКЗЕМПЛЯР ПРОГРАММЫ - копия продукта "PHPShop", включающая в себя код программы Интернет-магазина, воспроизведенный в файлах, включая электронную или распечатанную документацию.

Лицензионное соглашение вступает в силу с момента приобретения или установки продукта и действует на протяжении всего срока использования продукта.

1. Предмет лицензионного соглашения
1.1. Предметом настоящего лицензионного соглашения является право использования одного экземпляра программного продукта (в дальнейшем "ЭКЗЕМПЛЯР ПРОГРАММЫ", "программа" или "продукт") "PHPShop", предоставляемое Пользователю ООО "ПХПШОП", в порядке и на условиях, установленных настоящим соглашением.
1.2. Все положения настоящего соглашения распространяются как на весь продукт в целом, так и на его отдельные компоненты.
1.3. Данное Соглашение дает право Пользователю на использование одной копии Продукта на одном web-сервере в пределах одного домена.
1.4. Лицензионное соглашение не предоставляет право собственности на продукт "PHPShop" и его компоненты, а только право использования ЭКЗЕМПЛЯРА ПРОГРАММЫ и его компонентов в соответствии с условиями, которые обозначены в пункте 3 настоящего соглашения.

2. Авторские права
2.1. Все авторские права на Продукт, включая документацию и исходный текст, принадлежат Автору, на основании свидетельств о государственной регистрации программы для ЭВМ "PHPShop" №2006614274 и №2010611237.
2.2. Продукт в целом или по отдельности является объектом авторского права и защищен Законом РФ "О правовой охране программ для электронных вычислительных машин и баз данных" от 23 сентября 1992 года, Законом РФ "Об авторском праве и смежных правах" от 9 июля 1993 года, а также международными Договорами.
2.3. В случае нарушения авторских прав предусматривается ответственность в соответствии с действующим законодательством РФ.

3. Условия использования продукта и ограничения
3.1. Пользователь имеет право бесплатно воспользоваться демо-версией Продукта, скачав с сайта Лицензиара www.phpshop.ru и установив на сервер в течении 30 дней, и без ограничений времени при установке на локальном компьютере. Демо-версии всех версий Продукта PHPShop работают без каких-либо ограничений функциональности, кроме количества выгрузки товаров в обработчике 1С версии PHPShop Enterprise Pro.
3.2. Для каждой новой установки Продукта на другой адрес web-сервера должна быть приобретена отдельная Лицензия. Перевод лицензии на новый домен возможен только при активной технической поддержке.
3.3. Автор оставляет за собой право требовать размещения обратной ссылки, с указанием Авторского права на сайте, где используется Продукт. Использование Продукта с нарушением условий данного Соглашения, является нарушением законов об авторском праве, и будет преследоваться в соответствии с действующим законодательством. Отказ от размещения обратной ссылки с указанием Авторского права является нарушением Соглашения и ограничивает Продукт в предоставлении технической поддержки Автором на все сайты Пользователя.
3.4. Вид ссылки и размещение строго задается Автором, код ссылки не поддается изменению. В целях сохранения визуализации с персональным дизайном возможно изменение цвета ссылки (задается лицензией).
3.5. Для официальных партнеров, после письменного согласования с Автором, ссылка размещается в удобном для них месте, на каждой странице сайта. Лицензия без копирайтов Автора увеличивает стоимость Продукта.
3.6. Версии Enterprise и EnterprisePro поддерживают размещение в некорневые директории, т.е. вида seamply.ru/market1/. Размещение типа market1.seamply.ru и т.д. требует покупки отдельной Лицензии. Техническая поддержка распространяется только на одну копию Продукта. Для каждого нового экземпляра магазина, а также для магазинов некорневых директорий вида seamply.ru/market1/, требуется покупка новой технической поддержки по тарифам, указанным на сайте Лицензиара. Версии Start и Catalog поддерживают размещение только в корневой папке или поддомене. Допускается возможность создания и использования Пользователем дополнительной копии Продукта исключительно в целях тестирования или внесения изменений в исходный код, при условии, что такая копия не будет доступна третьим лицам.
3.7. После покупки товара покупателю предоставляются исходные коды php-приложения за исключением файла index.php, в котором происходит проверка лицензии и защита от несанкционированного распространения программы. Все дополнительные приложения из комплекта EasyControl предоставляются в скомпилированном виде без возможности внесения изменения в код.
3.8. Пользователь может изменять, добавлять или удалять открытые файлы приобретенного ЭКЗЕМПЛЯРА ПРОГРАММЫ "PHPShop" в соответствии с Законодательством РФ об авторском праве. Изменение скомпилированных файлов запрещено и влечет нарушение данного Соглашения в соответствии с 273 статьей УК РФ.

4. Ответственность сторон
4.1. Пользователь не может копировать, передавать третьим лицам или распространять, сдавать в аренду/прокатПродукт и его компоненты, в том числе, созданные на базе Продукта сайты, в любой форме, в том числе, в виде исходного текста, каким-либо другим способом.
4.2. Любое распространение Продукта без предварительного согласия Автора, включая некоммерческое, является нарушением данного Соглашения, и влечет ответственность согласно действующему законодательству.
4.3. Продукт поставляется на условиях "КАК ЕСТЬ" ("AS IS") без предоставления гарантий производительности, покупательной способности, сохранности данных, а также иных явно выраженных или предполагаемых гарантий.
4.4. Автор не несет какой-либо ответственности за причинение или возможность причинения вреда Вам, Вашей информации или Вашему бизнесу вследствие использования или невозможности использования Продукта.
4.5. Автор не несет ответственность, связанную с привлечением Вас к административной или уголовной ответственности за использование Продукта в противозаконных целях (включая, но не ограничиваясь, продажей через Интернет магазин объектов, изъятых из оборота или добытых преступным путем, предназначенных для разжигания межрасовой или межнациональной вражды; и т.д.).
4.6. Автор не несет ответственности за работоспособность Продукта, в случае внесения Вами каких бы то ни было изменений в код программы.
4.7. Запрещается любое использование Продукта, противоречащее действующему законодательству РФ.
4.8. За нарушение условий настоящего соглашения наступает ответственность, предусмотренная законодательством РФ.

5. Условия технической поддержки
5.1. Приобретая Интернет-магазина PHPShop Enterprise, Пользователь получает бесплатную базовую техническую поддержку в течении 6 месяцев. Для версий PHPShop Start срок поддержки составляет 3 месяца.
5.2. Техническая поддержка предусматривает доступ к обновлениям, технические консультации, устранение ошибок в программном продукте "PHPShop", выявленных в течение гарантийного периода.
5.3. В поддержку включены консультации по поводу управления и заполнения магазина. Настройки связи с 1С (подключение к серверу и синхронизация данных). Вопросы по поводу установки продукта на сервер, решения проблем, мешающие установки продукта на сервер. Установка и настройка дополнительных бесплатных утилит из пакета EasyControl.
5.4. Консультации проводятся в специальном разделе сайта службы технической поддержки help.phpshop.ru ООО "ПХПШОП" в течение гарантийного срока по рабочим дням (за исключением выходных и нерабочих праздничных дней Российской Федерации) с 10 до 18 часов московского времени.
5.5. По истечению срока бесплатной технической поддержки, Пользователь может приобрести продление. Срок действия технической поддержки продлевается на один год с момента оплаты продления. Пользователь также получает возможность загрузить и установить все изменения и обновления, которые были выпущены к программному продукту до момента оплаты продления технической поддержки. Действующий прайс-лист для приобретения технической поддержки указан на интернет-сайте http://www.phpshop.ru/order/.
5.6. Проблемы, не относящиеся к базовой технической поддержке, не могут быть бесплатно решены. Для выявления и решения таких проблем следует оплатить дополнительные технические работы. Полный список платных технических работ доступен по ссылке: https://help.phpshop.ru/knowledgebase.php?article=116
5.7. Персональные доработки и модули, выполненные под заказ, поддерживаются бесплатно в течении 1 месяца.

6.Политика возврата денежных средств
6.1. В связи с тем, что перед приобретением, Автором предоставлено право проверить соответствие Продукта потребностям Пользователя, а именно, установить демо-версию Продукта, согласно п. 3.1. настоящего Соглашения, а также, по причине того, что Автор при продаже передает нематериальный продукт, не подлежащий возврату физически, возврат денежных средств Пользователю возможен, если Продукт явно не соответствует функциям, которые описаны в Руководстве Пользователя по адресу: http://wiki.phpshop.ru
6.2. Любые другие потребительские свойства, предполагаемые, но не обнаруженные пользователем при покупке, кроме описанных в http://wiki.phpshop.ru и на сайте Автора, не могут являться причиной для возврата денежных средств.
6.3. Возврат денежных средств осуществляется без комиссий банков и других платежей, по письменному заявлению Пользователя, не позднее 30 (тридцати) календарных дней с момента приобретения Продукта. В заявлении необходимо указать, что Пользователь гарантирует неиспользование Продукта, а также удаление всех без исключения полученных от Автора файлов, имеющих отношение к Продукту.
6.4. По истечении 30 (тридцати) дней с момента приобретения Продукта, претензии Автором не принимаются и денежные средства не возвращаются.
6.5. Возврат осуществляется в течение 15 (пятнадцати) календарных дней с момента получения письменного заявления в случае принятия Автором решения о возврате денежных средств Пользователю.
6.6. Бесплатные утилиты EasyControl, кроме платной синхронизации с 1С, предоставляются на условиях работы КАК ЕСТЬ" ("AS IS"), и не могут быть причиной возврата денежных средств за Продукт.

7. Изменение и расторжение соглашения
7.1. В случае невыполнения пользователем одного из вышеуказанных положений, ООО "ПХПШОП" имеет право в одностороннем порядке расторгнуть настоящее соглашение, уведомив об этом пользователя.
7.2. При расторжении соглашения Пользователь обязан прекратить использование продукта и удалить Лицензия полностью.
7.3. Пользователь вправе расторгнуть данное соглашение в любое время, полностью удалив ЭКЗЕМПЛЯР ПРОГРАММЫ "PHPShop", при этом, расторжение Соглашения не обязывает Автора возвращать средства, потраченные Пользователем на приобретение Продукта.
7.4. В случае если компетентный суд признает какие-либо положения настоящего соглашения недействительными, Соглашение продолжает действовать в остальной части.
7.5. Настоящее лицензионное соглашение также распространяется на все обновления, предоставляемые пользователю в рамках технической поддержки, если только при обновлении программного продукта пользователю не предлагается ознакомиться и принять новое лицензионное соглашение или дополнения к действующему соглашению. 

Контактная информация компании ООО «ПХПШОП»
Адрес сайта: http://www.phpshop.ru
E-mail отдела продаж: sales@phpshop.ru
Телефон отдела продаж: +7 (495) 989-11-15
Раздел техподдержки на сайте: https://help.phpshop.ru

                            </textarea>


                    </FIELDSET> </TD>
            </TR>
    </TABLE><br>
    &nbsp;&nbsp;<input type="checkbox" name="selectLic">Я принимаю условия лицензионного соглашения
    </DIV>
    <br>
    <hr>
    <table cellpadding="0" cellspacing="0" width="100%" height="50" >
        <tr>
            <td align="right" style="padding:10"><br>
                <INPUT class=but type=button value="&laquo; Назад" onclick="history.back(1)">
                <INPUT class=but type=button value="Отмена" onclick="window.close()">
                <INPUT class=but type="button" value="Далее &raquo;" onclick="ChekRegLic()">
                <input type="hidden" name="install" value="2">
            </td>
        </tr>
    </table>
    </form>
    <?
} else {

    $AllError = 0;


// Апач
    if (stristr($_SERVER['SERVER_SOFTWARE'], 'Apache'))
        $API = "............<img src=\"rewritemodtest/icon-activate.gif\" border=0 align=absmiddle> <b class='ok'>Ok</b>";
    else {
        $API = "............<img src=\"rewritemodtest/errormessage.gif\"  border=0 align=absmiddle> <b class='error'>Error</b>";
        $AllError = 1;
    }

// Версия PHP
    $phpversion = substr(phpversion(), 0, 1);
    if ($phpversion >= 5)
        $php = "............<img src=\"rewritemodtest/icon-activate.gif\" border=0 align=absmiddle> <b class='ok'>Ok</b>";
    else {
        $php = "............<img src=\"rewritemodtest/errormessage.gif\"  border=0 align=absmiddle> <b class='error'>Error</b>";
        $AllError = 1;
    }



    if (@mysql_get_server_info()) {
        $mysqlversion = substr(@mysql_get_server_info(), 0, 1);
        if ($mysqlversion >= 4)
            $mysql = "............<img src=\"rewritemodtest/icon-activate.gif\" border=0 align=absmiddle> <b class='ok'>Ok</b>";
        else {
            $mysql = "............<img src=\"rewritemodtest/errormessage.gif\"  border=0 align=absmiddle> <b class='error'>Error</b>";
            $AllError = 1;
        }
    }
    else
        $mysql = "...............?";

// Rewrite
    $path_parts = pathinfo($_SERVER['PHP_SELF']);
    $filename = "http://" . $_SERVER['SERVER_NAME'] . $path_parts['dirname'] . "/rewritemodtest/test.html";
    if (@fopen($filename, "r"))
        $rewrite = "............<img src=\"rewritemodtest/icon-activate.gif\" border=0 align=absmiddle> <b class='ok'>Ok</b>";
    else {
        $rewrite = "............<img src=\"rewritemodtest/errormessage.gif\"  border=0 align=absmiddle> <b class='error'>Error</b>";
        $AllError = 1;
    }

    $GD = gd_info();

//GD Support
    if ($GD['GD Version'] != "")
        $gd_support = "............<img src=\"rewritemodtest/icon-activate.gif\" border=0 align=absmiddle> <b class='ok'>Ok</b>";
    else {
        $gd_support = "............<img src=\"rewritemodtest/errormessage.gif\"  border=0 align=absmiddle> <b class='error'>Error</b>";
        $AllError = 1;
    }

//FreeType Support
    if ($GD['FreeType Support'] === true)
        $gd_freetype_support = "............<img src=\"rewritemodtest/icon-activate.gif\" border=0 align=absmiddle> <b class='ok'>Ok</b>";
    else {
        $gd_freetype_support = "............<img src=\"rewritemodtest/errormessage.gif\"  border=0 align=absmiddle> <b class='error'>Error</b>";
        $AllError = 1;
    }

//FreeType Linkage
    if ($GD['FreeType Linkage'] == "with freetype")
        $gd_freetype_linkage = "............<img src=\"rewritemodtest/icon-activate.gif\" border=0 align=absmiddle> <b class='ok'>Ok</b>";
    else {
        $gd_freetype_linkage = "............<img src=\"rewritemodtest/errormessage.gif\"  border=0 align=absmiddle> <b class='error'>Error</b>";
        $AllError = 1;
    }

// XML Support
    if (function_exists("xml_parser_create"))
        $xml_support = "............<img src=\"rewritemodtest/icon-activate.gif\" border=0 align=absmiddle> <b class='ok'>Ok</b>";
    else {
        $xml_support = "............<img src=\"rewritemodtest/errormessage.gif\"  border=0 align=absmiddle> <b class='error'>Error</b>";
        $AllError = 1;
    }
    ?>
    <TABLE cellSpacing=1 cellPadding=5 width="100%" height="400" align=center border=0>
        <FORM method="post" name="regForma">
            <TR class=adm vAlign=top align=middle>
                <TD align=left>
                    <FIELDSET style="height:300px"><LEGEND>Тест системных требований</LEGEND>
                        <DIV style="padding: 10px;">
                            <ol>
                                <li id="line1" style="visibility:hidden"> Apache <?php echo $API ?></li>
                                <li id="line2" style="visibility:hidden"> MySQL <?php echo $mysql ?>
                                <li id="line3" style="visibility:hidden"> PHP  <?php echo $php ?></li>
                                <li id="line4" style="visibility:hidden">GD Support для PHP <?php echo $gd_support ?></li>
                                <li id="line5" style="visibility:hidden">FreeType Support для PHP <?php echo $gd_freetype_support ?></li>
                                <li id="line6" style="visibility:hidden">FreeType Linkage для PHP <?php echo $gd_freetype_linkage ?></li>
                                <li id="line7" style="visibility:hidden">XML Parser для PHP <?php echo $xml_support ?></li>
                                <br><br>
                                <ol>
                                    </DIV>
                                    <div style="padding: 20px"><strong>Расшифровка</strong>: <img src="rewritemodtest/icon-activate.gif" border=0 align=absmiddle> <b class='ok'>Ok</b> - тест пройден,
                                        <img src="rewritemodtest/errormessage.gif"  border=0 align=absmiddle> <b class='error'>Error</b> - тест не пройден (возможны проблемы при работе скрипта, обратитесь к документации сервера или свяжитесь с администратором сервера)
                                    </div>
                                    </FIELDSET> </TD>
                                    </TR>
                                    </TABLE>


                                    <br>
                                    <hr>
                                    <table cellpadding="0" cellspacing="0" width="100%" height="50" >
                                        <tr>
                                            <td align="right" style="padding:10">
                                                <INPUT class=but type=button value="Отмена" onclick="window.close()">
                                                <input type="hidden" name="errors" id="error" value="<?php echo $AllError ?>">
                                                <INPUT class=but type="button" value="Далее &raquo;" onclick="ChekApi()">
                                                <input type="hidden" name="install" value="1">
                                            </td>
                                        </tr>

                                    </table>
                                    </form>
                                    <script>
    function LoadTest(i) {
        document.getElementById("line" + i).style.visibility = 'visible';
        if (i != 7)
            setTimeout("LoadTest(" + (i + 1) + ")", 300);
    }
    setTimeout("LoadTest(1)", 300);
                                    </script>
<? } ?>


                                </body>
                                </html>