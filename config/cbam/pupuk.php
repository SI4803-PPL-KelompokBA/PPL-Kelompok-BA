<?php

return [
    'label' => 'Pupuk',
    'faktor_kategori' => 'Pupuk',
    'faktor_label' => 'Jenis pupuk',
    'jumlah_label' => 'Jumlah pupuk (kg)',
    'extra' => [
        ['name' => 'merek_produsen', 'label' => 'Merek / produsen', 'type' => 'text', 'required' => false],
        [
            'name' => 'tipe_pupuk',
            'label' => 'Tipe pupuk',
            'type' => 'select',
            'required' => false,
            'options' => [
                'kimia' => 'Kimia',
                'organik' => 'Organik',
            ],
        ],
        ['name' => 'kandungan_nitrogen', 'label' => 'Kandungan nitrogen (%)', 'type' => 'number', 'required' => false],
        ['name' => 'catatan', 'label' => 'Catatan', 'type' => 'text', 'required' => false],
    ],
];