<?

/*
 * **** 
 * Модуль "СМАРТ-ФИЛЬТР" для PHPShop Enterprise 3.6
 * Copyright © WEB for ALL, 20010-2014 
 * @author "WEB for ALL" (www.web4.su) 
 * @version 1.0
 * ****
 */
/*
 * Настройки модуля "СМАРТ-ФИЛЬТР"
 */
$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/", "w4asmartfilter");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.w4asmartfilter.w4asmartfilter_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $SysValue, $PHPShopModules;

    $action = $PHPShopOrm->update($_POST);
    foreach ($_POST['sort'] as $key => $val) {


        $sql = "UPDATE " . $PHPShopModules->getParam("base.w4asmartfilter.w4asmartfilter_categories") . " 
					SET 
					`id_sort` = '$val',
					`name` = 'резерв'
					WHERE `id` = $key";
        $res = mysql_query($sql);

        // собираем массив записаных характеристик для исключения повторений
        $sort_cat_arr[$val] = 'true';
    }

    if (is_array($_POST['code'])) {

        foreach ($_POST['code'] as $key => $val) {
            $sql = "UPDATE " . $SysValue['base']['table_name21'] . "
					SET
					code = '$val'
					WHERE id = $key
			";
            $res = mysql_query($sql);
        }
    }

    $res = w4a_smart_filter_update_check();
    echo $res;

    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopOrm, $PHPShopModules;


    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Настройка модуля";
    $PHPShopGUI->size = "600,500";


    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);
    $color = $data['color'];
    $price_enabled = $data['price_enabled'];

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'w4aSmartFilter'", "Настройки", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");

    $PHPShopOrm = new PHPShopOrm($SysValue['base']['table_name20']);
    $data = $PHPShopOrm->select(array('id'), array('name' => "='Smart Filter'", 'category' => '=0'));
    $n = 0;
    if (is_array($data)) {

        $data = $PHPShopOrm->select(array('name,id'), array('category' => "=" . $data['id'] . ""), false, array('limit' => 1000));
        $value[0] = array('Выберите...', 0, false);
        foreach ($data as $val) {

            $value[] = array($val['name'], $val['id'], false);
            $n++;
        }

        for ($i = 0; $i < $n; $i++) {
            $sql = "select * from " . $PHPShopModules->getParam("base.w4asmartfilter.w4asmartfilter_categories") . " where id = $i";
            $row = mysql_fetch_array(mysql_query($sql));

            $val = $value;
            if (!empty($row['id_sort'])) {
                foreach ($val as $key => $v) {

                    if ($v[1] == $row['id_sort']) {
                        $val[$key][2] = "selected";
                        if ($sort_cat_dbl_arr[$row['id_sort']])
                            $flag = 'dbl';
                        else
                            $flag = 'true';
                        // собираем массив характеристик для определения дублей
                        $sort_cat_dbl_arr[$row['id_sort']] = 'true';
                    }
                }
            }
            if ($flag == 'dbl') {
                $dis .=$PHPShopGUI->setSelect('sort[' . $i . ']', $val, 150, 'none; border:solid 1px #ff0000', ($i + 1) . ') ') . '<span style="color:red;">Дубль!!!</span>' . $PHPShopGUI->setLine('<br>');
            } elseif ($flag == 'true') {
                $dis .=$PHPShopGUI->setSelect('sort[' . $i . ']', $val, 150, 'none; border:solid 1px #0000ff', ($i + 1) . ') ') . $PHPShopGUI->setLine('<br>');
            } else {
                $dis .=$PHPShopGUI->setSelect('sort[' . $i . ']', $val, 150, 'none', ($i + 1) . ') ') . $PHPShopGUI->setLine('<br>');
            }
            if ($i == 4)
                $dis .='</td><td>';

            // определяем ID-цвета
            if ($i == $color)
                $id_color = $row['id_sort'];
            $val_color[] = array("Поле-" . ($i + 1), $i, false);

            unset($row);
            unset($val);
            $flag = '';
            if ($i == 9)
                break;
        }

        // определение поля для Цвета (в виде #ffffff)
        if ($color == 999) {
            $val_color[] = array('Выберите поле', 999, 'selected');
        } else {
            $val_color[] = array('Выберите поле', 999, false);
            $val_color[$color][2] = 'selected';
        }
        $dis_color = $PHPShopGUI->setSelect('color_new', $val_color, 95, 'none', 'Поле цвета: ');

        // учет/неучет Цены товара
        if ($price_enabled == '0') {
            $checker_0 = 'checked';
            $checker_1 = '';
        } else {
            $checker_0 = '';
            $checker_1 = 'checked';
        }

        $dis_price = $PHPShopGUI->setRadio('price_enabled_new', '0', "Не учитывать цену", $checker_0, $onchange = "return true");
        $dis_price.='<br>';
        $dis_price .= $PHPShopGUI->setRadio('price_enabled_new', '1', "Учитывать цену", $checker_1, $onchange = "return true");

        $disp = '<table width="100%"><tr><td width="50%">' . $dis . '</td></tr>
			
				<tr><td>' . $dis_color . '</td><td>' . $dis_price . '</td></tr>
			</table>';
    } else {

        $disp = '<span style="color:red;">В характеристиках ненайдена группа <span style="font-weight:bold;">"Smart Filter"</span></span>
				<br>
				<p>Для работы фильтра необходимо:<br> <ol> <li>создать группу характеристик: <span style="font-weight:bold;">"Smart Filter"</span></p>
				<p><li> Объединить в нее характеристики для SMART FILTER</ol>
				';
    }

    $Tab1 = $disp;


    $info = '<p>Для работы фильтра необходимо:<br> 
			<ol> 
				<li>создать группу характеристик: <span style="font-weight:bold;">"Smart Filter"</span>
				<li> Объединить в нее характеристики для SMART FILTER
				<li>Делаем настройки в закладке «Настройки».
				<li>Делаем настройки в закладке «Цвет-настройка» (только у PRO).
				<li>Вставить в шаблоны index.tpl & shop.tpl переменные:
				<ul>
					<li>@w4aSmartFilterModWin@ - генерирует всплывающую подсказку с количеством найденных товаров
					<li>@w4aSmartFilter@ - генерирует пользовательский интерфейс фильтра 
				</ul>
				<li>Установить радио-переключатель «Учитывать/Не учитывать цену»
				<li>Отметить галочкой  опцию «Заполнить Таблицу SMART-Filter»				
			</ol>
			<div>Подробную инструкцию по по использованию модуля «SMART-фильтр» можно скачать тут: <a href="http://www.web4.su/UserFiles/File/modules/insructions/w4aSmartFilter_instruction.pdf" title="скачать инструкцию" target="_blank">скачать</a></div>';

    $Tab2 = $PHPShopGUI->setInfo($info, 200, '96%');

    $Tab3 = $PHPShopGUI->setPay($serial, true);
    $Tab3.= $PHPShopGUI->setLine('<br>') . $PHPShopGUI->setHistory();

    $w4a_info = '			
		<div style="padding: 0 0 15px 0; font-size: 20px; color:#63b6e6; text-align:center;">
								Функционал разработан веб-студией:<br/>
			</div>
			<div style="text-align:center;">
			<a href="http://www.web4.su" target="_blank"  tabindex="1000" title="Перейти на сайт разработчика"><img style="border:0;width: 180px;" src="http://web4.su/UserFiles/logo_02.png"></a>
			</div>
			<div style="padding: 15px 0 0 0; font-size: 12px;text-align:center;">
								Официальный представитель PHPShop <br/>
			<span style="color:#63b6e6;"> дизайн, верстка, кодинг, интеграция и<br/>
								доработки любого уровня сложности  для PHPShop</span><br/>
								<a href="http://www.web4.su" target="_blank"  tabindex="1000">Перейти на сайт разработчика</a>
			</div>
	';

    $w4aTab4 = $PHPShopGUI->setInfo($w4a_info, 200, '96%');

    // Вывод формы закладки		
    if ($color == 999) {

        $PHPShopGUI->setTab(array("Описание", $Tab2, 270), array("Настройки", $Tab1, 270), array("О разработчике", $w4aTab4, 270));
    } else {
        // закладка настройки цвета
        $PHPShopOrm = new PHPShopOrm($SysValue['base']['table_name21']);
        $data = $PHPShopOrm->select(array('*'), array('category' => "=$id_color"));
        foreach ($data as $row) {
            $diss .='
				<tr>
					<td>' . $row['name'] . '  </td>				
					<td> <input type="text" name="code[' . $row['id'] . ']" width="150" value="' . $row['code'] . '"> </td>	
				</tr>';
        }

        $disp = '
		<table>
			
			' . $diss . '
			
		</table>
		
		';
        $Tab4 = $disp;
        $PHPShopGUI->setTab(array("Описание", $Tab2, 270), array("Настройки", $Tab1, 270), array("Цвет-Настройка", $Tab4, 270), array("О разработчике", $w4aTab4, 270));
    }

    $_PRO = true;
    if ($_PRO === true) {

        if ($PHPShopModules->checkKeyBase('w4asmartfilter')) {

            $w4aTabPro = '<p>Внимание!!! Срок TRIAL-лицензии истек.</p>
						<p><a href="http://www.web4.su/gbook/?add_forma=true&promo=' . $_SERVER['SERVER_NAME'] . '&item=smart" target="_blank" class="w4a" title="Оф. представитель PHPShop © WEB for ALL" style="color:red;font-size:16px;">приобрести лицензию</a></p>';
            $w4aTabPro .= '<input type="hidden" name="w4a_smartfilter_fillin" value="1">';
        } elseif (!$PHPShopModules->checkKey($serial, 'w4asmartfilter')) {
            $time_r = trial_timer();
            $w4aTabPro = '<p>Внимание!!! Вы используете TRIAL-лицензию</p>
						<p>До окончания осталось: ' . $time_r['day'] . ' дней ' . $time_r['hour'] . ' часов</p>
						<p><a href="http://www.web4.su/gbook/?add_forma=true&promo=' . $_SERVER['SERVER_NAME'] . '&item=w4asmartfilter" target="_blank" class="w4a" title="Оф. представитель PHPShop © WEB for ALL" style="color:red;font-size:16px;">приобрести лицензию</a></p>';
            $w4aTabPro .= '<input type="checkbox" name="w4a_smartfilter_fillin"> Заполнить Таблицу SMART-Filter';
        }
        elseif ($PHPShopModules->checkKey($serial, 'w4asmartfilter')) {

            $w4aTabPro .= '<input type="checkbox" name="w4a_smartfilter_fillin"> Заполнить Таблицу SMART-Filter';
        }

        $PHPShopGUI->addTab(array("PRO", $w4aTabPro, 270));
    }
    $PHPShopGUI->addTab(array("О Модуле", $Tab3, 270));
    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "newsID", $id, "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionUpdate");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

include('../w4a/w4asmartfilter.pro.php');

if ($UserChek->statusPHPSHOP < 2) {

    // Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

    // Обработка событий 
    $PHPShopGUI->getAction();
}
else
    $UserChek->BadUserFormaWindow();

/*
 * Функция, получающая из метки времени в формате UNIX TIME
 * месяцы, дни, часы, минуты и секунды
 *
 * $t - значение UNIX timestamp, которое нужно перевести
 * в месяцы, дни, часы, минуты и секунды
 */

function parse_timestamp($t = 0) {
    $month = floor($t / 2592000);
    $day = ( $t / 86400 ) % 30;
    $hour = ( $t / 3600 ) % 24;
    $min = ( $t / 60 ) % 60;
    $sec = $t % 60;

    return array('month' => $month, 'day' => $day, 'hour' => $hour, 'min' => $min, 'sec' => $sec);
}

function trial_timer() {
    global $SysValue;
    $PHPShopOrm = new PHPShopOrm($SysValue['base']['modules_key']);
    $data = $PHPShopOrm->select(array('*'), array('path' => "='w4asmartfilter'"));

    $time_r = parse_timestamp($data['date']-time());

    return $time_r;
}
?>


