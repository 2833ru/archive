<?php
$_classPath='../../';
include($_classPath.'phpshop/class/obj.class.php');
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("xml");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("basexml");
PHPShopObj::loadClass("modules");


$_POST['log_test']='root';
$_POST['pas_test']='I4OI1OI1OI6OI4OI1OI1OI6OI10O';

$_POST['sql_test']='<?xml version="1.0" encoding="windows-1251"?>
<phpshop>
<sql>
<from>table_name1</from>
<method>update</method>
<vars>
<statusi>2</statusi>
</vars>
<where>id=121</where>
</sql>
</phpshop>';

// Подключаем БД
$PHPShopBase=new PHPShopBase($_classPath.'phpshop/inc/config.ini');

// Настройки модулей
$PHPShopModules = new PHPShopModules($_classPath."phpshop/modules/");

class PHPShop1C extends PHPShopBaseXml {

    function PHPShop1C() {
        $this->debug=false;
        $this->true_method=array('select','option','insert','update','delete','image','order');
        $this->true_from=array('table_name','table_name1','table_name2','table_name3','table_name24',
                'table_name5', 'table_name6', 'table_name7','table_name8','table_name11',
                'table_name14','table_name15','table_name17','table_name29',
                'table_name50','table_name51');

        parent::PHPShopBaseXml();
    }

    function order() {

        // Массив данных для вставки
        $vars=readDatabase($this->sql,"vars",false);
        $update=false;
        $PHPShopOrm=new PHPShopOrm($this->PHPShopBase->getParam('base.'.$this->xml['from']));
        $PHPShopOrm->debug=$this->debug;
        $order_data=$PHPShopOrm->select(array('orders'),$this->xml['where'],$this->xml['order'],$this->xml['limit']);
        $orders=unserialize($order_data["orders"]);


        if(is_array($orders['Cart']['cart']))
            foreach($orders['Cart']['cart'] as $key=>$product)
                if($product['uid'] == $vars[0]['art']) {

                    $orders['Cart']['cart'][$key]['num']=$vars[0]['item'];
                    $update=true;

                    // Удаление товара при нулевов кол-ве
                    if(empty($orders['Cart']['cart'][$key]['num'])) unset($orders['Cart']['cart'][$key]);
                }

        // Добавление нового товара
        if(empty($update) and !empty($vars[0]['item'])) {

            $PHPShopOrm = new PHPShopOrm($this->PHPShopBase->getParam('base.table_name2'));
            $PHPShopOrm->debug=$this->debug;
            $data=$PHPShopOrm->select(array('*'),array('uid'=>'="'.$vars[0]['art'].'"'),false,array('limit'=>1));
            if(is_array($data)) {
                $orders['Cart']['cart'][$data['id']]=array(
                        'id'=>$data['id'],
                        'name'=>$data['name'],
                        'price'=>$data['price'],
                        'uid'=>$vars[0]['art'],
                        'num'=>$vars[0]['item'],
                        'weight'=>$data['weight'],
                        'user'=>$data['user']);
            }
            else exit("Error Art");
        }


        // Пересчет общих данных корзины
        $num=$sum=$weight=0;
        if(is_array($orders['Cart']['cart'])) {
            foreach($orders['Cart']['cart'] as $product) {
                $num+=$product['num'];
                $sum+=$product['num']*$product['price'];
                $weight+=$product['num']*$product['weight'];
            }
            $orders['Cart']['num']=$num;
            $orders['Cart']['sum']=$sum;
            $orders['Cart']['weight']=$weight;
        }

        $order_data["orders_new"]=serialize($orders);

        // Запись обновленного заказа
        $PHPShopOrm = new PHPShopOrm($this->PHPShopBase->getParam('base.'.$this->xml['from']));
        $PHPShopOrm->debug=$this->debug;
        $this->data=$PHPShopOrm->update($order_data,$this->xml['where']);

    }

    function decode($code) {
        $decode=substr($code,0,strlen($code)-4);
        $decode=str_replace("I",11,$decode);
        $decode=explode("O",$decode);
        $disp_pass="";
        for ($i=0;$i<(count($decode)-1);$i++) $disp_pass.=chr($decode[$i]);
        return base64_encode($disp_pass);
    }


    function admin() {

        $PHPShopOrm=&new PHPShopOrm($this->PHPShopBase->getParam('base.table_name19'));
        $PHPShopOrm->debug = $this->debug;
        $data=$PHPShopOrm->select(array('login,password,status'),array('enabled'=>"='1'"),false,array('limit'=>10));
        if(is_array($data)) {
            foreach($data as $v)
                if($_POST['log'] == $v['login'] and $this->decode($_POST['pas']) == $v['password']) {
                    $this->user_status=unserialize($v['status']);
                    return true;
                }
        }
        return false;
    }

    // Проверка прав
    function status($from,$flag) {
        global $PHPShopModules;
        $path_core=explode("_",$this->PHPShopBase->getParam('base.'.$from));
        $path_mod=explode("_",$PHPShopModules->getParam('base.'.$from));
        $path=array_merge($path_core,$path_mod);

        // Корректировка статусов относительно имен БД
        $correct_path=array(
                'page'=>'page_site',
                'menu'=>'page_menu',
                'baners'=>'baner',
                'categories'=>'cat_prod',
                'modules'=>'cat_prod',
                'products'=>'cat_prod',
                '1c'=>'visitor',
                'orders'=>'visitor',
                'order'=>'visitor',
                'payment'=>'visitor'
        );

        if($correct_path[$path[1]]) $path=$correct_path[$path[1]];
        else $path=$path[1];

        $array=explode("-",$this->user_status[$path]);
        if(!empty($array[$flag])) return true;
        else $this->data[]=array('phpshop_sql_user'=>'deny');
    }

    function update() {

        if($this->status($this->xml['from'],1))
            parent::update();
    }

    function delete() {

        if($this->status($this->xml['from'],1))
            parent::delete();
    }

    function insert() {

        if($this->status($this->xml['from'],2))
            parent::insert();
    }

    function select() {

        if($this->status($this->xml['from'],0))
            parent::select();
    }


    function image() {
        $i=1;
        $type_array=array('gif','jpg','png');
        $dir='/UserFiles/Image/'.$this->xml['vars'][0];
        if ($dh = opendir('../..'.$dir.'/')) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != "..") {

                    if(is_file('../..'.$dir.'/'.$file)) {

                        $type=substr($file,-3);
                        if(in_array($type, $type_array)) {

                            $image[$i]['name']=$file;
                            $image[$i]['type']=$type;
                        }
                    }
                    else {
                        $image[$i]['name']=$file;
                        $image[$i]['type']='dir';
                    }

                    $i++;
                }
            }
            closedir($dh);
        }
        $this->data=$image;
    }

}

$PHPShop1C = new PHPShop1C();
?>