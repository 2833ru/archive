<?

$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("date");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");

PHPShopObj::loadClass("system");
$PHPShopSystem = new PHPShopSystem();

PHPShopObj::loadClass("orm");

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI($reload='none');
$PHPShopGUI->title="Настройки антивируса";

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.guard.guard_system"));


function setSelectValue($n) {
    $i=1;
    while($i<=10) {
        if($n==$i) $s="selected"; else $s="";
        $select[]=array($i,$i,$s);
        $i++;
    }
    return $select;
}


function actionStart() {
    global $PHPShopGUI,$_classPath,$PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();
    extract($data);

    if($flag==1) $fl="checked";
    else $fl2="checked";

    if($element==0) $s1="selected";
    else $s2="selected";

    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->size="630,530";

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройки антивируса","Укажите данные для записи в базу.","../install/shield_green.png");


    // Содержание закладки 1
    $Tab1=$PHPShopGUI->setField($PHPShopGUI->setCheckbox("enabled_new",1,"Автоматическая проверка файлов",$enabled),
            $PHPShopGUI->setSelect('chek_day_num_new',setSelectValue($chek_day_num),50,false,'Количество проверок в день: ').
            $PHPShopGUI->setCheckbox("mode_new",1,"Расширенный режим проверки файлов (рекомендуется)",$mode)
            );
    $Tab1.=$PHPShopGUI->setField('Уведомления',
            $PHPShopGUI->setCheckbox("stop_new",1,"Блокировка сайта при обнаружении вируса",$stop).$PHPShopGUI->setLine().
            $PHPShopGUI->setCheckbox("mail_enabled_new",1,"Уведомление администратора по E-mail",$mail_enabled)
            ,false,false,10);


    // Таблица
    $PHPShopInterface = new PHPShopInterface();
    $PHPShopInterface->size="600,500";
    $PHPShopInterface->window=true;
    $PHPShopInterface->idRows='p';
    $PHPShopInterface->imgPath=$PHPShopGUI->dir."img/";
    $PHPShopInterface->setCaption(array("Название","40%"),array("Дата","30%"),array('Действия','30%'));

    // Проверка дат событий
    if((date('U')-$last_chek)<(86400*3)) $flag_chek=$PHPShopGUI->setImage('icon/icon-activate.gif', 19, 15);
    else $flag_chek=$PHPShopGUI->setImage('icon/error.gif', 15, 15);

    if((date('U')-$last_update)<(86400*5)) $flag_update=$PHPShopGUI->setImage('icon/icon-activate.gif', 19, 15);
    else $flag_update=$PHPShopGUI->setImage('icon/error.gif', 15, 15);

    if((date('U')-$last_crc)<(86400*3)) $flag_crc=$PHPShopGUI->setImage('icon/icon-activate.gif', 19, 15);
    else $flag_crc=$PHPShopGUI->setImage('icon/error.gif', 15, 15);
    
    if(empty($last_update)) $last_update_mes='Не выполнено!';
            else $last_update_mes=PHPShopDate::dataV($last_update);
            
     if(empty($last_crc)) $last_crc_mes='Не выполнено!';
            else $last_crc_mes=PHPShopDate::dataV($last_crc);

    if(!empty($last_chek)){
    $PHPShopInterface->setRow(1,'Последняя проверка файлов',$flag_chek.PHPShopDate::dataV($last_chek),$PHPShopGUI->setButton('Проверить','icon/accept.png',130,25,
            false, "miniWin('http://".$_SERVER['SERVER_NAME'].$GLOBALS['SysValue']['dir']['dir']."/phpshop/modules/guard/admin.php?do=chek',500,500)"));
    }
    $PHPShopInterface->setRow(2,'Последнее обновление сигнатур',$flag_update.$last_update_mes,$PHPShopGUI->setButton('Обновить','icon/add.png',130,25,
            false, "miniWin('http://".$_SERVER['SERVER_NAME'].$GLOBALS['SysValue']['dir']['dir']."/phpshop/modules/guard/admin.php?do=update',500,500)"));
    $PHPShopInterface->setRow(2,'Файловая база',$flag_crc.$last_crc_mes,$PHPShopGUI->setButton('Пересчитать','icon/database_refresh.png',130,25,
            false, "miniWin('http://".$_SERVER['SERVER_NAME'].$GLOBALS['SysValue']['dir']['dir']."/phpshop/modules/guard/admin.php?do=create',500,500)"));

    $Tab1.=$PHPShopInterface->Compile();
    
     // Инстркция
     $Info=' <h3>Режимы</h3>
Флаг "Автоматическая проверка файлов" включает автоматическую проверку файлов по заданному промежутку, редактирующийся в опции "Количество проверок в день".
    Флаг "Расширенный режим проверки файлов" активирует возможность проверки всех файлов, включая шаблоны и библиотеки. Если эта опция не активная, то работает упрощенный режим проверки, который проверяет только самые "популярные" файлы у вирусов. Расширенный режим требует больше ресурсов и может замедлять работу сайта. 
<h3>Уведомления</h3>
    Флаг "Блокировка сайта при обнаружении вируса" будет выводит заглушку вместо сайта, чтобы остальные пользователи не заразились вирусом через ваш сайт и поисковики не понизили его в рейтинге.
    Флаг "Уведомление администратора по E-mail" отправляет отчеты о проверки, включенной флагом "Автоматическая проверка файлов". 
<h3>Действия</h3>
    Проверка файлов - проверяет файлы на изменения, в измененных файлах проверяются сигнатуры вирусов
    Обновление сигнатур - соединение и обновление сигнатур с сервера разработчика.
    Пересчет файловой базы - обход файлов и создание новой БД контрольных сумм файлов 
<h3>Лечение</h3>
При заражении вирусом можно восстановить рабочие бекапы файлов. Все бекапы хранятся в UserFiles/Files/ и имеют вид дата-случайное_число.zip Нужно или распаковать файлы на сервере (определяется сервером) или скачать самый свежий архив через ftp себе на компьютер, распаковать и загрузить содержимое в корневую папку сервера / 

<h3>Полезные советы</h3>

1. Если вы производите обновление ПО, то антивирус при включенном флаге "автоматическая проверка файлов" сработает на несоответствие контрольных сумм файлов и сообщит вам об этом. В этом случаи необходимо выполнить действие по обновлению сигнатур.<br><br>
2. Если вы получили уведомление о заражении вирусом, то в письме администратору (необходимо, чтобы флаг "Уведомление администратора по E-mail" был включен) будет предложен файл архивной копии (backup) с чистыми проверенными файлами. Архив нужно восстановить в ручном режиме и заменить файлы на фтп через любой фтп-менеджер. <br><br>
';
     $Tab2=$PHPShopGUI->setInfo($Info, 280);
    
    // Содержание закладки 2
    $Tab3=$PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,300),array("Инструкция",$Tab2,300),array("О Модуле",$Tab3,300));


    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Отмена","right",70,"return onCancel();","but").
            $PHPShopGUI->setInput("submit","editID","ОК","right",70,"","but","actionUpdate");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    if(empty($_POST['mode_new'])) $_POST['mode_new']=0;
    if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;
    if(empty($_POST['stop_new'])) $_POST['stop_new']=0;
    if(empty($_POST['mail_enabled_new'])) $_POST['mail_enabled_new']=0;

    $action = $PHPShopOrm->update($_POST,array('id'=>'='.$_POST['newsID']));
    return $action;
}

if(CheckedRules($UserStatus["option"],1) == 1){

    // Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'],'actionStart');

    // Обработка событий
    $PHPShopGUI->getAction();

}else $UserChek->BadUserFormaWindow();
?>