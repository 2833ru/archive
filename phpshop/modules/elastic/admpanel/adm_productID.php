<?php

include_once dirname(__DIR__) . '/class/include.php';

function elasticBeforeDeleteProduct($product)
{
    $Elastic = new Elastic();

    try {
        $productObj = new PHPShopProduct((int) $product['rowID']);
        $Elastic->client->deleteProduct($productObj->getParam('elastic_id'));
    } catch (\Exception $exception) {
        // var_dump($exception->getMessage()); exit; for debug
    }
}

function elasticBeforeUpdateProduct($product)
{
    $oldProduct = new PHPShopProduct((int) $product['rowID']);
    $Elastic = new Elastic();

    $description = trim(strip_tags($product['description_new']));
    if(empty($description)) {
        $description = trim(strip_tags($oldProduct->getParam('description')));
    }

    $content = trim(strip_tags($product['content_new']));
    if(empty($content)) {
        $content = trim(strip_tags($oldProduct->getParam('content')));
    }

    if(empty($product['dop_cat_new'])) {
        $product['dop_cat_new'] = $oldProduct->getParam('dop_cat');
    }
    $categories = explode('#', $product['dop_cat_new']);
    if(!is_array($categories)) {
        $categories = [];
    }
    if(empty($product['category_new'])) {
        $product['category_new'] = $oldProduct->getParam('category');
    }
    $categories[] = $product['category_new'];
    $categories = array_diff($categories, ['']);

    if(empty($product['vendor_array_new'])) {
        $product['vendor_array_new'] = $oldProduct->getParam('vendor_array');
    }

    $attributes = unserialize($product['vendor_array_new']);
    if(!is_array($attributes)) {
        $attributes = [];
    }
    $atts = [];
    foreach ($attributes as $attributeId => $values) {
        $atts[] = [
            'id'     => (int) $attributeId,
            'values' => array_diff(array_diff(array_unique(array_map(function ($value) {
                return (int) $value;
            }, $values)), ['']), [0])
        ];
    }

    if(empty($product['pic_big_new'])) {
        $product['pic_big_new'] = $oldProduct->getParam('pic_big');
    }
    if(empty($product['pic_small_new'])) {
        $product['pic_small_new'] = $oldProduct->getParam('pic_small');
    }
    if(empty($product['barcode_new'])) {
        $product['barcode_new'] = $oldProduct->getParam('barcode');
    }
    if(empty($product['vendor_name_new'])) {
        $product['vendor_name_new'] = $oldProduct->getParam('vendor_name');
    }
    if(empty($product['vendor_code_new'])) {
        $product['vendor_code_new'] = $oldProduct->getParam('vendor_code');
    }
    if(empty($product['country_of_origin_new'])) {
        $product['country_of_origin_new'] = $oldProduct->getParam('country_of_origin');
    }

    $data = [
        'title'             => isset($product['name_new']) ? $product['name_new'] : $oldProduct->getName(),
        'description'       => $content,
        'short_description' => $description,
        'price'             => isset($product['price_new']) ? $product['price_new'] : (float) $oldProduct->getParam('price'),
        'price2'            => isset($product['price2_new']) ? $product['price2_new'] : (float) $oldProduct->getParam('price2'),
        'price3'            => isset($product['price3_new']) ? $product['price3_new'] : (float) $oldProduct->getParam('price3'),
        'price4'            => isset($product['price4_new']) ? $product['price4_new'] : (float) $oldProduct->getParam('price4'),
        'price5'            => isset($product['price5_new']) ? $product['price5_new'] : (float) $oldProduct->getParam('price5'),
        'price_n'           => isset($product['price_n_new']) ? $product['price_n_new'] : $oldProduct->getParam('price_n'),
        'price_search'      => $oldProduct->getParam('price_search'),
        'article'           => isset($product['uid_new']) ? $product['uid_new'] : $oldProduct->getParam('uid'),
        'categories'        => array_values($categories),
        'main_category'     => $product['category_new'],
        'keywords'          => isset($product['keywords_new']) ? $product['keywords_new'] : $oldProduct->getParam('keywords'),
        'image'             => !empty($product['pic_big_new']) ? $Elastic->getFullUrl($product['pic_big_new']) : null,
        'preview_image'     => !empty($product['pic_small_new']) ? $Elastic->getFullUrl($product['pic_small_new']) : null,
        'barcode'           => isset($product['barcode_new']) ? $product['barcode_new'] : null,
        'vendor_name'       => isset($product['vendor_name_new']) ? $product['vendor_name_new'] : null,
        'vendor_code'       => isset($product['vendor_code_new']) ? $product['vendor_code_new'] : null,
        'country_of_origin' => isset($product['country_of_origin_new']) ? $product['country_of_origin_new'] : null,
        'length'            => isset($product['length_new']) ? $product['length_new'] : (float) $oldProduct->getParam('length'),
        'width'             => isset($product['width_new']) ? $product['width_new'] : (float) $oldProduct->getParam('width'),
        'height'            => isset($product['height_new']) ? $product['height_new'] : (float) $oldProduct->getParam('height'),
        'weight'            => isset($product['weight_new']) ? $product['weight_new'] : (float) $oldProduct->getParam('weight'),
        'active'            => isset($product['enabled_new']) ? (int) $product['enabled_new'] : (int) $oldProduct->getParam('enabled'),
        'attributes'        => $atts
    ];

    try {
        $Elastic->client->updateProduct($oldProduct->getParam('elastic_id'), $data);
    } catch (\Exception $exception) {
       // var_dump($exception->getMessage()); exit; for debug
    }
}

$addHandler = [
    'actionStart'  => false,
    'actionDelete' => 'elasticBeforeDeleteProduct',
    'actionUpdate' => 'elasticBeforeUpdateProduct'
];
?>