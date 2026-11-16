<?php

return [
    'label' => 'Transportasi Lokal',
    'faktor_kategori' => 'Transportasi Lokal',
    'faktor_label' => 'Jenis kendaraan',
    'jumlah_dari' => ['jarak_km', 'muatan_ton'], // jumlah = jarak x muatan (ton-km)
    'extra' => [
        ['name' => 'jarak_km',   'label' => 'Jarak tempuh (km)',  'type' => 'number', 'required' => true],
        ['name' => 'muatan_ton', 'label' => 'Muatan (ton)',       'type' => 'number', 'required' => true],
        ['name' => 'asal',       'label' => 'Lahan / titik asal', 'type' => 'text',   'required' => false],
        ['name' => 'tujuan',     'label' => 'Fasilitas tujuan',   'type' => 'text',   'required' => false],
    ],
];