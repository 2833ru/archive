<?php

/**
 * Библиотека работы с Yandex.Market API
 * @author PHPShop Software
 * @version 1.5
 * @package PHPShopClass
 * @subpackage RestApi
 * @todo https://yandex.ru/dev/market/partner-marketplace-cd/doc/dg/reference/post-campaigns-id-offer-mapping-entries-updates.html
 */
class YandexMarket {

    const API_URL = 'https://api.partner.market.yandex.ru/v2/';
    const IMPORT_CONDITION = [
        'yml' => '="1"',
        'enabled' => '="1"',
        'parent_enabled' => '="0"',
        'manufacturer' => ' is not null and trim(manufacturer) != ""',
        'country_of_origin' => ' is not null and trim(country_of_origin) != ""',
        'weight' => ' is not null and trim(weight) != ""',
        'length' => ' is not null and trim(length) != ""',
        'width' => ' is not null and trim(width) != ""',
        'height' => ' is not null and trim(height) != ""'
    ];

    public $options;
    private $image_source = false;

    public function __construct() {
        $this->options = (new PHPShopOrm('phpshop_modules_yandexcart_system'))->select();
        $this->system = new PHPShopSystem();
        $this->image_source = $this->system->ifSerilizeParam('admoption.image_save_source');
        $this->type = $this->options['type'];
    }

    public function getProductsCount() {
        $data = (new PHPShopOrm($GLOBALS['SysValue']['base']['products']))->select(["count('id') as count"], self::IMPORT_CONDITION);

        return (int) $data['count'];
    }

    public function importProducts($from, $imported, $id = false) {
        $limit = 100;
        if (($imported + $limit) >= 5000) {
            $limit = 5000 - $imported;
        }

        $orm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);

        if (empty($id)) {
            $products = $orm->getList(
                    ['*'], self::IMPORT_CONDITION, ['order' => 'id ASC'], ['limit' => $from . ', ' . $limit]
            );
        } else {
            $where = self::IMPORT_CONDITION;
            $where['id'] = '=' . $id;
            $products = $orm->getList(
                    ['*'], $where, ['order' => 'id ASC'], ['limit' => '10']
            );
        }

        if (count($products) === 0) {
            return 0;
        }

        $categories = array_column(
                (new PHPShopOrm($GLOBALS['SysValue']['base']['categories']))
                        ->getList(['id', 'name'], ['id' => sprintf(' IN (%s)', implode(',', array_column($products, 'category')))]), 'name', 'id'
        );

        $modules = array_column((new PHPShopOrm('phpshop_modules'))->getList(['path']), 'path');

        $data = [];

        foreach ($products as $product) {

            $urls = [];
            $pictures = [];

            if (empty($product['market_sku'])) {
                $product['market_sku'] = $this->getMarketSku($product, $categories[$product['category']]);
            }

            // Стандартный урл
            $url = '/shop/UID_' . $product['id'] . '.html';

            // SEOURL
            if (in_array('seourl', $modules))
                $url .= '_' . PHPShopString::toLatin($product['name']) . '.html';

            // SEOURLPRO
            if (in_array('seourlpro', $modules)) {
                if (is_null($GLOBALS['PHPShopSeoPro'])) {
                    include_once dirname(dirname(dirname(__DIR__))) . '/modules/seourlpro/inc/option.inc.php';
                    $GLOBALS['PHPShopSeoPro'] = new PHPShopSeoPro();
                }

                if (empty($product['prod_seo_name']))
                    $url = '/id/' . $GLOBALS['PHPShopSeoPro']->setLatin($product['name']) . '-' . $product['id'] . '.html';
                else
                    $url = '/id/' . $product['prod_seo_name'] . '-' . $product['id'] . '.html';
            }

            $photos = (new PHPShopOrm($GLOBALS['SysValue']['base']['foto']))->getList(['*'], ['parent' => '=' . $product['id']], ['order' => 'num']);

            $urls[] = $this->getFullUrl($url);

            foreach ($photos as $photo) {

                // Исходое изображение
                if (!empty($this->image_source))
                    $photo['name'] = str_replace(".", "_big.", $photo['name']);

                $pictures[] = $this->getFullUrl($photo['name']);
            }
            if (count($pictures) === 0) {
                $pictures[] = $this->getFullUrl($product['pic_big']);
            }


            $options = unserialize($this->options['options']);

            // Блокировка изображений
            if (empty($options['block_image']))
                $pictures = [];

            // Шаблон описания
            if (strstr($this->options['description_template'], '@Description@'))
                $product['content'] = $product['description'];

            // Блокировка описаний
            if (empty($options['block_content']))
                $product['content'] = null;

            // Ключ обновления
            if ($this->type == 2)
                $shopSku = str_replace(['-','_'], [' ','-'], $product['uid']);
            else
                $shopSku = $product['id'];

            $offer = [
                'offer' => [
                    'shopSku' => $shopSku,
                    'name' => $product['name'],
                    'category' => $categories[$product['category']],
                    'manufacturer' => $product['manufacturer'],
                    'manufacturerCountries' => !empty($product['country_of_origin']) ? [$product['country_of_origin']] : null,
                    'urls' => $urls,
                    'pictures' => $pictures,
                    'vendor' => $product['vendor_name'],
                    'vendorCode' => $product['vendor_code'],
                    'barcodes' => !empty($product['barcode']) ? [$product['barcode']] : null,
                    'description' => trim(strip_tags($product['content'], '<p><h3><ul><li><br>')),
                    'weightDimensions' => [
                        'length' => str_replace(',', '.', $product['length']),
                        'width' => str_replace(',', '.', $product['width']),
                        'height' => str_replace(',', '.', $product['height']),
                        'weight' => (float) $product['weight'] / 1000
                    ]
                ]
            ];

            if (!empty($product['market_sku'])) {
                $offer['mapping'] = [
                    'marketSku' => $product['market_sku']
                ];
            }

            $data[] = $offer;
        }

