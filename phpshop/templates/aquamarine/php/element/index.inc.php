<?php

/**
 * Элемент вывода случайного товарав перменную @showcase@
 */
class AddToTemplate extends PHPShopProductElements {

    var $debug = false;

    function AddToTemplate() {
        $this->objBase = $GLOBALS['SysValue']['base']['products'];
        parent::PHPShopProductElements();
    }

    function randMultibase() {

        $multi_cat = null;

        // Мультибаза
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {

            $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
            $where['parent_to'] = " > 0";
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->cache = true;
            $data = $PHPShopOrm->select(array('id'), $where, false, array('limit' => 1), __CLASS__, __FUNCTION__);
            if (is_array($data)) {
                foreach ($data as $row) {
                    $multi_cat = '=' . $row['id'];
                }
            }

            return $multi_cat;
        }
    }

    function showcase() {

        // Проверка на индекс
        if ($this->PHPShopNav->index()) {

            // Шаблон ячейки
            $template = 'product_showcase';
            $this->SysValue['templates']['product_showcase'] = 'element/' . $template . '.tpl';

            // Количество ячеек для вывода товара
            $cell = 1;

            // Кол-во товаров на странице
            $limit = 1;

            // Случаные товары
            //$where['id']=$this->setramdom($limit);
            $where['spec'] = "='1'";
            $where['enabled'] = "='1'";

            $randMultibase = $this->randMultibase();
            if (!empty($randMultibase))
                $where['category'] = $randMultibase;

            $this->dataArray[] = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $limit));

            // Добавляем в дизайн ячейки с товарами
            $this->product_grid($this->dataArray, $cell, $template, $line = false);

            // Собираем и возвращаем таблицу с товарами
            $this->set('showcase', $this->compile());
        }
    }

}

// Добавляем в шаблон элемент вывода случайного товара в индекс
$AddToTemplate = new AddToTemplate();
$AddToTemplate->showcase();
?>