<?php

class Avito
{
    public $avitoTypes;
    public $avitoCategories;
    public $options;

    public function __construct()
    {
        $PHPShopOrm = new PHPShopOrm('phpshop_modules_avito_system');

        /**
         * Опции модуля
         */
        $this->options = $PHPShopOrm->select();
    }

    public static function getAvitoCategories($currentCategory = null)
    {
        $orm = new PHPShopOrm('phpshop_modules_avito_categories');

        $categories = $orm->getList(array('*'));

        $result = array(array(__('Не выбрано'), 0, $currentCategory));
        foreach ($categories as $category) {
            $result[] = array($category['name'], $category['id'], $currentCategory);
        }

        return $result;
    }

    public static function getCategoryTypes($category = 1, $currentType = null)
    {
        if(empty($category)) {
            $category = 1;
        }

        $orm = new PHPShopOrm('phpshop_modules_avito_types');
        $types = $orm->getList(array('*'), array('category_id' => '="' . $category . '"'));

        $result = array();
        foreach ($types as $type) {
            $result[] = array($type['name'], $type['id'], $currentType);
        }

        return $result;
    }

    /**
     * Название категории в Авито.
     * @param int $categoryId
     * @return string|null
     */
    public function getCategoryById($categoryId)
    {
        if(!is_array($this->avitoCategories)) {
            $orm = new PHPShopOrm('phpshop_modules_avito_categories');
            $categories = $orm->getList(array('*'));
            foreach ($categories as $category) {
                $this->avitoCategories[$category['id']] = $category['name'];
            }
        }

        if(isset($this->avitoCategories[$categoryId])) {
            return $this->avitoCategories[$categoryId];
        }

        return null;
    }

    /**
     * @param int $typeId
     * @return string|null
     */
    public function getAvitoType($typeId)
    {
        if(!is_array($this->avitoTypes)) {
            $orm = new PHPShopOrm('phpshop_modules_avito_types');
            $types = $orm->getList(array('*'));
            foreach ($types as $type) {
                $this->avitoTypes[$type['id']] = $type['name'];
            }
        }

        if(isset($this->avitoTypes[$typeId])) {
            return $this->avitoTypes[$typeId];
        }

        return null;
    }

    public static function getListingFee($currentListingFee)
    {
        return array (
            array('Package', 'Package', $currentListingFee),
            array('PackageSingle', 'PackageSingle', $currentListingFee),
            array('Single', 'Single', $currentListingFee),
        );
    }

    public static function getAdStatuses($currentStatus)
    {
        return array (
            array(__('Обычное объявление').' (Free)', 'Free', $currentStatus),
            array('Premium', 'Premium', $currentStatus),
            array('VIP', 'VIP', $currentStatus),
            array('PushUp', 'PushUp', $currentStatus),
            array('Highlight', 'Highlight', $currentStatus),
            array('TurboSale', 'TurboSale', $currentStatus),
            array('x2_1', 'x2_1', $currentStatus),
            array('x2_7', 'x2_7', $currentStatus),
            array('x5_1', 'x5_1', $currentStatus),
            array('x5_7', 'x5_7', $currentStatus),
            array('x10_1', 'x10_1', $currentStatus),
            array('x10_7', 'x10_7', $currentStatus)
        );
    }

    public static function getConditions($currentCondition)
    {
        return array(
            array('Новый товар', 'Новое', $currentCondition),
            array('Подержанный', 'Б/у', $currentCondition)
        );
    }
}