<?php

include_once dirname(__DIR__) . '/Xml/BaseAvitoXml.php';
include_once dirname(__DIR__) . '/Xml/AvitoPriceInterface.php';

/**
 * XML прайс Авито "Запчасти и аксессуары"
 * @author PHPShop Software
 * @version 1.1
 */
class AvitoSpare extends BaseAvitoXml implements AvitoPriceInterface
{
    public static function getXml($product)
    {
        $tier = unserialize($product['tiers']);

        $xml = '<Ad>';
        $xml .= sprintf('<Id>%s</Id>', $product['id']);
        $xml .= sprintf('<ListingFee>%s</ListingFee>', $product['listing_fee']);
        $xml .= sprintf('<AdStatus>%s</AdStatus>', $product['status']);
        $xml .= sprintf('<ManagerName>%s</ManagerName>', PHPShopString::win_utf8(Avito::getOption('manager')));
        $xml .= sprintf('<ContactPhone>%s</ContactPhone>', PHPShopString::win_utf8(Avito::getOption('phone')));
        $xml .= sprintf('<Address>%s</Address>', PHPShopString::win_utf8(static::getAddress()));
        $xml .= sprintf('<Category>%s</Category>', $product['category']);
        $xml .= sprintf('<TypeId>%s</TypeId>', str_replace('[', '', explode(']', $product['type']))[0]);
        $xml .= sprintf('<AdType>%s</AdType>', $product['ad_type']);
        $xml .= sprintf('<Title>%s</Title>', $product['name']);
        $xml .= sprintf('<Description>%s</Description>', $product['description']);
        $xml .= sprintf('<Price>%s</Price>', $product['price']);
        $xml .= sprintf('<Condition>%s</Condition>', $product['condition']);
        $xml .= sprintf('<OEM>%s</OEM>', $product['oem']);
        if(isset($tier['diameter']) && !empty($tier['diameter'])) {
            $xml .= sprintf('<RimDiameter>%s</RimDiameter>', $tier['diameter']);
        }
        if(isset($tier['tier-type']) && !empty($tier['tier-type'])) {
            $xml .= sprintf('<TireType>%s</TireType>', PHPShopString::win_utf8($tier['tier-type']));
        }
        if(isset($tier['wheel-axle']) && !empty($tier['wheel-axle'])) {
            $xml .= sprintf('<WheelAxle>%s</WheelAxle>', PHPShopString::win_utf8($tier['wheel-axle']));
        }
        if(isset($tier['rim-type']) && !empty($tier['rim-type'])) {
            $xml .= sprintf('<RimType>%s</RimType>', PHPShopString::win_utf8($tier['rim-type']));
        }
        if(isset($tier['tire-section-width']) && !empty($tier['tire-section-width'])) {
            $xml .= sprintf('<TireSectionWidth>%s</TireSectionWidth>', $tier['tire-section-width']);
        }
        if(isset($tier['tire-aspect-ratio']) && !empty($tier['tire-aspect-ratio'])) {
            $xml .= sprintf('<TireAspectRatio>%s</TireAspectRatio>', $tier['tire-aspect-ratio']);
        }
        if(isset($tier['rim-width']) && !empty($tier['rim-width'])) {
            $xml .= sprintf('<RimWidth>%s</RimWidth>', $tier['rim-width']);
        }
        if(isset($tier['rim-bolts']) && !empty($tier['rim-bolts'])) {
            $xml .= sprintf('<RimBolts>%s</RimBolts>', $tier['rim-bolts']);
        }
        if(isset($tier['rim-bolts-diameter']) && !empty($tier['rim-bolts-diameter'])) {
            $xml .= sprintf('<RimBoltsDiameter>%s</RimBoltsDiameter>', $tier['rim-bolts-diameter']);
        }
        if(isset($tier['rim-offset']) && !empty($tier['rim-offset'])) {
            $xml .= sprintf('<RimOffset>%s</RimOffset>', $tier['rim-offset']);
        }

        if(count($product['images']) > 0) {
            $xml .= '<Images>';
            foreach ($product['images'] as $image) {
                $xml .= sprintf('<Image url="%s"/>', $image['name']);
            }
            $xml .= '</Images>';
        }

        $xml .= '</Ad>';

        return $xml;
    }
}
?>