        $method = sprintf('campaigns/%s/offer-mapping-entries/updates.json', trim($this->options['campaign_id']));

        // Отладочный токен
        //$debug='?dbg=4B00000152811A67';
        //print_r($data);

        $result = $this->post($method . $debug, ['offerMappingEntries' => $data]);

        if ($result['status'] === 'ERROR') {
            $errors = [];
            foreach ($result['errors'] as $error) {
                $errors[] = PHPShopString::utf8_win1251($error['message']);
            }

            throw new \Exception(implode('<br>', $errors));
        }

        // Устанавливаем цены, для товаров с marketSku
        $this->importProductsPrice($products);

        return count($products);
    }

    public function getFullUrl($url) {
        if (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }

        if (strpos($url, 'http:') === false && strpos($url, 'https:') === false) {
            $url = $protocol . $_SERVER['SERVER_NAME'] . $url;
        }

        return $url;
    }

    public function getRegionById($id) {
        $region = $this->get('regions/' . $id . '.json');

        if (!isset($region['regions'][0]) or ! is_array($region['regions'][0])) {
            return false;
        }

        return $this->getRegionName($region['regions'][0]);
    }

    public function findRegion($term) {
        $regions = $this->get('regions.json', 'name=' . urlencode(PHPShopString::win_utf8($term)));
        $result = array();
        if (!is_array($regions['regions']) or count($regions['regions']) === 0) {
            return $result;
        }

        foreach ($regions['regions'] as $region) {
            $result[] = array(
                'label' => implode(', ', $this->getRegionName($region)),
                'value' => $region['id']
            );
        }

        return $result;
    }

    public function changeStatus($orderId, $status) {
        $this->put('campaigns/' . trim($this->options['campaign_id']) . '/orders/' . $orderId . '/status.json', $status);
    }

    public function getOutlets($regionId = 0) {
        $parameters = [];
        if ((int) $regionId > 0) {
            $parameters['region_id'] = (int) $regionId;
        }

        $outlets = $this->get('campaigns/' . trim($this->options['campaign_id']) . '/outlets.json', $parameters);

        if (isset($outlets['outlets']) && is_array($outlets['outlets'])) {
            return $outlets['outlets'];
        }

        return [];
    }

    public function getOutletsSelectOptions($regionId = 0, $current = null) {
        $current = unserialize($current);
        if (!is_array($current)) {
            $current = [];
        }

        $outlets = $this->getOutlets($regionId);

        $result = [];
        foreach ($outlets as $outlet) {
            $result[] = [
                PHPShopString::utf8_win1251($outlet['name']), $outlet['shopOutletCode'], in_array($outlet['shopOutletCode'], $current) ? $outlet['shopOutletCode'] : null
            ];
        }

        return $result;
    }

    private function getRegionName($region, $names = array()) {
        $names[] = $region['name'];
        if (isset($region['parent']) && is_array($region['parent'])) {
            $names = $this->getRegionName($region['parent'], $names);
        }

        return $names;
    }

    private function getMarketSku($product, $categoryTitle) {
        $method = sprintf('campaigns/%s/offer-mapping-entries/suggestions.json', trim($this->options['campaign_id']));

        // Ключ обновления
        if ($this->type == 2)
            $shopSku = $product['uid'];
        else
            $shopSku = $product['id'];

        $parameters = [
            'offers' => [
                [
                    'offer' => [
                        'shopSku' => $shopSku,
                        'name' => $product['name'],
                        'category' => $categoryTitle,
                        'vendor' => $product['vendor_name'],
                        'vendorCode' => $product['vendor_code'],
                        'barcodes' => !empty($product['barcode']) ? [$product['barcode']] : null,
                        'price' => $this->getPrice($product)
                    ]
                ]
            ]
        ];

        $result = $this->post($method, $parameters);

        if (isset($result['result']['offers'][0]['shopSku'])) {
            return $result['result']['offers'][0]['shopSku'];
        }

        return null;
    }

    private function getPrice($product) {
        global $PHPShopValutaArray;

        $promotions = new PHPShopPromotions();
        $options = unserialize($this->options['options']);

        // Колонка цен
        if (isset($options['price']) && (int) $options['price'] > 1)
            $column = 'price' . $options['price'];
        else
            $column = $this->system->getPriceColumn();

        $defaultCurrency = $this->system->getValue('dengi');
        $format = $this->system->getSerilizeParam('admoption.price_znak');

        // Наценка %
        if (isset($options['price_fee']) && (float) $options['price_fee'] > 0)
            $fee = (float) $options['price_fee'];
        else
            $fee = $this->system->getValue('percent');

        $PHPShopValutaArr = $PHPShopValutaArray->getArray();

        $price = $product[$column];

        // Промоакции
        $promotions = $promotions->getPrice($product);
        if (is_array($promotions)) {
            $price = $promotions['price'];
        }

        $currency = $product['baseinputvaluta'];

        //Если валюта отличается от базовой
        if ($currency !== $defaultCurrency) {
            $vkurs = $PHPShopValutaArr[$currency]['kurs'];

            // Если курс нулевой или валюта удалена
            if (empty($vkurs))
                $vkurs = 1;

            // Приводим цену в базовую валюту
            $price = $price / $vkurs;
        }

        // Наценка руб.
        $price = $price + (int) $options['price_markup'];

        // Наценка %
        $price = ($price + (($price * $fee) / 100));

        $price = round($price, (int) $format);

        return $price;
    }

    private function importProductsPrice($products) {
        $products = array_column($products, null, 'id');

        $imported = $this->getProductsInMarketByShopSku(array_keys($products));

        $offers = [];
        foreach ($imported as $prod) {
            if (isset($prod['mapping']['marketSku'])) {
                $offers[] = [
                    'marketSku' => $prod['mapping']['marketSku'],
                    'price' => [
                        'currencyId' => 'RUR',
                        'value' => $this->getPrice($products[$prod['offer']['shopSku']])
                    ]
                ];
            }
        }

        if (count($offers) === 0) {
            return;
        }

        $method = sprintf('campaigns/%s/offer-prices/updates.json', trim($this->options['campaign_id']));

        $this->post($method, ['offers' => $offers]);
    }

    private function getProductsInMarketByShopSku($shopSkus) {
        $method = sprintf('campaigns/%s/offer-mapping-entries.json', trim($this->options['campaign_id']));

        $skus = '';
        foreach ($shopSkus as $key => $shopSku) {
            $skus .= 'shop_sku=' . urlencode($shopSku) . '&';
        }

        $result = $this->get($method, $skus);

        if ($result['status'] === 'ERROR') {
            $errors = [];
            foreach ($result['errors'] as $error) {
                $errors[] = PHPShopString::utf8_win1251($error['message']);
            }

            throw new \Exception(implode('<br>', $errors));
        }

        return $result['result']['offerMappingEntries'];
    }

    private function get($method, $parameters = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . $method . '?' . $parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            sprintf('Authorization: OAuth oauth_token="%s", oauth_client_id="%s"', $this->options['client_token'], $this->options['client_id'])
        ]);
        $result = json_decode(curl_exec($ch), 1);
        curl_close($ch);

        return $result;
    }

    private function put($method, $parameters = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            sprintf('Authorization: OAuth oauth_token="%s", oauth_client_id="%s"', $this->options['client_token'], $this->options['client_id']),
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($parameters))
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));

        $result = curl_exec($ch);
        $status = curl_getinfo($ch);

        curl_close($ch);

        return json_decode($result, true);
    }

    private function post($method, $parameters = []) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            sprintf('Authorization: OAuth oauth_token="%s", oauth_client_id="%s"', $this->options['client_token'], $this->options['client_id']),
            'Content-Type: application/json',
            'Content-Length: ' . strlen(PHPShopString::json_safe_encode($parameters))
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, PHPShopString::json_safe_encode($parameters));

        $result = curl_exec($ch);
        $status = curl_getinfo($ch);

        if ($status['http_code'] === 401) {
            throw new \Exception('Доступ запрещен. Пожалуйста, проверьте введенный ID приложения Яндекс.OAuth и OAuth-токен.');
        }

        if ($status['http_code'] === 413) {
            throw new \Exception('Request Entity Too Large.');
        }

        curl_close($ch);

        return json_decode($result, true);
    }

}
