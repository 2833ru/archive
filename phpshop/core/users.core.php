<?php

// Библиотеки
PHPShopObj::loadClass('user');
PHPShopObj::loadClass('mail');
PHPShopObj::loadClass('order');
PHPShopObj::loadClass('delivery');

/**
 * Обработчик кабинета пользователя
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopUsers
 * @version 1.5
 * @package PHPShopCore
 */
class PHPShopUsers extends PHPShopCore {

    var $activation = false;

    /**
     * Конструктор
     */
    function __construct() {

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['shopusers'];

        // Отладка
        $this->debug = false;

        // Список экшенов
        $this->action = array('get' => array('productId', 'noticeId'), 'post' => array('add_notice', 'update_password', 'add_user', 'update_user', 'passw_send'),
            'name' => array('register', 'order', 'wishlist', 'useractivate', 'sendpassword', 'notice', 'message', 'newsletter'), 'nav' => 'index');

        // Префикс для экшенов методов
        $this->action_prefix = 'action_';

        // Элемент формы авторизациии пользователя
        $this->PHPShopUserElement = new PHPShopUserElement();

        // Локализация
        $this->locale = array();

        parent::__construct();

        // Проверка на подтверждение активации
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.user_mail_activate') or $this->PHPShopSystem->ifSerilizeParam('admoption.user_mail_activate_pre'))
            $this->activation = true;
    }

