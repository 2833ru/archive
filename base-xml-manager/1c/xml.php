<?php

include("../../phpshop/class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("xml");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("basexml");

// Подключаем БД
$PHPShopBase=&new PHPShopBase("../../phpshop/inc/config.ini");

class PHPShop1C extends PHPShopBaseXml {

    function PHPShop1C() {
        $this->debug=false;
        $this->true_method=array('select','option','insert','update','delete','image');
        $this->true_from=array('table_name','table_name2','table_name3','table_name24',
                'table_name5', 'table_name6', 'table_name7','table_name8','table_name11',
                'table_name14','table_name15','table_name17','table_name29',
                'table_name50','table_name51');

        parent::PHPShopBaseXml();
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
        $path=explode("_",$this->PHPShopBase->getParam('base.'.$from));

        // Корректировка статусов относительно имен БД
        $correct_path=array(
                'page'=>'page_site',
                'menu'=>'page_menu',
                'baners'=>'baner',
                'categories'=>'cat_prod'
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