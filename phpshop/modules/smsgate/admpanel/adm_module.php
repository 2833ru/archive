<?

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.smsgate.smsgate_message"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopOrm;

    $PHPShopGUI->includeJava.='
    <style>
      .smsgate .btn {
        margin: 3px;
        color: #fff;
      }
      
      .smsgate textarea {
        margin-top: 10px;
      }
      
    </style>
    <script type="text/javascript">

        $.fn.extend({
            insertAtCaret: function(myValue){
                return this.each(function(i) {
                    if (document.selection) {
                        // Для браузеров типа Internet Explorer
                        this.focus();
                        var sel = document.selection.createRange();
                        sel.text = myValue;
                        this.focus();
                    }
                    else if (this.selectionStart || this.selectionStart == "0") {
                        // Для браузеров типа Firefox и других Webkit-ов
                        var startPos = this.selectionStart;
                        var endPos = this.selectionEnd;
                        var scrollTop = this.scrollTop;
                        this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
                        this.focus();
                        this.selectionStart = startPos + myValue.length;
                        this.selectionEnd = startPos + myValue.length;
                        this.scrollTop = scrollTop;
                    } else {
                        this.value += myValue;
                        this.focus();
                    }
                })
            }
        });
        
        
        function ShablonAdd(area,snippet){
          $("#"+area).insertAtCaret(snippet);
        }    

    </script>';

    // Выборка
    $data = $PHPShopOrm->select();
    extract($data);

    // Содержание закладки 1
    $Tab1 =$PHPShopGUI->setText("Сервер API (по умолчанию phpshop4.incore1.ru): ")
        .$PHPShopGUI->setInput('text','domen_api_new',$domen_api,false,'97%','');

    $Tab1.=$PHPShopGUI->setText("Логин API:")
        .$PHPShopGUI->setInput('text','login_api_new',$login_api,false,'97%','');

    $Tab1.=$PHPShopGUI->setText("Пароль API:")
        .$PHPShopGUI->setInput('password','password_api_new',$password_api,false,'97%','');

    $Tab1.=$PHPShopGUI->setText("Телефон администратора магазина для получение уведомлений (можно указать несколько телефонов, перечислив их через запятую):")
        .$PHPShopGUI->setInput('text','admin_phone_new',$admin_phone,false,'97%','','','','(в формате: 71234567890)');

    $Tab1.=$PHPShopGUI->setText("Имя отправителя (по умолчанию INCORE). Для использования в смс уникального имени отправителя необходимо согласование с вашим менеджером.:")
        .$PHPShopGUI->setInput('text','sender_new',$sender,false,'97%','','','','');

    $Tab1.=$PHPShopGUI->setLink("https://".$domen_api."/ru/reg.html", 'Перейти на сервис и зарегистрироваться', '_blank', 'margin:5px;display:inline-block;');

    $Tab1.=$PHPShopGUI->setLink("https://".$domen_api."/ru/cabinet.html", 'Перейти на сервис и  авторизоваться', '_blank', 'margin:5px;display:inline-block;');

    // Содержание закладки 2
    $Tab2 = $PHPShopGUI->setText("<h3>Шаблон 'Новый заказ' для заказчика: </h3>",'',false)
            .$PHPShopGUI->setInput("button","","Название интернет-магазина","","","ShablonAdd('order_template_sms_new','Интернет-магазин - @NameShop@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Идентификатор заказа","","","ShablonAdd('order_template_sms_new','Ваш номер заказа - @OrderNum@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Статус заказа","","","ShablonAdd('order_template_sms_new','Статус Вашего заказа - @OrderStatus@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Состав заказа","","","ShablonAdd('order_template_sms_new','СОСТАВ ЗАКАЗА:".'\r\n'."@Order@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setTextarea('order_template_sms_new',$order_template_sms,false,'97%',150);

    $Tab2.= $PHPShopGUI->setText("<h3>Шаблон 'Изменение статуса заказа': </h3>",'',false)
        .$PHPShopGUI->setInput("button","","Название интернет-магазина","","","ShablonAdd('change_status_order_template_sms_new','Интернет-магазин - @NameShop@')","btn-sm btn-primary","","","")
        .$PHPShopGUI->setInput("button","","Идентификатор заказа","","","ShablonAdd('change_status_order_template_sms_new','Ваш номер заказа - @OrderNum@')","btn-sm btn-primary","","","")
        .$PHPShopGUI->setInput("button","","Статус заказа","","","ShablonAdd('change_status_order_template_sms_new','Статус Вашего заказа изменен на - @OrderStatus@')","btn-sm btn-primary","",
            "","")
        .$PHPShopGUI->setTextarea('change_status_order_template_sms_new',$change_status_order_template_sms,false,'97%',150);

    // Содержание закладки 3
    $Info = '<div>
      <h2>Сервис</h2>
      <p>Для начала работы с установленным модулем "InCore Dev: SMS-уведомления" необходимо пройти <a href="https://'.$domen_api.'/ru/reg.html" target="_blank">регистрацию</a>
      на сервисе InCore Dev и дождаться активации аккаунта, которая происходит с понедельника по пятницу с 9.00 до 18.00 по
      московскому времени.</p>
      <p>Стоимость смс с именем отправителя тарифицируется - 1,5 руб. Стоимость смс без имени отправителя - 1 руб.</p>
      <p>Для использования в смс уникального имени отправителя необходимо согласование с вашим менеджером сервиса
      InCore Dev.</p>
      <p>Для отправки SMS-уведомлений используется сервис
      <a href="#" onclick="window.open(\'http://incoredevelopment.com\')">InCore Dev.</a></p>
      <h2>Шаблон "Новый заказ" для администратора</h2>
      <p>Настраивается для администратора сайта. Шаблон сообщения будет приходить после оформления нового заказа.</p>
      <h2>Шаблон "Новый заказ" для заказчика</h2>
      <p>Настраивается для уведомления заказчика о совершенном заказе.</p>
      <h2>Шаблон "Изменение статуса заказа"</h2>
      <p>Настраивается для уведомления заказчика об изменении статуса заказа.</p>
      <h2>Кнопки в шаблонах перед текстовым полем -> <input type="button" value="кнопка" class="btn btn-sm btn-primary"></h2>
      <p>Данные кнопки вставляют в текстовое поле необходимую переменную для формирования итогового сообщения перед отправкой уведомления. Чтобы вставить переменную в текстовое поле, просто поставьте курсор в нужное место поля и кликните по кнопке с нужным названием.</p>
      </div>
    ';
    
    $Tab3 = $PHPShopGUI->setInfo($Info, '', '95%');

    // Содержание закладки 4
    $Tab4 = $PHPShopGUI->setPay('',false);
    
    // Содержание закладки 5
    $Tab5.= $PHPShopGUI->setInfo('<div><p>Студия <a href="http://www.webvk.ru" target="_blank">WEBVK</a></p><p>E-mail: <a href="mailto:mail@webvk">mail@webvk.ru</a></p></div>', '', '99%');

    // Содержание закладки 6
    $Tab6 = $PHPShopGUI->setText("<h3>Шаблон 'Новый заказ' для администратора: </h3>",'',false)
            .$PHPShopGUI->setInput("button","","Название интернет-магазина","","","ShablonAdd('order_template_admin_sms_new','Интернет-магазин - @NameShop@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Идентификатор заказа","","","ShablonAdd('order_template_admin_sms_new','Оформлен новый заказ №@OrderNum@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","ФИО заказчика","","","ShablonAdd('order_template_admin_sms_new','Имя - @UserFio@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Телефон заказчика","","","ShablonAdd('order_template_admin_sms_new','Телефон - @UserPhone@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","E-mail заказчика","","","ShablonAdd('order_template_admin_sms_new','E-mail - @UserMail@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Доставка","","","ShablonAdd('order_template_admin_sms_new','Способ доставки - @UserDelivery@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Страна","","","ShablonAdd('order_template_admin_sms_new','Страна - @UserCountry@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Регион","","","ShablonAdd('order_template_admin_sms_new','Регион - @UserState@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Город","","","ShablonAdd('order_template_admin_sms_new','Город - @UserCity@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Почтовый индекс","","","ShablonAdd('order_template_admin_sms_new','Почтовый индекс - @UserIndex@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Улица","","","ShablonAdd('order_template_admin_sms_new','Улица - @UserStreet@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Дом","","","ShablonAdd('order_template_admin_sms_new','Дом - @UserHouse@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Подъезд","","","ShablonAdd('order_template_admin_sms_new','Подъезд - @UserPorch@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Код домофона","","","ShablonAdd('order_template_admin_sms_new','Код домофона - @UserDoorPhone@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Квартира","","","ShablonAdd('order_template_admin_sms_new','Квартира - @UserFlat@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Время доставки","","","ShablonAdd('order_template_admin_sms_new','Время доставки - @UserDelivtime@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Доп. инфо","","","ShablonAdd('order_template_admin_sms_new','Доп. инфо - @UserDopInfo@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Состав заказа","","","ShablonAdd('order_template_admin_sms_new','СОСТАВ ЗАКАЗА:".'\r\n'."@Order@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setInput("button","","Отдельно общая сумма заказа","","","ShablonAdd('order_template_admin_sms_new','Общая сумма заказа: @CommonSumOrder@')","btn-sm btn-primary","",
            "","")
            .$PHPShopGUI->setTextarea('order_template_admin_sms_new',$order_template_admin_sms,false,'',500);
    
    
    // Содержание закладки 7
    $Tab7 = $PHPShopGUI->setInfo('
      <div>
      <p><b>Служба поддержки</b></p>

      <p>График работы: с 10:00 до 19:00 (ежедневно, кроме выходных)</p>
      
      <p>Тел.: <a href="tel:8-800-550-17-89">8-800-550-17-89</a></p>
      <p><a href="mailto:support@incore1.ru">support@incore1.ru</a></p>
      </div>
    ', '', '95%');
    
    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,true),array("Шаблоны для администратора",'<div class="smsgate">'.$Tab6.'</div>',true),array("Шаблоны для заказчика",'<div class="smsgate">'.$Tab2.'</div>',true),array("Инструкция",$Tab3),array("О Модуле",$Tab4),array("Разработчик",$Tab5),array("Поддержка",$Tab7));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("submit","saveID","ОК","right",70,"","but","actionUpdate");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'],'actionStart');


?>


