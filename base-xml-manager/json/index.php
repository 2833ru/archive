<?php

/**
 * Синхронизация данных через JSON
 * @package PHPShopExchange
 * @author PHPShop Software
 * @version 1.1
 */
$_classPath = "../../phpshop/";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("basexml");
PHPShopObj::loadClass("string");

// Подключаем БД
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true, true);

class PHPShopJSON extends PHPShopBaseXml {
    
    public $debug = false;

    function __construct() {
        global $PHPShopBase;

        $this->true_method = array('select', 'update', 'delete', 'insert','mail');
        $this->token = $_SERVER['HTTP_TOKEN'];

        if (empty($this->token))
            $this->error('No token');

        $this->PHPShopBase = $PHPShopBase;

        if ($this->admin()) {

            $this->sql = json_decode(file_get_contents("php://input"), true);
            $this->parser();

            if (in_array($this->xml['method'], $this->true_method)) {
                if (method_exists($this, $this->xml['method'])) {

                    // Проверка прав
                    if ($this->checkRules($this->xml['method']))
                        call_user_func(array($this, $this->xml['method']));
                    else
                        $this->error('No permission');

                    if (!empty($this->error)) {
                        $this->error($this->error);
                    }
                } else
                    $this->error('Non method');
            } else
                $this->error('False method');

            $this->compile();
        } else {
            $this->error('Token not found');
        }
    }

    public function parser() {

        if (is_array($this->sql)) {
            $this->xml['method'] = $this->sql['method'];
            $this->xml['vars'] = array($this->sql['vars']);
            $this->xml['from'] = $this->sql['from'];

            if (!empty($this->sql['where']))
                $this->xml['where'] = $this->parseWhereString($this->sql['where']);
            if (!empty($this->sql['order']))
                $this->xml['order'] = array('order' => $this->sql['order']);
            if (!empty($this->sql['limit']))
                $this->xml['limit'] = array('limit' => $this->sql['limit']);
        } else
            $this->error('Non json');
    }

    public function admin() {
        $PHPShopOrm = new PHPShopOrm($this->PHPShopBase->getParam('base.users'));
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('token,status'), array('enabled' => "='1'"), array('order' => 'id desc'), array('limit' => 100));
        if (is_array($data)) {
            foreach ($data as $v)
                if ($this->token and $this->token == $v['token']) {
                    $this->UserStatus = unserialize($v['status']);
                    return true;
                }
        }
    }

    public function checkRules($do = 'select') {
        $rules_array = array(
            'select' => 0,
            'update' => 1,
            'insert' => 2,
            'delete' => 1,
            'mail' => 1
        );

        $array = explode("-", $this->UserStatus['api']);

        if (!empty($array[$rules_array[$do]]))
            return true;
    }

    public function is_serialize($str) {
        $array = unserialize($str);

        if (is_array($array)) {
            array_walk_recursive($array, 'array2iconvUTF');
            $result = $array;
        } else
            $result = PHPShopString::win_utf8($str);

        return $result;
    }

    public function compile() {

        if ($this->data)
            $result['status'] = 'succes';
        else
            $result['status'] = 'false';

        if (is_array($this->data)) {

            foreach ($this->data as $row) {
                if (is_array($row)) {
                    foreach ($row as $key => $val) {
                        $result['data'][$row['id']][$key] = $this->is_serialize($val);
                    }
                } else {
                    foreach ($this->data as $key => $val) {
                        $result['data'][$key] = $this->is_serialize($val);
                    }
                }
            }
        }

        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function error($text) {
        if (!empty($text)) {
            header("Content-Type: application/json");
            exit(json_encode(array('status' => 'error', 'error' => $text)));
        }
    }
    
    public function mail(){

        PHPShopObj::loadClass(["system","mail"]);
        
        $GLOBALS['PHPShopSystem'] = new PHPShopSystem();
        $mail = $this->xml['vars'][0]['mail'];
        $title= $this->xml['vars'][0]['title'];
        $content= $this->xml['vars'][0]['content'];
        
        if(new PHPShopMail($mail, $GLOBALS['PHPShopSystem']->getParam('adminmail2'), $title, $content)){
           $this->data = $this->xml['vars'];
        }else $this->error('Mail not sent');
        
    }

}

/*
 * Смена кодировки на UTF-8 в массиве
 */
function array2iconvUTF(&$value) {
    $value = iconv("CP1251", "UTF-8", $value);
}

new PHPShopJSON();
?>