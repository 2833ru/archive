<?php

/**
 * Библиотека Dialog Bot
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 */
class PHPShopBot {

    protected $bot = 'message';
    public $protocol = 'http://';

    /**
     * Конструктор
     */
    public function __construct() {

        $this->PHPShopSystem = new PHPShopSystem();

        $this->PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['dialog']);

        // Путь сохранения вложений
        $this->image_dialog_path = $this->PHPShopSystem->getSerilizeParam('admoption.image_dialog_path');

        if (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])) {
            $this->protocol = 'https://';
        }
    }

    public function dialog($message) {

        if (empty($message['attachments']))
            $message['attachments'] = null;

        if (empty($message['order_id']))
            $message['order_id'] = null;

        // Картинка
        if (!empty($message['photo']) and is_array($message['photo'])) {
            $file = $message['photo'][count($message['photo']) - 1]['file_id'];

            if (empty($message['caption']))
                $message['caption'] = "Картинка";

            $message['text'] = $message['caption'];
            $message['attachments'] = $this->file($file);
        }

        // Файл
        else if (!empty($message['document'])) {
            $file = $message['document']['file_id'];

            if (empty($message['caption']))
                $message['caption'] = "Файл";

            $message['text'] = $message['caption'];
            $message['attachments'] = $this->file($file);
        }

        $insert = array(
            'user_id' => $message['user_id'],
            'name' => PHPShopString::utf8_win1251($message['chat']['first_name']),
            'message' => PHPShopString::utf8_win1251(strip_tags($message['text'])),
            'chat_id' => $message['chat']['id'],
            'time' => $message['date'],
            'staffid' => $message['staffid'],
            'bot' => $this->bot,
            'attachments' => $message['attachments'],
            'isview' => $message['isview'],
            'isview_user' => $message['isview_user'],
            'order_id' => $message['order_id']
        );

        $this->PHPShopOrm->insert($insert, '');
    }

    // Отправка картинки
    public function send_image($id, $message, $file) {
        return true;
    }

    // Отправка файла
    public function send_file($id, $message, $file) {
        return true;
    }

    // Отправка текста
    public function send($id, $message) {
        return true;
    }

    // Уведомление администратору
    public function notice($message, $bot = false) {

        // Проверка бана
        if (empty($message['isview'])) {

            $message['from']['first_name'] = PHPShopString::win_utf8($message['chat']['first_name']);
            $message['text'] = strip_tags($message['text']);
            $msg = __('Сообщение в диалогах от ') . $message['chat']['first_name'];

            if ($this->PHPShopSystem->ifSerilizeParam('admoption.telegram_dialog')) {
                $PHPShopBot = new PHPShopTelegramBot();
                $PHPShopBot->notice_telegram($message, $bot);
            }

            if ($this->PHPShopSystem->ifSerilizeParam('admoption.vk_dialog')) {
                $PHPShopBot = new PHPShopVKBot();
                $PHPShopBot->notice_vk($message, $bot);
            }

            if ($this->PHPShopSystem->ifSerilizeParam('admoption.push_dialog')) {
                PHPShopObj::loadClass(array("push"));
                $PHPShopPush = new PHPShopPush();
                $PHPShopPush->send($msg);
            }

            if ($this->PHPShopSystem->ifSerilizeParam('admoption.mail_dialog', 1)) {
                PHPShopObj::loadClass(array("parser", "mail"));
                $adminmail = $this->PHPShopSystem->getEmail();
                if (empty($GLOBALS['_classPath']))
                    $GLOBALS['_classPath'] = '../phpshop/';
                $GLOBALS['PHPShopSystem'] = $this->PHPShopSystem;
                $PHPShopMail = new PHPShopMail($adminmail, $adminmail, $msg, '', true, true);
                $link = '<br><a href="' . $this->protocol . $_SERVER['SERVER_NAME'] . '/phpshop/admpanel/admin.php?path=dialog&id=' . $message['user_id'] . '&bot=' . $bot . '&user=' . $message['user_id'] . '" target="_blank">' . __('Ответить') . '</a>';

                PHPShopParser::set('message', $msg . ': ' . PHPShopString::utf8_win1251($message['text']) . $link);
                $content = ParseTemplateReturn('./phpshop/lib/templates/order/blank.tpl', true);
                $PHPShopMail->sendMailNow($content);
            }
        }
    }

    public function find($user) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['dialog']);
        $PHPShopOrm->debug = false;
        $data = $PHPShopOrm->getOne(array('chat_id'), array('user_id' => '="' . intval($user) . '"', 'bot' => '="' . $this->bot . '"'));
        return $data['chat_id'];
    }

    // Отладка
    public function log($data) {
        ob_start();
        print_r(unserialize($data));
        $log = ob_get_clean();
        return $log;
    }

}

