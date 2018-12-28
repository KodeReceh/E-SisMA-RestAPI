<?php

return [
    'dokumen' => [
        'surat' => [
            'masuk' => 'dokumen/surat/masuk',
            'keluar' => 'dokumen/surat/keluar'
        ],
        'general' => 'dokumen/general'
    ],
    'templates' => 'templates',
    'raw_images' => 'raw_images',
    'template_data_image' => 'templates/img',
    'signatures' => 'signatures',
    'field_types' => [
        1 => 'Text',
        'Gambar',
        'Data Penduduk',
        'Tanda Tangan'
    ],
    'villager_field' => [
        [
            'id' => 'name',
            'title' => 'Nama'
        ],
        [
            'id' => 'birthplace',
            'title' => 'Tempat Lahir'
        ],
        [
            'id' => 'birthdate',
            'title' => 'Tanggal Lahir'
        ],
        [
            'id' => 'sex',
            'title' => 'Jenis Kelamin'
        ],
        [
            'id' => 'job',
            'title' => 'Pekerjaan'
        ],
        [
            'id' => 'religion',
            'title' => 'Agama'
        ],
        [
            'id' => 'tribe',
            'title' => 'Suku'
        ],
        [
            'id' => 'NIK',
            'title' => 'NIK'
        ],
        [
            'id' => 'status',
            'title' => 'Status'
        ],
        [
            'id' => 'address',
            'title' => 'Alamat'
        ],
        [
            'id' => 'photo',
            'title' => 'Foto'
        ],
    ]
];