<?php

return [
    'label' => 'Listrik',
    'faktor_kategori' => 'Listrik',
    'faktor_label' => 'Sumber listrik',
    'jumlah_label' => 'Konsumsi listrik (kWh)',
    'extra' => [
        ['name' => 'periode_tagihan', 'label' => 'Periode tagihan', 'type' => 'text', 'required' => false],
        ['name' => 'nomor_meter', 'label' => 'Nomor meter', 'type' => 'text', 'required' => false],
    ],
];