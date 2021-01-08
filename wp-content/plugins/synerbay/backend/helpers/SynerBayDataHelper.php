<?php

namespace SynerBay\Helper;

class SynerBayDataHelper
{
    public static function getMaterialTypes()
    {
        return ArrayHelper::reKeyBySlugFromValue([
            '-',
            'Wood',
            'Metal',
            'Plastic',
            'Textil',
            'Latex',
            'PVC',
            'Silicone',
            'Leather',
            'Paper',
            'Fiber',
            'Glass',
            'Chemical',
            'Composite-material',
            'Mineral',
            'Stone',
            'Concrete',
            'Plaster',
            'Ceramic',
            'Rubber',
            'Foam',
            'Semiconductor',
            'Rare-earths',
        ]);
    }

    public static function getUnitTypes()
    {
        return ArrayHelper::reKeyBySlugFromValue([
            'piece',
            'mm',
            'cm',
            'm',
            'km',
            'ft',
            'in',
            'mg',
            'g',
            'dg',
            'kg',
            'ml',
            'cl',
            'dl',
            'l',
            'mm2',
            'cm2',
            'm2',
            'km2',
        ]);
    }

    public static function getOfferTransportParityTypes()
    {
        return [
            'exw' => 'EXW – Ex Works (named place of delivery)',
            'fca' => 'FCA – Free Carrier (named place of delivery)',
            'cpt' => 'CPT – Carriage Paid To (named place of destination)',
            'cip' => 'CIP – Carriage and Insurance Paid to (named place of destination)',
            'dpu' => 'DPU – Delivered At Place Unloaded (named place of destination)',
            'dap' => 'DAP – Delivered At Place (named place of destination)',
            'ddp' => 'DDP – Delivered Duty Paid (named place of destination)',
            'fas' => 'FAS – Free Alongside Ship (named port of shipment)',
            'fob' => 'FOB – Free on Board (named port of shipment)',
            'cfr' => 'CFR – Cost and Freight (named port of destination)',
            'cif' => 'CIF – Cost, Insurance & Freight (named port of destination)',
            'daf' => 'DAF – Delivered at Frontier (named place of delivery)',
            'dat' => 'DAT – Delivered at Terminal',
            'des' => 'DES – Delivered Ex Ship',
            'deq' => 'DEQ – Delivered Ex Quay (named port of delivery)',
            'ddu' => 'DDU – Delivered Duty Unpaid (named place of destination)',
        ];
    }

    public static function setupDeliveryDestinationsForOfferData(array $deliveryDestinationsSlugs)
    {
        $ret = [];
        $destinations = self::getDeliveryDestinationsForOffer();

        foreach ($deliveryDestinationsSlugs as $slug) {
            if (array_key_exists($slug, $destinations)) {
                $ret[] = $destinations[$slug];
            }
        }

        return $ret;
    }

    public static function getDeliveryDestinationsForOffer()
    {
        return ArrayHelper::reKeyBySlugFromValue([
            'Worldwide',
            'Africa',
            'North America',
            'South America',
            'Europe',
            'Asia',
            'Middle East',
            'Ocean Pacific',
        ]);
    }
}