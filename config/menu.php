<?php

return [
    'data_inputter' => [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
        ],
        [
            'label' => 'Input & Riwayat',
            'route' => 'inputter.emissions.index',
            'active' => 'inputter.emissions.*',
        ],
    ],

    'esg_officer' => [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
        ],
        [
            'label' => 'Lahan',
            'route' => 'esg.lands.index',
            'active' => 'esg.lands.*',
        ],
        [
            'label' => 'Faktor Emisi',
            'route' => 'esg.emission-factors.index',
            'active' => 'esg.emission-factors.*',
        ],
        [
            'label' => 'Target Emisi',
            'route' => 'esg.targets.index',
        ],
        [
            'label' => 'Perhitungan Emisi',
            'route' => 'esg.calculations.index',
        ],
    ],

    'auditor' => [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
        ],
    ],
];
