<?php

$_classPath = "../../../";
include_once($_classPath . "class/obj.class.php");
include_once($_classPath . "modules/avito/xml/AbstractAvitoXml.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true, true);
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("file");
PHPShopObj::loadClass("promotions");

/**
 * XML прайс Авито "Бытовая электроника"
 * @author PHPShop Software
 * @version 1.0
 */
class Appliances extends AbstractAvitoXml {

    public function setAds()
    {
        $this->xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $this->xml .= '<Ads formatVersion="3" target="Avito.ru">';

        $products = $this->getProducts($_GET['getall']);

        foreach ($products as $product) {
            $this->xml .= '<Ad>';
            $this->xml .= sprintf('<Id>%s</Id>', $product['id']);
            $this->xml .= sprintf('<Category>%s</Category>', $product['category']);
            $this->xml .= sprintf('<GoodsType>%s</GoodsType>', $product['type']);
            $this->xml .= sprintf('<Title>%s</Title>', $product['name']);
            $this->xml .= sprintf('<Description>%s</Description>', $product['description']);
            $this->xml .= sprintf('<Price>%s</Price>', $product['price']);
            $this->xml .= sprintf('<AdStatus>%s</AdStatus>', $product['status']);
            $this->xml .= sprintf('<ListingFee>%s</ListingFee>', $product['listing_fee']);
            $this->xml .= sprintf('<Condition>%s</Condition>', $product['condition']);
            $this->xml .= sprintf('<ManagerName>%s</ManagerName>', PHPShopString::win_utf8($this->Avito->options['manager']));
            $this->xml .= sprintf('<ContactPhone>%s</ContactPhone>', PHPShopString::win_utf8($this->Avito->options['phone']));
            $this->xml .= sprintf('<Address>%s</Address>', PHPShopString::win_utf8($this->getAddress()));

            if(count($product['images']) > 0) {
                $this->xml .= '<Images>';
                foreach ($product['images'] as $image) {
                    $this->xml .= sprintf('<Image url="%s"/>', $image['name']);
                }
                $this->xml .= '</Images>';
            }

            $this->xml .= '</Ad>';
        }

        $this->xml .= '</Ads>';
    }
}

header("HTTP/1.1 200");
header("Content-Type: application/xml; charset=utf-8");
$Appliances = new Appliances(1);
$Appliances->compile();
?>