    /**
     * Экшен по умочанию
     */
    function action_index() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // Проверка прохождения авторизации
        if ($this->true_user()) {

            // Форма редактирования персональных данных
            $this->user_info();
        } else {
            // Форма регистрации нового пользователя
            $this->action_register();
        }
    }

    /**
     * Экшен записи нового уведомления
     */
    function action_add_notice() {
        if ($this->true_user()) {
            if (PHPShopSecurity::true_num($_POST['productId']))
                $this->notice_add();
            else
                $this->action_notice();
        } else {
            // Форма регистрации нового пользователя
            $this->action_register();
        }
    }

    /**
     * Экшен списка всех сообщений
     */
    function action_message() {
        if ($this->true_user()) {
            $this->user_message();
        } else {
            // Форма регистрации нового пользователя
            $this->action_register();
        }
    }

    /**
     * Вывод списка сообщений
     * Функция вынесена в отдельный файл users.core/user_message.php
     */
    function user_message() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__);
    }

    /**
     * Экшен списка всех уведомлений
     */
    function action_notice() {
        if ($this->true_user()) {
            $this->notice_list();
        } else {
            // Форма регистрации нового пользователя
            $this->action_register();
        }
    }

    /**
     * Вывод списка уведомлений
     */
    function notice_list() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__);
    }

    /**
     * Запись нового уведомления
     */
    function notice_add() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__);
    }

    /**
     * Экшен удаления уведомления
     */
    function action_noticeId() {
        if ($this->true_user()) {
            if (PHPShopSecurity::true_num($_GET['noticeId'])) {
                $PHPShopOrm = new PHPShopOrm($this->getValue('base.notice'));
                $PHPShopOrm->debug = $this->debug;
                $PHPShopOrm->delete(array('user_id' => '=' . $this->UsersId, 'id' => '=' . $_GET['noticeId']));
                $this->action_notice();
            } else
                $this->setError404();
        }
        else {
            // Форма регистрации нового пользователя
            $this->action_register();
        }
    }

    /**
     * Экшен форма уведомления
     */
    function action_productId() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        if (PHPShopSecurity::true_num($_GET['productId'])) {
            $PHPShopProduct = new PHPShopProduct($_GET['productId']);
            if (PHPShopSecurity::true_num($PHPShopProduct->getParam('id'))) {
                $this->set('productId', $_GET['productId']);
                $this->set('pic_small', $PHPShopProduct->getParam('pic_small'));
                $this->set('name', $PHPShopProduct->getParam('name'));

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $PHPShopProduct, 'MIDDLE');

                if ($this->true_user())
                    $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/notice.tpl', true));
                else
                    $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/notice_no_auth.tpl', true));
                $this->set('formaTitle', __('Уведомления'));

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $PHPShopProduct, 'END');

                $this->ParseTemplate($this->getValue('templates.users_page_list'));
            } else
                $this->setError404();
        }
    }

    /**
     * Вывод подробных данных по заказам
     * Функция вынесена в отдельный файл users.core/order_info.php
     * @return mixed
     */
    function action_order_info() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, $tip = 1);
    }

    /**
     * Вывод таблицы заказов пользователя
     * Функция вынесена в отдельный файл users.core/order_list.php
     * @return mixed
     */
    function order_list() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, $tip = 1);
    }

    /**
     * Шифрование ссылки на файлы
     * @param string $files имя файла
     * @return string 
     */
    function link_encode($files) {
        $str = array(
            "files" => $files,
            "time" => (time("U") + ($this->getValue('my.digital_time') * 86400))
        );
        $str = serialize($str);
        $code = base64_encode($str);
        $code2 = str_replace($this->getValue('my.digital_pass1'), "!", $code);
        $code2 = str_replace($this->getValue('my.digital_pass2'), "$", $code2);

        return $code2;
    }

    /**
     * Чистка старых активаций
     */
    function clean_old_activation() {
        $nowData = time() - 432000;
        $this->PHPShopOrm->delete(array('datas' => '<' . $nowData, 'enabled' => "='0'"));
        $this->PHPShopOrm->clean();
    }

    /**
     * Проверка ключа активации
     */
    function true_key($passw) {
        return preg_match("/^[a-zA-Z0-9_]{4,35}$/", $passw);
    }

    /**
     * Экшен активации по ключу
     */
    function action_useractivate() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        if ($this->true_key($_GET['key'])) {

            // Чистка старых активаций
            $this->clean_old_activation();

            $data = $this->PHPShopOrm->select(array('login'), array('status' => "='" . $_GET['key'] . "'"), false, array('limit' => 1));
            if (!empty($data['login'])) {

                $this->set('date', date("d-m-y H:i a"));
                $this->set('user_ip', $_SERVER['REMOTE_ADDR']);
                $this->set('user_name', $data['login']);
                $this->set('user_login', $data['login']);

                if (!$this->PHPShopSystem->ifSerilizeParam('admoption.user_mail_activate_pre')) {

                    $this->PHPShopOrm->clean();

                    $this->PHPShopOrm->update(array('enabled_new' => '1', 'status_new' => $this->PHPShopSystem->getSerilizeParam('admoption.user_status')), array('status' => "='" . $_GET['key'] . "'"));

                    $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/message_activation_done.tpl', true));
                } else {

                    $this->PHPShopOrm->clean();

                    $this->PHPShopOrm->update(array('status_new' => $this->PHPShopSystem->getSerilizeParam('admoption.user_status')), array('status' => "='" . $_GET['key'] . "'"));

                    // Заголовок e-mail администратору
                    $title = $this->lang('activation_admin_title') . " " . $_POST['name_new'];

                    // Содержание e-mail администратору
                    $content = ParseTemplateReturn('./phpshop/lib/templates/users/mail_admin_activation.tpl', true);

                    // Отправка e-mail администратору
                    $PHPShopMail = new PHPShopMail($this->PHPShopSystem->getValue('adminmail2'), $this->PHPShopSystem->getValue('adminmail2'), $title, '', true, true,array('replyto'=>$data['login']));
                    
                    // Содержание e-mail  администратору
                    $content = ParseTemplateReturn('./phpshop/lib/templates/users/mail_admin_activation.tpl', true);
                    $PHPShopMail->sendMailNow($content);

                    $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/message_admin_activation.tpl', true), true);
                }
            } else {
                $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/message_activation_error.tpl', true));
            }

            $this->set('formaTitle', $this->lang('user_register_title'));

            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__, $data, 'END');

            $this->ParseTemplate($this->getValue('templates.users_page_list'));
        } else
            $this->action_register();
    }

    /**
     * Экшен вывода заказов
     */
    function action_order() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        if ($this->true_user()) {

            // Форма вывода заказов
            $this->order_list();

            // Ожидание экшена подробного описания заказа
            $this->waitAction('order_info');

            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__, false, 'END');

            $this->ParseTemplate($this->getValue('templates.users_page_list'));
        } else {

            // Сообщение об обязательной авторизации
            $this->set('usersError', __('Требуется авторация пользователя'));

            // Форма регистрации нового пользователя
            $this->action_register();
        }
    }

    /**
     * Экшен обновления/добавления  адреса пользователя.
     */
    function update_user_adres() {
        if (PHPShopSecurity::true_email($_POST['mail'])) {
            $data = $this->PHPShopOrm->select(array('data_adres'), array('mail' => "='" . $_POST['mail'] . "'"), false, array('limit' => 1));
            if ($data['data_adres'])
                $data_adres = unserialize($data['data_adres']);

            // формируем массив нового адреса
            if (!empty($_POST['country_new']))
                $newAdres['country_new'] = PHPShopSecurity::CleanStr(@$_POST['country_new']);
            if (!empty($_POST['state_new']))
                $newAdres['state_new'] = PHPShopSecurity::CleanStr(@$_POST['state_new']);
            if (!empty($_POST['city_new']))
                $newAdres['city_new'] = PHPShopSecurity::CleanStr(@$_POST['city_new']);
            if (!empty($_POST['index_new']))
                $newAdres['index_new'] = PHPShopSecurity::CleanStr(@$_POST['index_new']);
            if (!empty($_POST['fio_new']))
                $newAdres['fio_new'] = PHPShopSecurity::CleanStr(@$_POST['fio_new']);
            if (!empty($_POST['tel_new']))
                $newAdres['tel_new'] = PHPShopSecurity::CleanStr(@$_POST['tel_new']);
            if (!empty($_POST['street_new']))
                $newAdres['street_new'] = PHPShopSecurity::CleanStr(@$_POST['street_new']);
            if (!empty($_POST['house_new']))
                $newAdres['house_new'] = PHPShopSecurity::CleanStr(@$_POST['house_new']);
            if (!empty($_POST['porch_new']))
                $newAdres['porch_new'] = PHPShopSecurity::CleanStr(@$_POST['porch_new']);
            if (!empty($_POST['door_phone_new']))
                $newAdres['door_phone_new'] = PHPShopSecurity::CleanStr(@$_POST['door_phone_new']);
            if (!empty($_POST['flat_new']))
                $newAdres['flat_new'] = PHPShopSecurity::CleanStr(@$_POST['flat_new']);
            if (!empty($_POST['delivtime_new']))
                $newAdres['delivtime_new'] = PHPShopSecurity::CleanStr(@$_POST['delivtime_new']);

            if (!empty($_POST['org_name_new']))
                $newAdres['org_name_new'] = PHPShopSecurity::CleanStr(@$_POST['org_name_new']);
            if (!empty($_POST['org_inn_new']))
                $newAdres['org_inn_new'] = PHPShopSecurity::CleanStr(@$_POST['org_inn_new']);
            if (!empty($_POST['org_kpp_new']))
                $newAdres['org_kpp_new'] = PHPShopSecurity::CleanStr(@$_POST['org_kpp_new']);
            if (!empty($_POST['org_yur_adres_new']))
                $newAdres['org_yur_adres_new'] = PHPShopSecurity::CleanStr(@$_POST['org_yur_adres_new']);
            if (!empty($_POST['org_fakt_adres_new']))
                $newAdres['org_fakt_adres_new'] = PHPShopSecurity::CleanStr(@$_POST['org_fakt_adres_new']);
            if (!empty($_POST['org_ras_new']))
                $newAdres['org_ras_new'] = PHPShopSecurity::CleanStr(@$_POST['org_ras_new']);
            if (!empty($_POST['org_bank_new']))
                $newAdres['org_bank_new'] = PHPShopSecurity::CleanStr(@$_POST['org_bank_new']);
            if (!empty($_POST['org_kor_new']))
                $newAdres['org_kor_new'] = PHPShopSecurity::CleanStr(@$_POST['org_kor_new']);
            if (!empty($_POST['org_bik_new']))
                $newAdres['org_bik_new'] = PHPShopSecurity::CleanStr(@$_POST['org_bik_new']);
            if (!empty($_POST['org_city_new']))
                $newAdres['org_city_new'] = PHPShopSecurity::CleanStr(@$_POST['org_city_new']);

            if (is_array($newAdres) AND count($newAdres)) {
                // если прислан ИД используемого адреса, обновляем его
                if (isset($_POST['adres_id']) AND is_numeric($_POST['adres_id'])) {
                    $id = intval($_POST['adres_id']);
                    if (is_array($newAdres))
                        $data_adres['list'][$id] = array_merge($data_adres['list'][$id], $newAdres);
                } else {
                    // Если новый адрес сохраняем его в массив
                    $data_adres['list'][] = $newAdres;
                    // получаем Ид добавленного адреса
                    end($data_adres['list']);         // move the internal pointer to the end of the array
                    $id = key($data_adres['list']);
                }

                if ((!empty($_POST['adres_this_default']) AND $_POST['adres_this_default']) OR ! isset($data_adres['main']) OR ! isset($data_adres['list'][$data_adres['main']])) {
                    $data_adres['main'] = $id;
                }

                $data_adres = serialize($data_adres);

                $this->PHPShopOrm->clean();
                $this->PHPShopOrm->update(array(
                    'data_adres_new' => $data_adres), array('mail' => "='" . $_POST['mail'] . "'"));
            }
            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__, $_POST);

            return $newAdres;
        }
    }

    /**
     * Экшен обновления персональных данных
     */
    function action_update_user() {

        if (PHPShopSecurity::true_num($_SESSION['UsersId'])) {

            // запрещаем изменение e-mail
//            if (!PHPShopSecurity::true_email($_POST['mail_new']))
//                $this->error[] = $this->lang('error_mail');

            if (strlen($_POST['name_new']) < 3)
                $this->error[] = $this->lang('error_name');

            if (count($this->error) == 0) {
                // обновляем имя в сессии для вывода в топе.
                $_SESSION['UsersName'] = PHPShopSecurity::TotalClean($_POST['name_new']);
                $this->PHPShopOrm->update(array(
                    //'mail_new' => $_POST['mail_new'],
                    'name_new' => PHPShopSecurity::TotalClean($_POST['name_new']),
                    'company_new' => PHPShopSecurity::TotalClean($_POST['company_new']),
                    'inn_new' => PHPShopSecurity::TotalClean($_POST['inn_new']),
                    'tel_new' => PHPShopSecurity::TotalClean($_POST['tel_new']),
                    'adres_new' => PHPShopSecurity::TotalClean($_POST['adres_new']),
                    'kpp_new' => PHPShopSecurity::TotalClean($_POST['kpp_new']),
                    'tel_code_new' => PHPShopSecurity::TotalClean($_POST['tel_code_new'])), array('id' => '=' . $_SESSION['UsersId']));
                $this->error[] = $this->lang('done');

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $_POST);
            }
        }

        // Список ошибок смены данных
        $this->error();

        // Форма персональных данных пользователя
        $this->user_info();
    }

    /**
     * Экшен формы восстановления пароля
     */
    function action_sendpassword() {
        $this->set('formaTitle', __('Восстановление пароля'));

        // Шаблон формы восстановления пароля
        if (PHPShopParser::checkFile("users/register.tpl"))
            $this->set('formaContent', ParseTemplateReturn('users/sendpassword.tpl'));
        else
            $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/sendpassword.tpl', true));

        $this->setHook(__CLASS__, __FUNCTION__);
        $this->ParseTemplate($this->getValue('templates.users_page_list'));
    }

    /**
     * Экшен отправления пароля по почте
     */
    function action_passw_send() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        if (PHPShopSecurity::true_email($_POST['login'])) {
            $this->PHPShopOrm->clean();
            $data = $this->PHPShopOrm->select(array('*'), array('login' => '="' . $_POST['login'] . '"'), false, array('limit' => 1));
            if (is_array($data)) {

                $this->set('date', date("d-m-y H:i a"));
                $this->set('user_ip', $_SERVER['REMOTE_ADDR']);
                $this->set('user_login', $data['login']);
                $this->set('user_name', $data['name']);
                $this->set('user_mail', $data['login']);
                $this->set('user_password', $this->decode($data['password']));

                // Заголовок e-mail пользователю
                $title = $this->PHPShopSystem->getName() . " - " . __('Восстановление пароля пользователя') . " " . $_POST['login'];
                $title = __('Восстановление пароля пользователя') . " " . $_POST['login'];

                // Отправка e-mail пользователю
                $PHPShopMail = new PHPShopMail($data['login'], $this->PHPShopSystem->getParam('adminmail2'), $title, '', true, true);
                
                // Содержание e-mail пользователю
                $content = ParseTemplateReturn('./phpshop/lib/templates/users/mail_sendpassword.tpl', true);
                $PHPShopMail->sendMailNow($content);

                // Сообщение об успешном отправлении пароля
                $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/message_sendpassword.tpl', true));
            } else {
                // Сообщение об успешном отправлении пароля
                $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/message_sendpassword_error.tpl', true));
            }
        }

        $this->set('formaTitle', __('Личный кабинет'));
        $this->ParseTemplate($this->getValue('templates.users_page_list'));
    }

    /**
     * Экшен обновления пароля пользователя
     */
    function action_update_password() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        //Проверка валидности новых данных
        if (PHPShopSecurity::true_num($_SESSION['UsersId'])) {

            if ($_POST['password_new'] != $_POST['password_new2'])
                $this->error[] = $this->lang('error_password');

            if (!PHPShopSecurity::true_email($_POST['login_new']))
                $this->error[] = $this->lang('error_login');
            else
                $update['mail_new'] = PHPShopSecurity::TotalClean($_POST['login_new'], 3);

            if (!PHPShopSecurity::true_passw($_POST['password_new']))
                $this->error[] = $this->lang('error_password_hack');

            $update['login_new'] = PHPShopSecurity::TotalClean($_POST['login_new'], 3);
            $update['password_new'] = $this->encode($_POST['password_new']);

            if (count($this->error) == 0) {
                $this->PHPShopOrm->update($update, array('id' => '=' . $_SESSION['UsersId']));
                $this->error[] = $this->lang('done');
            }
        }

        // Список ошибок смены данных
        $this->error();

        // Форма персональных данных пользователя
        $this->user_info();
    }

    /**
     * Сообщения и ошибки о смене данных пользователем
     */
    function error() {
        $user_error = null;
        if (is_array($this->error))
            foreach ($this->error as $val)
                $user_error.=PHPShopText::ul(PHPShopText::li($val));

        $this->set('user_error', $user_error);
    }

    /**
     * вывод товаров вишлиста
     */
    function action_wishlist() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        $dis = null;

        $this->set('formaTitle', 'Отложенные товары');

        if ($this->true_user()) {
            $PHPShopUser = new PHPShopUser($_SESSION['UsersId']);
            $wishlist = unserialize($PHPShopUser->objRow['wishlist']);
            if (is_array($wishlist)) {
                
                // удаление из вишлиста
                if ($_REQUEST['delete']) {
                    unset($wishlist[$_REQUEST['delete']]);
                    // обновляем кол-во для вывода в топе
                    $_SESSION['wishlistCount'] = count($wishlist);
                    $this->PHPShopOrm->update(array(
                        'wishlist' => serialize($wishlist)), array('id' => '=' . $_SESSION['UsersId']), false, false);
                    header("Location: ./wishlist.html");
                    die();
                }
                foreach ($wishlist as $key => $value) {

                    // Данные по товару
                    $objProduct = new PHPShopProduct($key);

                    if ($objProduct->getParam("enabled") == 1) {
                        
                        if($objProduct->getParam("sklad") == 1)
                            $this->set('prodDisabled', 'disabled');
                        else $this->set('prodDisabled', '');

                        $this->set('prodId', $key);
                        $this->set('prodName', $objProduct->getParam("name"));
                        $this->set('prodPic', $objProduct->getParam("pic_small"));
                        // цена
                        $this->set('prodPrice', PHPShopProductFunction::GetPriceValuta($objProduct->objRow['id'], array($objProduct->objRow['price'], $objProduct->objRow['price2'], $objProduct->objRow['price3'], $objProduct->objRow['price4'], $objProduct->objRow['price5']), $objProduct->objRow['baseinputvaluta']));
                        $dis.= ParseTemplateReturn('users/wishlist/wishlist_list_one.tpl');
                    }
                }
            }
            if ($dis) {
                $this->set('wishlistList', $dis);
                $this->set('formaContent', ParseTemplateReturn('users/wishlist/wishlist_list_main.tpl'));
            } else {
                $this->set('formaContent', ParseTemplateReturn('users/wishlist/wishlist_list_empty.tpl'));
            }
            $this->ParseTemplate($this->getValue('templates.users_page_list'));
        } else {

            // Форма регистрации нового пользователя
            $this->action_register();
        }
    }

    /**
     * Персональные данные пользователя
     */
    function user_info() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        $this->PHPShopUser = new PHPShopUser($_SESSION['UsersId']);

        // Персональные данные пользователя
        $this->set('user_status', $this->PHPShopUser->getStatusName());

        if ($this->get('user_status') == "")
            $this->set('user_status', __('Авторизованный пользователь'));

        //Максимальная скидка
        $discount = 0 + max($this->PHPShopUser->getDiscount(), $this->PHPShopUser->getParam('cumulative_discount'));

        $this->set('user_login', $this->PHPShopUser->getParam('login'));
        $this->set('user_password', $this->decode($this->PHPShopUser->getParam('password')));
        $this->set('user_name', $this->PHPShopUser->getName());
        $this->set('user_mail', $this->PHPShopUser->getParam('mail'));
        $this->set('user_company', $this->PHPShopUser->getParam('company'));
        $this->set('user_inn', $this->PHPShopUser->getParam('inn'));
        $this->set('user_tel', $this->PHPShopUser->getParam('tel'));
        $this->set('user_tel_code', $this->PHPShopUser->getParam('tel_code'));
        $this->set('user_adres', $this->PHPShopUser->getParam('adres'));
        $this->set('user_kpp', $this->PHPShopUser->getParam('kpp'));
        $this->set('user_cumulative_discount', $discount);

        // Форма личного кабинета пользователя
        $this->set('formaTitle', $this->lang('user_info_title'));

        // Шаблон смены паролей
        if (PHPShopParser::checkFile("users/users_page_info.tpl"))
            $this->set('formaContent', ParseTemplateReturn('users/users_page_info.tpl'));
        else
            $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/info.tpl', true));

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $this->PHPShopUser, 'END');

        $this->ParseTemplate($this->getValue('templates.users_page_list'));
    }

    /**
     * Проверки прохождения авторизации
     * @return bool
     */
    function true_user() {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook)
            return $hook;

        if (PHPShopSecurity::true_num($_SESSION['UsersId'])) {
            $this->UsersId = $_SESSION['UsersId'];
            $this->UsersStatus = $_SESSION['UsersStatus'];
            return true;
        }
    }

    /**
     * Кодирование пароля
     * @param string $str пароль
     * @return string
     */
    function encode($str) {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $str);
        if ($hook)
            return $hook;

        return base64_encode($str);
    }

    /**
     * Декодирование пароля
     * @param string $str пароль
     * @return string
     */
    function decode($str) {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $str);
        if ($hook)
            return $hook;

        return base64_decode($str);
    }

    /**
     * Проверка нового пользователя
     * @return Bool
     */
    function add_user_check() {

        // Проверка на защитную картинку
        if (empty($_SESSION['text']) or ( strtolower($_POST['key']) != strtolower($_SESSION['text']))) {
            $this->error[] = $this->lang('error_key');
            return false;
        }

        // логин и есть емейл
        $_POST['mail_new'] = $_POST['login_new'];

        // Проверка уникальности логина и его валидности
        if (PHPShopSecurity::true_email($_POST['login_new'])) {
            $data = $this->PHPShopOrm->select(array('id'), array('login' => "='" . $_POST['login_new'] . "'"), false, array('limit' => 1));
            if (!empty($data['id']))
                $this->error[] = $this->lang('error_id');
        } else {
            $this->error[] = $this->lang('error_login');
        }

        // Проверка равности паролей 1 и 2
        if ($_POST['password_new'] != $_POST['password_new2'])
            $this->error[] = $this->lang('error_password');

        // Проверка валидности пароля
        if (!PHPShopSecurity::true_passw($_POST['password_new']))
            $this->error[] = $this->lang('error_password_hack');

        // Проверка валидности имени
        if (strlen($_POST['name_new']) < 3)
            $this->error[] = $this->lang('error_name');

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $_POST);

        if (count($this->error) == 0)
            return true;
    }

    /**
     * Запись нового пользователя в БД
     * @return Int ИД нового пользователя в БД
     */
    function add($content = false, $list = false) {

        // Проверка на подтверждение активации
        if (!$this->activation) {
            $user_mail_activate = 1;
            $this->user_status = $this->PHPShopSystem->getSerilizeParam('admoption.user_status');
        } else {
            $user_mail_activate = 0;
            $this->user_status = md5(time());
        }

        //Подписка
        if ($_POST['subscribe_new'] == 'on') {
            $subscribe = 1;
        }

        // Массив данных нового пользователя
        $insert = array(
            'login_new' => PHPShopSecurity::TotalClean($_POST['login_new'], 3),
            'password_new' => $this->encode($_POST['password_new']),
            'datas_new' => time(),
            'mail_new' => PHPShopSecurity::TotalClean($_POST['mail_new'], 3),
            'name_new' => PHPShopSecurity::TotalClean($_POST['name_new']),
            'company_new' => PHPShopSecurity::TotalClean($_POST['company_new']),
            'inn_new' => PHPShopSecurity::TotalClean($_POST['inn_new']),
            'tel_new' => PHPShopSecurity::TotalClean($_POST['tel_new']),
            'adres_new' => PHPShopSecurity::TotalClean($_POST['adres_new']),
            'enabled_new' => $user_mail_activate,
            'status_new' => $this->user_status,
            'kpp_new' => PHPShopSecurity::TotalClean($_POST['kpp_new']),
            'subscribe_new' => $subscribe,
            'tel_code_new' => PHPShopSecurity::TotalClean($_POST['tel_code_new'])
        );

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $insert);
        if (is_array($hook))
            $insert = $hook;

        // Запись в БД
        $result = $this->PHPShopOrm->insert($insert);

        // Возвращаем ИД нового пользователя
        return $result;
    }

    /**
     * Экшен проверки существования пользователя по email. Если существует, возвращает ИД
     */
    function user_check_by_email($login) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.shopusers'));
        $PHPShopOrm->debug = $this->debug;
        if (PHPShopSecurity::true_email($login)) {
            $data = $PHPShopOrm->select(array('id'), array('mail' => '="' . trim($login) . '"'), false, array('limit' => 1));
            if (is_array($data) AND PHPShopSecurity::true_num($data['id'])) {
                return $data['id'];
            }
        }
        return false;
    }

    /**
     * Экшен генерации пароля пользователя
     */
    function generatePassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }

    /**
     * Экшен добавления нового пользователя со страницы оформления заказа
     */
    function add_user_from_order($login) {

        // Отключение активации в заказе
        $this->activation = false;

        $_SESSION['text'] = $_POST['key'] = "fromOrder";
        // логин и есть емейл
        $_POST['mail_new'] = $_POST['login_new'] = $login;
        $_POST['password_new'] = $_POST['password_new2'] = $this->generatePassword();

        $this->UsersId = $this->user_check_by_email($login);
        // если пользователь существующий, авторизуем его
        if (!$this->UsersId)
            $this->action_add_user();

        if ($this->UsersId)
            return $this->UsersId;
        else
            return false;
    }

    /**
     * Экшен добавления нового пользователя с формы подписки на рассылку
     */
    function action_newsletter() {

        // Отключение активации в заказе
//        $this->activation = true;

        $_SESSION['text'] = $_POST['key'] = "fromOrder";
        // логин и есть емейл
        $login = $_REQUEST['newsletter_email'];
        $_POST['mail_new'] = $_POST['login_new'] = $login;
        $_POST['password_new'] = $_POST['password_new2'] = $this->generatePassword();
        $_POST['name_new'] = "Участник рассылки";

        $this->UsersId = $this->user_check_by_email($login);
        // если пользователь существующий, авторизуем его
        if ($this->UsersId) {
            // пользователь уже добавлен в рассылку, сообщаем об этом
            if (PHPShopParser::checkFile("users/newsletter/newsletter_user_exist.tpl"))
                $this->Disp = ParseTemplateReturn('users/newsletter/newsletter_user_exist.tpl');
            else
                $this->Disp = ParseTemplateReturn('phpshop/lib/templates/users/newsletter/newsletter_user_exist.tpl', true);

            return true;
        }

        if (!$this->UsersId) {
            $this->action_add_user();
        }

        if (count($this->error)) {
            // выводим сообщение об ошибке добавления в рассылку
            if (PHPShopParser::checkFile("users/newsletter/newsletter_add_error.tpl"))
                $this->Disp = ParseTemplateReturn('users/newsletter/newsletter_add_error.tpl');
            else
                $this->Disp = ParseTemplateReturn('phpshop/lib/templates/users/newsletter/newsletter_add_error.tpl', true);

            return true;
        }

        if ($this->UsersId) {
            if (!$this->activation) {
                // выводим сообщение об успешном добавлении в рассылку
                if (PHPShopParser::checkFile("users/newsletter/newsletter_add_success.tpl"))
                    $this->Disp = ParseTemplateReturn('users/newsletter/newsletter_add_success.tpl');
                else
                    $this->Disp = ParseTemplateReturn('phpshop/lib/templates/users/newsletter/newsletter_add_success.tpl', true);
            }
            else {
                // выводим сообщение об успешном добавлении в рассылку + что нужно активировать email
                if (PHPShopParser::checkFile("users/newsletter/newsletter_add_success_need_activation.tpl"))
                    $this->Disp = ParseTemplateReturn('users/newsletter/newsletter_add_success_need_activation.tpl');
                else
                    $this->Disp = ParseTemplateReturn('phpshop/lib/templates/users/newsletter/newsletter_add_success_need_activation.tpl', true);
            }
        }
    }

    /**
     * Экшен добавления нового пользователя
     */
    function action_add_user() {
        // Если пройдена проверка на существующий логин
        if ($this->add_user_check()) {

            // Добавление запись в БД
            $this->UsersId = $this->add();

            // Проверка на подтверждение активации
            if (!$this->activation) {

                // авторизуем пользователя
                $_POST['login'] = $_POST['login_new'];
                $_POST['password'] = $_POST['password_new'];
                $this->PHPShopUserElement->autorization();


                // Сообщение об успешной регистрации
                $this->message_register_success();
                // Форма персональных данных
                $this->PHPShopUserElement->checkRedirect();
                //Показываем ЛК пользователя.
//                $this->user_info();
                $this->redirectToUserInfo();
            } else {

                // Сообщение об активации аккаунта
                $this->message_activation();
            }
        } else {

            // Список ошибок
            $this->error();

            // Форма регистрации
            $this->action_register();
        }
    }

    /**
     * Сообщение об успешной регистрации
     * Функция вынесена в отдельный файл users.core/message_register_success.php
     * @return mixed
     */
    function message_register_success() {
        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, false, 'users');
    }

    /**
     * Редирект в ЛК пользователя.
     */
    function redirectToUserInfo() {
        if ($this->PHPShopNav->getPath() != "done" AND $this->PHPShopNav->getName() != "newsletter")
            header("Location: " . $GLOBALS['SysValue']['dir']['dir'] . "/users/");
    }

    /**
     * Сообщение регистрации
     * Функция вынесена в отдельный файл users.core/message_activation.php
     * @return mixed
     */
    function message_activation() {

// Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, false, 'users');
    }

    /**
     * Экшен форма регистрации нового пользователя
     * Результат заполнения формы обработывается в action_add_user()
     */
    function action_register() {

        // Проверка прохождения авторизации
        if ($this->true_user()) {
            // Форма редактирования персональных данных
            $this->user_info();
            return;
        }

        $this->set('formaTitle', $this->lang('user_register_title'));

        // Шаблон регистрации
        if (PHPShopParser::checkFile("users/register.tpl"))
            $this->set('formaContent', ParseTemplateReturn('users/register.tpl'));
        else
            $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/register.tpl', true));

        $this->setHook(__CLASS__, __FUNCTION__);

        $this->ParseTemplate($this->getValue('templates.users_page_list'));
    }

    /**
     * Шаблонизация вывода данных
     * @return string
     */
    function tr() {
        $Arg = func_get_args();

        $tr = '<tr>';

        foreach ($Arg as $key => $val)
            if ($val != '-')
                $col[$key] = 1;
            else
                $col[$key] = 2;

        foreach ($Arg as $key => $val) {
            if ($val != '-')
                $tr.=PHPShopText::td($val, false, @$col[$key + 1], $id = 'allspecwhite');
        }

        $tr.='</tr>';
        return $tr;
    }

    /**
     * Шаблонизация вывода данных. Заголовок.
     * @return string
     */
    function caption() {
        $Arg = func_get_args();
        $tr = '<thead><tr id="allspec">';
        foreach ($Arg as $val) {
            $tr.=PHPShopText::td(PHPShopText::b($val), false, false, $id = 'allspecwhite');
        }
        $tr.='</tr></thead>';
        return $tr;
    }

}

?>