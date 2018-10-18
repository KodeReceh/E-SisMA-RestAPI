<?php

use Illuminate\Database\Seeder;
use App\Models\LetterCode;

class LetterCodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $codes = [
            '400' => 'KESEJAHTERAAN MASYARAKAT',
            '410' => 'PEMBANGUNAN NAGARI/KELURAHAN',
            '411' => 'Pembinaan Sosial Budayaan',
            '412' => 'Perekonomian Nagari/Kelurahan',
            '413' => 'Pembangunan Pemeliharaan Sarana Prasarana Nagari/Kelurahan',
            '414' => 'Pembangunan Nagari/Kelurahan',
            '415' => 'Koordinasi Internal dan Eksternal',
            '420' => 'KEPENDIDIKAN',
            '421' => 'Pendidikan',
            '422' => 'Administrasi Sekolah',
            '423' => 'Metode Pelajaran',
            '424' => 'Tenaga Pengajar',
            '425' => 'Sarana Pendidikan',
            '426' => 'Keolahragaan',
            '427' => 'Kepemudaan/Kegiatan Remaja',
            '428' => 'Kepramukaan',
            '429' => 'Sanggar Kegiatan Belajar'
        ];

        foreach ($codes as $code => $title) {
            LetterCode::create([
                'code' => $code,
                'title' => $title
            ]);
        }
    }
}
