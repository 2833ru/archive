<?php

/**
 * Обработчик подбора товаров по характеристикам
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopShopCore
 */
class PHPShopSelection extends PHPShopShopCore {

    /**
     * Отладка
     * @var bool
     */
    var $debug = false;
    /*
     * Кэширование
     */
    var $cache = false;

    /**
     * Лимит вывода товаров
     * @var int
     */
    var $max_item = 100;

    /**
     * Конструктор
     */
    function PHPShopSelection() {

        // Список экшенов
        $this->action = array("get" => "v", 'nav' => 'index');
        parent::PHPShopShopCore();
        $this->PHPShopOrm->cache_format = $this->cache_format;
    }

    function index() {
        $this->setError404();
    }

    /**
     * Вывод списка товаров
     */
    function v() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, null, 'START'))
            return true;

        // Валюта
        $this->set('productValutaName', $this->currency());

        // Количество ячеек
        $cell = $this->PHPShopSystem->getValue('num_vitrina');

        // Фильтр сортировки
        $order = $this->query_filter();

        if (!is_array($order)) {
            $this->PHPShopOrm->sql = $order;
            $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
            $this->dataArray = $this->PHPShopOrm->select();
        }

        // Добавляем в дизайн ячейки с товарами
        $grid = $this->product_grid($this->dataArray, $cell);
        if (empty($grid))
            $grid = PHPShopText::h2($this->lang('empty_product_list'));
        $this->add($grid, true);

        // Заголовок
        $this->title = __('Поиск по производителям') . " - " . $this->PHPShopSystem->getParam('title');

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.product_selection_list'));
    }

}

?>