<?php

include_once dirname(__DIR__) . '/class/include.php';

class ElasticClient
{
    const API_URL = 'https://elastica.host/api/';
    const PROXY_API_URL = 'https://estrellas.dev/api/';

    public function getClientInfo()
    {
        $result = $this->post('client/status');

        if($result['success'] === false) {
            throw new \Exception(PHPShopString::utf8_win1251($result['message']));
        }

        $result['data']['domain'] = iconv('UTF-8', 'Windows-1251', $result['data']['domain']);
        $result['data']['block_reason'] = iconv('UTF-8', 'Windows-1251', $result['data']['block_reason']);
        $result['data']['tariff']['title'] = iconv('UTF-8', 'Windows-1251', $result['data']['tariff']['title']);
        $result['data']['tariff']['country']['title'] = iconv('UTF-8', 'Windows-1251', $result['data']['tariff']['country']['title']);
        $result['data']['tariff']['country']['currency']['title'] = iconv('UTF-8', 'Windows-1251', $result['data']['tariff']['country']['currency']['title']);
        $result['data']['tariff']['country']['currency']['short_title'] = iconv('UTF-8', 'Windows-1251', $result['data']['tariff']['country']['currency']['short_title']);

        return $result;
    }

    public function updateProduct($id, $product)
    {
        $result = $this->post('products/update', ['data' => [
            'product' => $product,
            'id'      => $id
        ]]);

        if($result['success'] === false) {
            throw new \Exception(PHPShopString::utf8_win1251($result['message']));
        }
    }

    public function deleteProduct($id)
    {
        $result = $this->post('products/delete', ['data' => ['id' => $id]]);

        if($result['success'] === false) {
            throw new \Exception(PHPShopString::utf8_win1251($result['message']));
        }
    }

    public function updateCategory($id, $category)
    {
        $result = $this->post('categories/update', ['data' => [
            'category' => $category,
            'id'       => $id
        ]]);

        if($result['success'] === false) {
            throw new \Exception(PHPShopString::utf8_win1251($result['message']));
        }
    }

    public function deleteCategory($id)
    {
        $result = $this->post('categories/delete', ['data' => ['id' => $id]]);

        if($result['success'] === false) {
            throw new \Exception(PHPShopString::utf8_win1251($result['message']));
        }
    }

    public function createProductsIndex()
    {
        $synonyms = Elastic::getOption('synonyms');
        $synonyms = trim($synonyms);

        if (strpos($synonyms, "\r\n")) {
            $eol = "\r\n";
        } elseif (strpos($synonyms, "\n")) {
            $eol = "\n";
        } else {
            $eol = "\r";
        }

        $result = $this->post('index/create', ['data' => [
            'index'    => '_products',
            'synonyms' => explode($eol, $synonyms)
        ]]);

        if($result['success'] === false) {
            throw new \Exception(PHPShopString::utf8_win1251($result['message']));
        }
    }

    public function createCategoriesIndex()
    {
        $synonyms = Elastic::getOption('synonyms');
        $synonyms = trim($synonyms);

        if (strpos($synonyms, "\r\n")) {
            $eol = "\r\n";
        } elseif (strpos($synonyms, "\n")) {
            $eol = "\n";
        } else {
            $eol = "\r";
        }

        $result = $this->post('index/create', ['data' => [
            'index'    => '_categories',
            'synonyms' => explode($eol, $synonyms)
        ]]);

        if($result['success'] === false) {
            throw new \Exception(PHPShopString::utf8_win1251($result['message']));
        }
    }

    public function removeProductsIndex()
    {
        $result = $this->post('index/remove', ['data' => [
            'index' => '_products'
        ]]);

        if($result['success'] === false) {
            throw new \Exception(PHPShopString::utf8_win1251($result['message']));
        }
    }

    public function removeCategoriesIndex()
    {
        $result = $this->post('index/remove', ['data' => [
            'index' => '_categories'
        ]]);

        if($result['success'] === false) {
            throw new \Exception(PHPShopString::utf8_win1251($result['message']));
        }
    }

    public function importProducts($products)
    {
        $result = $this->post('products/import', ['data' => $products]);

        if($result['success'] === false) {
            throw new \Exception(PHPShopString::utf8_win1251($result['message']));
        }
    }

    public function importCategories($categories)
    {
        $result = $this->post('categories/import', ['data' => $categories]);

        if($result['success'] === false) {
            throw new \Exception(PHPShopString::utf8_win1251($result['message']));
        }
    }

    public function searchByQuery($query)
    {
        $result = $this->post('products/search', ['data' => $query], 10);

        if(isset($result['success']) && $result['success'] === true) {
            return $result['data'];
        }

        throw new \Exception(PHPShopString::utf8_win1251($result['message']));
    }

    public function searchAllByQuery($query)
    {
        $result = $this->post('all/msearch', ['data' => $query], 10);

        if(isset($result['success']) && $result['success'] === true) {
            return $result['data'];
        }

        throw new \Exception(PHPShopString::utf8_win1251($result['message']));
    }

    private function post($method, $parameters = [], $timeout = 0)
    {
        if(empty(Elastic::getOption('api'))) {
            throw new \Exception('Необходимо ввести API ключ.');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->getApiUrl() . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-AUTH-TOKEN: ' . Elastic::getOption('api'),
            'Content-Type: application/json',
            'Content-Length: ' . strlen(PHPShopString::json_safe_encode($parameters))
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, PHPShopString::json_safe_encode($parameters));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        $result = curl_exec($ch);
        $status = curl_getinfo($ch);

        if($status['http_code'] === 401) {
            throw new \Exception('Доступ запрещен. Пожалуйста, проверьте введенный API ключ.');
        }

        if($status['http_code'] === 413) {
            throw new \Exception('Request Entity Too Large.');
        }

        return json_decode($result, true);
    }

    private function getApiUrl()
    {
        if ((int) Elastic::getOption('use_proxy') === 1) {
            return self::PROXY_API_URL;
        }

        return self::API_URL;
    }
}