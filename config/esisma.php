<?php

return [
    'dokumen' => [
        'surat' => [
            'masuk' => 'dokumen/surat/masuk',
            'keluar' => 'dokumen/surat/keluar'
        ],
        'general' => 'dokumen/general'
    ],
    'verify_letter_url' => env('VERIFY_LETTER_URL'),
    'templates' => 'templates',
    'raw_images' => 'raw_images',
    'template_data_image' => 'templates/img',
    'signatures' => 'signatures',
    'generated_docs' => 'generated_docs',
    'villager_photos' => 'villager_photos',
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
    ],
    'signatures' => 'signatures',
    'user_fields' => [
        'nama' => 'name',
        'tempat_lahir' => 'birthplace',
        'tanggal_lahir' => 'birthdate',
        'jenis_kelamin' => 'sex',
        'NIP' => 'employee_id_number',
        'email' => 'email',
        'alamat' => 'address',
        'HP' => 'handphone',
        'jabatan' => 'role.title',
        'tanda_tangan' => 'signature'
    ],
    'letter_number_field_alias' => 'nomor',
    'letter_date_field_alias' => 'tanggal',
    'empty_sign_file' => 'blank.png',
    'religions' => [
        1 => 'Islam',
        'Protestan',
        'Katolik',
        'Hindu',
        'Budha',
        'Konghuchu'
    ],
    'sexes' => [
        1 => 'Laki-laki',
        'Perempuan'
    ],
    'tribes' => [
        1 => 'Koto',
        'Piliang',
        'Bodi',
        'Chaniago',
        'Sikumbang',
        'Melayu',
        'Lainnya'
    ],
    'villager_statuses' => [
        1 => 'WNI',
        'WNA'
    ],
];