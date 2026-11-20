<?php

return [
    'label' => 'Logistik Ekspor',
    'faktor_kategori' => 'Logistik Ekspor',
    'faktor_label' => 'Moda transportasi',
    'jumlah_dari' => ['jarak_km', 'muatan_ton'],
    'extra' => [
        ['name' => 'pelabuhan_asal',   'label' => 'Pelabuhan asal',                'type' => 'text',   'required' => true],
        ['name' => 'pelabuhan_tujuan', 'label' => 'Pelabuhan tujuan (Eropa)',      'type' => 'text',   'required' => true],
        ['name' => 'jarak_km',         'label' => 'Jarak (km)',                    'type' => 'number', 'required' => true],
        ['name' => 'muatan_ton',       'label' => 'Berat muatan (ton)',            'type' => 'number', 'required' => true],
        ['name' => 'no_bl',            'label' => 'No. B/L / Dokumen pengiriman',  'type' => 'text',   'required' => false],
    ],
];