<?php

include_once dirname(__DIR__) . '/class/include.php';

function elasticBeforeCreateProduct($product)
{
    $Elastic = new Elastic();

    $description = trim(strip_tags($product['description_new']));
    $content = trim(strip_tags($product['content_new']));

    $categories = explode('#', $product['dop_cat_new']);
    if(!is_array($categories)) {
        $categories = [];
    }
    $categories[] = $product['category_new'];
    $categories = array_diff($categories, ['']);

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

    $_POST['elastic_id_new'] = \Ramsey\Uuid\Uuid::uuid4()->toString();

    $data = [
        'id'                => $_POST['elastic_id_new'],
        'title'             => $product['name_new'],
        'description'       => $content,
        'short_description' => $description,
        'price'             => (float) $product['price_new'],
        'price2'            => (float) $product['price2_new'],
        'price3'            => (float) $product['price3_new'],
        'price4'            => (float) $product['price4_new'],
        'price5'            => (float) $product['price5_new'],
        'price_n'           => (float) $product['price_n_new'],
        'price_search'      => (float) $product['price_new'],
        'article'           => $product['uid_new'],
        'categories'        => array_values($categories),
        'main_category'     => $product['category_new'],
        'keywords'          => $product['keywords_new'],
        'image'             => !empty($product['pic_big_new']) ? $Elastic->getFullUrl($product['pic_big_new']) : null,
        'preview_image'     => !empty($product['pic_small_new']) ? $Elastic->getFullUrl($product['pic_small_new']) : null,
        'barcode'           => isset($product['barcode_new']) ? $product['barcode_new'] : null,
        'vendor_name'       => isset($product['vendor_name_new']) ? $product['vendor_name_new'] : null,
        'vendor_code'       => isset($product['vendor_code_new']) ? $product['vendor_code_new'] : null,
        'country_of_origin' => isset($product['country_of_origin_new']) ? $product['country_of_origin_new'] : null,
        'length'            => $product['length_new'],
        'width'             => $product['width_new'],
        'height'            => $product['height_new'],
        'weight'            => $product['weight_new'],
        'active'            => (int) $product['enabled_new'],
        'attributes'        => $atts
    ];

    try {
        $Elastic->client->importProducts([$data]);
    } catch (\Exception $exception) {
        // var_dump($exception->getMessage()); exit; for debug
    }
}

$addHandler = [
    'actionStart'  => false,
    'actionInsert' => 'elasticBeforeCreateProduct'
];
?>