<?php
/**
 * Элемент вывода случайного товарав перменную @showcase@
 */
class AddToTemplate extends PHPShopProductElements {
    var $debug=false;

    function AddToTemplate() {
        $this->objBase=$GLOBALS['SysValue']['base']['products'];
        parent::PHPShopProductElements();
    }

    function showcase() {

        // Шаблон ячейки
        $template='main_spec_forma_icon';

        // Количество ячеек для вывода товара
        $cell=2;

        // Кол-во товаров на странице
        $limit=2;

        // Случаные товары
        $where['id']=$this->setramdom($limit);
        $where['spec']="='1'";
        $where['enabled']="='1'";

        $this->dataArray=$this->select(array('*'),$where,array('order'=>'RAND()'),array('limit'=>$limit));

        // Добавляем в дизайн ячейки с товарами
        $this->product_grid($this->dataArray,$cell,$template,$line=false);

        // Собираем и возвращаем таблицу с товарами
        $this->set('showcase',$this->compile());
    }
}


// Добавляем в шаблон элемент вывода случайного товара в индекс
$AddToTemplate = new AddToTemplate();
$AddToTemplate->showcase();
?>