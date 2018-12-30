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
    'generated_docs' => 'generated_docs',
    'field_types' => [
        1 => 'Text',
        'Gambar',
        'Data Penduduk',
        'Tanda Tangan'
    ],
    'villager_fields' => [
        'nama' => 'name',
        'tempat_lahir' => 'birthplace',
        'tanggal_lahir' => 'birthdate',
        'jenis_kelamin' => 'sex',
        'pekerjaan' => 'job',
        'agama' => 'religion',
        'suku' => 'tribe',
        'NIK' => 'NIK',
        'status' => 'status',
        'alamat' => 'address',
        'foto' => 'photo'
    ]
];