/**
 * Библиотека VK Bot
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 */
class PHPShopVKBot extends PHPShopBot {

    protected $bot = 'vk';
    protected $version = '5.81';

    /**
     * Конструктор
     */
    public function __construct() {

        $this->PHPShopSystem = new PHPShopSystem();

        $this->confirmation = $this->PHPShopSystem->getSerilizeParam('admoption.vk_confirmation');
        $this->secret = $this->PHPShopSystem->getSerilizeParam('admoption.vk_secret');
        $this->token = $this->PHPShopSystem->getSerilizeParam('admoption.vk_token');
        $this->enabled = $this->PHPShopSystem->getSerilizeParam('admoption.vk_enabled');
        $this->vk_admin = $this->PHPShopSystem->getSerilizeParam('admoption.vk_admin');

        if ($this->token == '')
            $this->enabled = 0;

        $this->PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['dialog']);
    }

    public function user($id) {

        $data = array(
            'user_ids' => $id,
            'v' => $this->version,
        );
        $out = $this->request('users.get', $data);


        $user_name = $out[response][0][first_name] . ' ' . $out[response][0][last_name];
        return $user_name;
    }

    public function init($message) {

        if ($this->enabled == 1) {

            $type = $message['type'];
            $chatId = $message['object']['user_id'];

            if ($type == 'message_new') {

                $token = $message['object']['ref'];

                // Новый чат
                if ($token) {
                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
                    $PHPShopOrm->debug = false;
                    $data = $PHPShopOrm->getOne(array('id'), array('bot' => '="' . $token . '"'));
                    $user = $data['id'];

                    if ($user !== null) {

                        $insert = array(
                            'user_id' => $user,
                            'chat' => array
                                (
                                'id' => $chatId,
                                'first_name' => "Администрация",
                                'last_name' => "",
                            ),
                            'date' => time(),
                            'staffid' => 0,
                            'isview' => 1,
                            'isview_user' => 0,
                            'text' => 'Здравствуйте, ' . PHPShopString::utf8_win1251($this->user($chatId))
                        );
                        $this->dialog($insert);
                        $this->send($chatId, PHPShopString::win_utf8($insert['text']));
                    }
                }
                // Подписка на новые заказы
                elseif (strpos($message['object']['body'], '/chatid') !== false) {
                    $this->send($chatId, $chatId);
                }

                // Продолжение чата
                else {
                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['dialog']);
                    $PHPShopOrm->debug = false;
                    $data = $PHPShopOrm->getOne(array('*'), array('chat_id' => '="' . intval($chatId) . '"'));
                    $chat_id = $data['chat_id'];
                    $message['user_id'] = $data['user_id'];
                    $message['staffid'] = 1;
                    $message['isview'] = 0;
                    $message['isview_user'] = 1;
                    $message['chat']['first_name'] = $this->user($chatId);
                    $message['date'] = time();
                    $message['chat']['id'] = $chatId;
                    $message['text'] = $message['object']['body'];

                    if (!empty($chat_id)) {
                        $this->dialog($message);
                        $this->notice($message, $this->bot);
                    }
                }
            }
        }
    }

    public function dialog($message) {

        // Картинка
        if (is_array($message['object']['attachments'][0]['photo'])) {
            $file = $message['object']['attachments'][0]['photo']['photo_604'];

            if (empty($message['object']['body']))
                $message['object']['body'] = "Картинка";

            $message['text'] = $message['object']['body'];
            $message['attachments'] = $file;
        }

        // Файл
        elseif (is_array($message['object']['attachments'][0]['doc'])) {
            $file = $message['object']['attachments'][0]['doc']['url'];

            if (empty($message['object']['body']))
                $message['object']['body'] = $message['object']['attachments'][0]['doc']['title'];

            $message['text'] = $message['object']['body'];
            $message['attachments'] = $file;
        }

        $insert = array(
            'user_id' => $message['user_id'],
            'name' => PHPShopString::utf8_win1251($message['chat']['first_name']),
            'message' => PHPShopString::utf8_win1251(strip_tags($message['text'])),
            'chat_id' => $message['chat']['id'],
            'time' => $message['date'],
            'staffid' => $message['staffid'],
            'bot' => $this->bot,
            'attachments' => $message['attachments'],
            'isview' => $message['isview'],
            'isview_user' => $message['isview_user'],
            'order_id' => $message['order_id']
        );

        $this->PHPShopOrm->insert($insert, '');
    }

    // Отправка файла
    public function send_file($id, $message, $file) {

        $uploadServer = $this->request('docs.getMessagesUploadServer', array('type' => 'doc', 'peer_id' => $id));
        $upload_url = $uploadServer['response']['upload_url'];

        $data = array(
            'file' => new CURLfile($_SERVER['DOCUMENT_ROOT'] . $file)
        );

        // Проблема отправки файла в VK
        $upload_array = $this->request(null, $data, $upload_url);

        $upload_result = $this->request('docs.save', $upload_array);

        if (is_array($upload_result['response'])) {
            $doc = $upload_result['response'];
            $attachments = 'doc' . $doc['owner_id'] . '_' . $doc['id'];
        } else
            $message .= ' https://' . $_SERVER['SERVER_NAME'] . $file;

        $data = array(
            'peer_id' => $id,
            'message' => $message,
            'attachment' => $attachments
        );

        $out = $this->request('messages.send', $data);
        return $out;
    }

    // Отправка картинки
    public function send_image($id, $message, $file) {

        $uploadServer = $this->request('photos.getMessagesUploadServer');
        $upload_url = $uploadServer['response']['upload_url'];

        $data = array(
            'photo' => new CURLfile($_SERVER['DOCUMENT_ROOT'] . $file)
        );

        $upload_array = $this->request(null, $data, $upload_url);

        $upload_result = $this->request('photos.saveMessagesPhoto', $upload_array);
        $photo = array_pop($upload_result['response']);
        $attachments = 'photo' . $photo['owner_id'] . '_' . $photo['id'];

        $data = array(
            'peer_id' => $id,
            'message' => $message,
            'attachment' => $attachments
        );

        $out = $this->request('messages.send', $data);
        return $out;
    }

    // Отправка сообщений
    public function send($id, $message, $keyboard = false) {

        $data = array(
            'peer_id' => $id,
            'message' => $message,
            'keyboard' => json_encode($keyboard),
        );


        $out = $this->request('messages.send', $data);
        return $out;
    }

    private function request($method, $data = array(), $path = 'https://api.vk.com/method/') {
        $curl = curl_init();
        $data['access_token'] = $this->token;
        $data['v'] = $this->version;
        curl_setopt($curl, CURLOPT_URL, $path . $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $out = json_decode(curl_exec($curl), true);
        curl_close($curl);
        return $out;
    }

    // Уведомление администратору
    public function notice_vk($message, $bot = 'vk') {
        $chat_id = $this->PHPShopSystem->getSerilizeParam('admoption.vk_admin');
        $notice = $this->PHPShopSystem->getSerilizeParam('admoption.vk_dialog');

        $link = $this->protocol . $_SERVER['SERVER_NAME'] . '/phpshop/admpanel/admin.php?path=dialog&id=' . $message['user_id'] . '&bot=' . $bot . '&user=' . $message['user_id'];

        if(empty($message['from']['last_name']))
            $message['from']['last_name']=null;

        $buttons[][] = array(
            'action' => array(
                'type' => 'open_link',
                'link' => $link,
                'label' => $message['from']['first_name'] . ' ' . $message['from']['last_name']
            ),
        );


        if (!empty($chat_id) and ! empty($notice))
            $this->send($chat_id, PHPShopString::win_utf8('Сообщение в диалогах: ') . $message['text'], array('buttons' => $buttons, 'one_time' => false, 'inline' => true));
    }

}

