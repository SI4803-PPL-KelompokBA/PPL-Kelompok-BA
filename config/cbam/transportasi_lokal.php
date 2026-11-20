
<?php

return [
    'label' => 'Transportasi Lokal',
    'faktor_kategori' => 'Transportasi Lokal',
    'faktor_label' => 'Jenis kendaraan',

    'jumlah_dari' => ['jarak_km', 'muatan_ton'],

    'extra' => [
        [
            'name' => 'jarak_km',
            'label' => 'Jarak tempuh (km)',
            'type' => 'number',
            'required' => true,
        ],
        [
            'name' => 'muatan_ton',
            'label' => 'Muatan (ton)',
            'type' => 'number',
            'required' => true,
        ],
        [
            'name' => 'asal',
            'label' => 'Titik asal',
            'type' => 'text',
            'required' => false,
        ],
        [
            'name' => 'tujuan',
            'label' => 'Fasilitas pemrosesan tujuan',
            'type' => 'text',
            'required' => true,
        ],
    ],
];
