<?php

class PHPShopProductListElement extends PHPShopElements {

    function __construct() {
        $this->debug = false;
        $this->objBase = $GLOBALS['SysValue']['base']['productlist']['productlist_system'];
        $this->option();
        parent::__construct();
    }

    function option() {
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $this->data = $PHPShopOrm->select();
        if ($this->data['num'] < 1)
            $this->data['num'] = 1;
    }

    // Âûâîä
    function element($category) {
        $dis = null;
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('*'), array('category' => '=' . intval($category), 'enabled' => "='1'", 'parent_enabled' => "='0'"), array('order' => 'datas'), array('limit' => $this->data['num']));
        if (is_array($data)) {
            foreach ($data as $row) {

                $this->set('productlist_product_id', $row['id']);
                $this->set('productlist_product_name', $row['name']);
                $this->set('productlist_product_pic_small', $row['pic_small']);
                $this->set('productlist_product_pic_big', $row['pic_big']);
                $this->set('productlist_product_price', $row['price']);
                $this->set('productlist_product_description', $row['description']);

                // Ó÷åò ìîäóëÿ SEOURL
                if (!empty($GLOBALS['SysValue']['base']['seourl']['seourl_system'])) {
                    $this->set('productlist_product_seo', '_' . PHPShopString::toLatin($row['name']));
                }
                // Ó÷åò ìîäóëÿ SEOURLPRO
                elseif (!empty($GLOBALS['SysValue']['base']['seourlpro']['seourlpro_system'])) {
                    $GLOBALS['PHPShopSeoPro']->setMemory($row['id'], $row['name'], 2);
                    PHPShopParser::set('productlastview_product_seo', null);
                }
                else
                    $this->set('productlist_product_seo', null);

                $dis.= PHPShopParser::file($GLOBALS['SysValue']['templates']['productlist']['productlist_product'], true, false, true);
            }


            // Íàçíà÷àåì ïåğåìåííóş øàáëîíà
            switch ($this->data['enabled']) {

                case 1:
                    $this->set('leftMenuName', $this->data['title']);
                    $this->set('leftMenuContent', $dis);
                    $this->set('leftMenu', parseTemplateReturn("main/left_menu.tpl"), true);
                    break;

                case 2:
                    $this->set('rightMenuName', $this->data['title']);
                    $this->set('rightMenuContent', $dis);
                    $this->set('rightMenu', parseTemplateReturn("main/left_menu.tpl"), true);
                    break;

                default: $this->set('productlist', $dis);
            }
        }
    }

}

function uid_productlist_hook($obj, $row, $rout) {
    if ($rout == 'MIDDLE') {

        $PHPShopProductListElement = new PHPShopProductListElement();
        $PHPShopProductListElement->element($row['category']);
    }
}

$addHandler = array
    (
    'UID' => 'uid_productlist_hook'
);
?>