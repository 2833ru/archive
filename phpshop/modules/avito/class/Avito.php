<?php

class Avito
{
    public $avitoTypes;
    public $avitoSubTypes;
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

    public static function getAvitoCategories($xmlPriceId = null, $currentCategory = null)
    {
        $orm = new PHPShopOrm('phpshop_modules_avito_categories');

        $categories = [];
        if((int) $currentCategory > 0) {
            $category = $orm->getOne(['xml_price_id'], ['id' => sprintf('="%s"', $currentCategory)]);
            $xmlPriceId = $category['xml_price_id'];
        }

        if((int) $xmlPriceId > 0) {
            $categories = $orm->getList(['*'], ['xml_price_id' => '="' . (int) $xmlPriceId . '"']);
        }

        $result = [['Не выбрано', 0, $currentCategory]];

        foreach ($categories as $category) {
            $result[] = [$category['name'], $category['id'], $currentCategory];
        }

        return $result;
    }

    public static function getCategoryTypes($category = null, $currentType = null)
    {
        $orm = new PHPShopOrm('phpshop_modules_avito_types');

        $types = [];
        if((int) $category > 0) {
            $types = $orm->getList(['*'], ['category_id' => '="' . $category . '"']);
        }

        $result = [['Не выбрано', 0, $currentType]];
        foreach ($types as $type) {
            $result[] = [$type['name'], $type['id'], $currentType];
        }

        return $result;
    }

    public static function getCategorySubTypes($currentSubType = null)
    {
        $orm = new PHPShopOrm('phpshop_modules_avito_subtypes');

        $result = [['Не выбрано', 0, $currentSubType]];
        foreach ($orm->getList() as $subtype) {
            $result[] = [$subtype['name'], $subtype['id'], $currentSubType];
        }

        return $result;
    }

    public static function getAvitoCategoryTypes($currentCategory)
    {
        $orm = new PHPShopOrm('phpshop_modules_avito_categories');
        $xmlOrm = new PHPShopOrm('phpshop_modules_avito_xml_prices');

        $category = $orm->getOne(['xml_price_id'], ['id' => sprintf('="%s"', $currentCategory)]);
        $xmlPrices = $xmlOrm->getList();

        $result = [[__('Не выбрано'), 0, $currentCategory]];
        foreach ($xmlPrices as $xmlPrice) {
            $result[] = [$xmlPrice['name'], $xmlPrice['id'], $category['xml_price_id']];
        }

        return $result;
    }

    public static function getAdTypes($currentAdType)
    {
        return [
            [__('Товар приобретен на продажу'), 'Товар приобретен на продажу', $currentAdType],
            [__('Товар от производителя'), 'Товар от производителя', $currentAdType]
        ];
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

    public function getAvitoSubType($subTypeId)
    {
        if(!is_array($this->avitoSubTypes)) {
            $orm = new PHPShopOrm('phpshop_modules_avito_subtypes');
            $subTypes = $orm->getList();
            foreach ($subTypes as $subType) {
                $this->avitoSubTypes[$subType['id']] = $subType['name'];
            }
        }

        if(isset($this->avitoSubTypes[$subTypeId])) {
            return $this->avitoSubTypes[$subTypeId];
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