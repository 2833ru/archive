<?php
/**
 * Элемент вывода двойника каталога
 */
class AddToTemplateCatalogElement extends PHPShopShopCatalogElement {
    var $debug=false;

    function AddToTemplateCatalogElement() {
        parent::PHPShopShopCatalogElement();
    }
    
    function index(){
        return parent::leftCatal();
    }
    
    function subcatalog($n) {

        $dis = null;

        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->cache_format = $this->cache_format;
        $PHPShopOrm->cache = $this->cache;
        $PHPShopOrm->debug = $this->debug;

        $where['parent_to'] = '=' . $n;

        $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'num'), array('limit' => 100), __CLASS__, __FUNCTION__);

        if (is_array($data))
            foreach ($data as $row) {

                // Определяем переменные
                $this->set('catalogName', $row['name']);
                $this->set('catalogUid', $row['id']);
                $this->set('catalogTitle', $row['name']);

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $row);

                // Подключаем новый персональный шаблон podcatalog_forma_2.tpl
                $dis.=ParseTemplateReturn($this->getValue('templates.podcatalog_forma_2'));
            }
        return $dis;
    }

}


// Добавляем в шаблон элемент второго каталога
$AddToTemplateCatalogElement = new AddToTemplateCatalogElement();
$AddToTemplateCatalogElement->init('leftCatal2');
?>