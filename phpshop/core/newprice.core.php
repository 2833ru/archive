<?php

/**
 * Обработчик распродаж
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopNewprice
 * @version 1.2
 * @package PHPShopShopCore
 */
class PHPShopNewprice extends PHPShopShopCore {

    var $debug = false;
    var $cache = true;
    var $cache_format = array('content', 'yml_bid_array');

    /**
     * Сетка товаров
     * @var int 
     */
    var $cell;

    /**
     * Конструктор
     */
    function PHPShopNewprice() {

        parent::PHPShopShopCore();
        $this->PHPShopOrm->cache_format = $this->cache_format;
    }

    /**
     * Вывод списка товаров
     */
    function index() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // Путь для навигации
        $this->objPath = './newprice_';

        // Валюта
        $this->set('productValutaName', $this->currency());

        $this->set('catalogCategory', $this->lang('newprice'));

        // Количество ячеек
        if (empty($this->cell))
            $this->cell = $this->calculateCell("newprice", $this->PHPShopSystem->getValue('num_vitrina'));

        // Фильтр сортировки
        $order = $this->query_filter("price_n!=''");

        // Кол-во товаров на странице
        // если 0 делаем по формуле 6 минус кол-во колонок * кол-во колонок.
        if (!$this->num_row)
            $this->num_row = (6 - $this->cell) * $this->cell;

        // Простой запрос
        if (is_array($order)) {
            $this->dataArray = parent::getListInfoItem(array('*'), array('price_n' => "!=''", 'enabled' => "='1'"), $order, __CLASS__, __FUNCTION__);
        } else {
            // Сложный запрос
            $this->PHPShopOrm->sql = $order;
            $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
            $this->dataArray = $this->PHPShopOrm->select();
            $this->PHPShopOrm->clean();
        }


        // Пагинатор
        if (is_array($order))
            $this->setPaginator(count($this->dataArray));

        // Добавляем в дизайн ячейки с товарами
        $grid = $this->product_grid($this->dataArray, $this->cell);
        if (empty($grid))
            $grid = PHPShopText::h2($this->lang('empty_product_list'));
        $this->add($grid, true);

        // Заголовок
        $this->title = $this->lang('newprice') . " - " . $this->PHPShopSystem->getParam('title');

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.product_page_spec_list'));
    }

    /**
     * Генерация SQL запроса со сложными фильтрами и условиями
     * @return mixed
     */
    function query_filter($where = false) {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $where);
        if (!empty($hook))
            return $hook;

        return parent::query_filter($where);
    }

}

?>