/**
 * Библиотека Telegram Bot
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopClass
 */
class PHPShopTelegramBot extends PHPShopBot {

    protected $bot = 'telegram';

    /**
     * Конструктор
     */
    public function __construct() {

        $this->PHPShopSystem = new PHPShopSystem();
        $this->token = $this->PHPShopSystem->getSerilizeParam('admoption.telegram_token');
        $this->enabled = $this->PHPShopSystem->getSerilizeParam('admoption.telegram_enabled');
        if ($this->token == '')
            $this->enabled = 0;

        $this->PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['dialog']);
    }

    public function dialog($message) {

        // Картинка
        if (is_array($message['photo'])) {
            $file = $message['photo'][count($message['photo']) - 1]['file_id'];

            if (empty($message['caption']))
                $message['caption'] = "Картинка";

            $message['text'] = $message['caption'];
            $message['attachments'] = $this->file($file);
        }

        // Файл
        else if (!empty($message['document'])) {
            $file = $message['document']['file_id'];

            if (empty($message['caption']))
                $message['caption'] = "Файл";

            $message['text'] = $message['caption'];
            $message['attachments'] = $this->file($file);
        }

        $insert = array(
            'user_id' => $message['user_id'],
            'name' => PHPShopString::utf8_win1251($message['chat']['first_name'] . ' ' . $message['chat']['last_name']),
            'message' => PHPShopString::utf8_win1251(strip_tags($message['text'])),
            'chat_id' => $message['chat']['id'],
            'time' => $message['date'],
            'staffid' => $message['staffid'],
            'bot' => $this->bot,
            'attachments' => $message['attachments'],
            'isview' => $message['isview'],
            'isview_user' => $message['isview_user'],
            'order_id' => $message['order_id']
        );

        $this->PHPShopOrm->insert($insert, '');
    }

    // Уведомление администратору
    public function notice_telegram($message, $bot = 'telegram') {
        $chat_id = $this->PHPShopSystem->getSerilizeParam('admoption.telegram_admin');

        $link = '(' . $this->protocol . $_SERVER['SERVER_NAME'] . '/phpshop/admpanel/admin.php?path=dialog&id=' . $message['user_id'] . '&bot=' . $bot . '&user=' . $message['user_id'] . '): ';

        if (!empty($chat_id))
            $this->send($chat_id, PHPShopString::win_utf8('Сообщение в диалогах от') . ' [' . $message['from']['first_name'] . ' ' . $message['from']['last_name'] . ']' . $link . $message['text']);
    }

    public function init($message) {

        if ($this->enabled == 1) {

            $text = $message['text'];

            // Новый чат
            if (strpos($text, '/start') !== false) {

                $textStrings = explode(' ', $text);

                if (isset($textStrings[1])) {
                    $token = $textStrings[1];
                    $chatId = $message['chat']['id'];

                    if ($token) {
                        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
                        $PHPShopOrm->debug = false;
                        $data = $PHPShopOrm->getOne(array('id'), array('bot' => '="' . $token . '"'));
                        $user = $data['id'];

                        if ($user !== null) {

                            $insert = array(
                                'user_id' => $user,
                                'chat' => array
                                    (
                                    'id' => $message['chat']['id'],
                                    'first_name' => "Администрация",
                                    'last_name' => "",
                                ),
                                'date' => time(),
                                'staffid' => 0,
                                'isview' => 1,
                                'isview_user' => 0,
                                'text' => 'Здравствуйте, ' . PHPShopString::utf8_win1251($message['from']['first_name']) . ' ' . PHPShopString::utf8_win1251($message['from']['last_name'])
                            );

                            $this->dialog($insert);
                            $this->send($chatId, PHPShopString::win_utf8($insert['text']));
                        }
                    }
                }
            }
            // Подписка на новые заказы
            elseif (strpos($text, '/chatid') !== false) {
                $this->send($message['chat']['id'], $message['chat']['id']);
            }
            // Продолжение чата
            elseif (!empty($message['chat']['id'])) {
                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['dialog']);
                $PHPShopOrm->debug = false;
                $data = $PHPShopOrm->getOne(array('*'), array('chat_id' => '="' . intval($message['chat']['id']) . '"'));
                $chat_id = $data['chat_id'];
                $message['user_id'] = $data['user_id'];
                $message['staffid'] = 1;
                $message['isview'] = 0;
                $message['isview_user'] = 1;

                if (!empty($chat_id)) {
                    $this->dialog($message);
                    $this->notice($message, $this->bot);
                }
            }
        }
    }

    // Отправка сообщений
    public function send($id, $message) {
        $data = array(
            'chat_id' => $id,
            'text' => $message,
            'parse_mode' => "markdown"
        );

        $out = $this->request('sendMessage', $data);
        return $out;
    }

    // Отправка картинки
    public function send_image($id, $message, $file) {
        $data = array(
            'chat_id' => $id,
            'caption' => $message,
            'photo' => curl_file_create($_SERVER['DOCUMENT_ROOT'] . $file)
        );

        $out = $this->request('sendPhoto', $data);
        return $out;
    }

    // Отправка файла
    public function send_file($id, $message, $file) {
        $data = array(
            'chat_id' => $id,
            'caption' => $message,
            'document' => curl_file_create($_SERVER['DOCUMENT_ROOT'] . $file)
        );

        $out = $this->request('sendDocument', $data);
        return $out;
    }

    private function request($method, $data = array()) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.telegram.org/bot' . $this->token . '/' . $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $out = json_decode(curl_exec($curl), true);
        curl_close($curl);
        return $out;
    }

    public function file($file_id) {
        $array = $this->request("getFile", ['file_id' => $file_id]);
        return 'https://api.telegram.org/file/bot' . $this->token . '/' . $array['result']['file_path'];
    }

}

?>