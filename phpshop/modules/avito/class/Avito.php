<?php

class Avito
{
    public $avitoTypes;
    public $avitoSubTypes;
    public $avitoCategories;
    private static $options;

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
            $categories = $orm->getList();
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
            $types = $orm->getList();
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

    public static function getTierTypes($currentType = null)
    {
        return [
            ['Не выбрано', '', $currentType],
            ['Всесезонные', 'Всесезонные', $currentType],
            ['Летние', 'Летние', $currentType],
            ['Зимние нешипованные', 'Зимние нешипованные', $currentType],
            ['Зимние шипованные', 'Зимние шипованные', $currentType]
        ];
    }

    public static function getWheelAxle($currentAxle = null)
    {
        return [
            ['Не выбрано', '', $currentAxle],
            ['Задняя', 'Задняя', $currentAxle],
            ['Любая', 'Любая', $currentAxle],
            ['Передняя', 'Передняя', $currentAxle]
        ];
    }

    public static function getRimTypes($currentRimType = null)
    {
        return [
            ['Не выбрано', '', $currentRimType],
            ['Кованые', 'Кованые', $currentRimType],
            ['Литые', 'Литые', $currentRimType],
            ['Штампованные', 'Штампованные', $currentRimType],
            ['Спицованные', 'Спицованные', $currentRimType],
            ['Сборные', 'Сборные', $currentRimType],
        ];
    }

    public static function getTireSectionWidth($currentSectionWidth = null)
    {
        return [
            ['Не выбрано', '', $currentSectionWidth],
            ['2.5', '2.5', $currentSectionWidth],
            ['2.75', '2.75', $currentSectionWidth],
            ['3', '3', $currentSectionWidth],
            ['3.5', '3.5', $currentSectionWidth],
            ['4', '4', $currentSectionWidth],
            ['4.1', '4.1', $currentSectionWidth],
            ['4.5', '4.5', $currentSectionWidth],
            ['4.6', '4.6', $currentSectionWidth],
            ['60', '60', $currentSectionWidth],
            ['70', '70', $currentSectionWidth],
            ['80', '80', $currentSectionWidth],
            ['90', '90', $currentSectionWidth],
            ['100', '100', $currentSectionWidth],
            ['110', '110', $currentSectionWidth],
            ['120', '120', $currentSectionWidth],
            ['130', '130', $currentSectionWidth],
            ['140', '140', $currentSectionWidth],
            ['150', '150', $currentSectionWidth],
            ['160', '160', $currentSectionWidth],
            ['170', '170', $currentSectionWidth],
            ['180', '180', $currentSectionWidth],
            ['190', '190', $currentSectionWidth],
            ['200', '200', $currentSectionWidth],
            ['210', '210', $currentSectionWidth],
            ['220', '220', $currentSectionWidth],
            ['230', '230', $currentSectionWidth],
            ['240', '240', $currentSectionWidth],
            ['250', '250', $currentSectionWidth],
            ['260', '260', $currentSectionWidth],
            ['270', '270', $currentSectionWidth],
            ['280', '280', $currentSectionWidth],
            ['290', '290', $currentSectionWidth],
            ['300', '300', $currentSectionWidth],
            ['310', '310', $currentSectionWidth],
            ['320', '320', $currentSectionWidth],
            ['330', '330', $currentSectionWidth],
            ['340', '340', $currentSectionWidth],
            ['350', '350', $currentSectionWidth],
            ['360', '360', $currentSectionWidth],
            ['370', '370', $currentSectionWidth],
            ['380', '380', $currentSectionWidth],
            ['390', '390', $currentSectionWidth]
        ];
    }

    public static function getTireAspectRatio($currentTireAspectRatio = null)
    {
        return [
            ['Не выбрано', '', $currentTireAspectRatio],
            ['25', '25', $currentTireAspectRatio],
            ['30', '30', $currentTireAspectRatio],
            ['35', '35', $currentTireAspectRatio],
            ['40', '40', $currentTireAspectRatio],
            ['45', '45', $currentTireAspectRatio],
            ['50', '50', $currentTireAspectRatio],
            ['55', '55', $currentTireAspectRatio],
            ['60', '60', $currentTireAspectRatio],
            ['65', '65', $currentTireAspectRatio],
            ['70', '70', $currentTireAspectRatio],
            ['75', '75', $currentTireAspectRatio],
            ['80', '80', $currentTireAspectRatio],
            ['85', '85', $currentTireAspectRatio],
            ['90', '90', $currentTireAspectRatio],
            ['95', '95', $currentTireAspectRatio],
            ['100', '100', $currentTireAspectRatio],
            ['105', '105', $currentTireAspectRatio],
            ['110', '110', $currentTireAspectRatio],
            ['Другое', 'Другое', $currentTireAspectRatio]
        ];
    }

    public static function getConditions($currentCondition)
    {
        return [
            ['Новый товар', 'Новое', $currentCondition],
            ['Подержанный', 'Б/у', $currentCondition]
        ];
    }

    public static function getOption($key)
    {
        if(!is_array(self::$options)) {
            $PHPShopOrm = new PHPShopOrm('phpshop_modules_avito_system');
            self::$options = $PHPShopOrm->select();
        }

        if(isset(self::$options[$key])) {
            return self::$options[$key];
        }

        return null;
    }
}