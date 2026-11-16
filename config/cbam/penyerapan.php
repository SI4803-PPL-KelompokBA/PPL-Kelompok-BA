<?php

return [
    'label' => 'Penyerapan Karbon Alam',
    'faktor_kategori' => 'Penyerapan Karbon',
    'faktor_label' => 'Jenis penyerapan',
    'jumlah_label' => 'Jumlah (sesuai satuan faktor: pohon / ha / ton)',
    'extra' => [
        ['name' => 'spesies',         'label' => 'Spesies pohon', 'type' => 'text',   'required' => false],
        ['name' => 'diameter_cm',     'label' => 'Diameter (cm)', 'type' => 'number', 'required' => false],
        ['name' => 'cara_olah_tanah', 'label' => 'Cara pengolahan tanah', 'type' => 'select',
            'options' => ['Konvensional', 'Minimum tillage', 'No-till'], 'required' => false],
        ['name' => 'catatan',         'label' => 'Catatan', 'type' => 'text', 'required' => false],
    ],
];