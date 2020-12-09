<?php

namespace SynerBay\Functions;


use SynerBay\Helper\ArrayHelper;
use SynerBay\Traits\WPActionLoader;

class Data
{
    use WPActionLoader;

    public function __construct()
    {
        $this->addAction('get_material_types', 'getMaterialTypes');
        $this->addAction('get_unit_types', 'getUnitTypes');
        $this->addAction('get_offer_transport_parity_types', 'getOfferTransportParityTypes');
    }

    public function getMaterialTypes()
    {
        return ArrayHelper::reKeyBySlugFromValue([
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

    public function getUnitTypes()
    {
        return ArrayHelper::reKeyBySlugFromValue(['mg', 'g', 'dg', 'kg', 'ml', 'cl', 'dl', 'l']);
    }

    public function getOfferTransportParityTypes()
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
}