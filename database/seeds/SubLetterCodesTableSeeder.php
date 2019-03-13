<?php

use Illuminate\Database\Seeder;
use App\Models\LetterCode;

class SubLetterCodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $letterCodes = LetterCode::where('letter_code_id', null)->get();
        $subs = [
            411 => [
                1 => 'Gotong Royong',
                'Lembaga Sosial Nagari (LSN)',
                'Latihan Kerja Masyarakat',
                'Pembinaan Kesejahteraan Keluarga',
                'Penyuluhan Masyarakat Nagari/Kelurahan'
            ],
            412 => [
                1 => 'Usaha Produksi Masyarakat termasuk Pengolahan, Pemasaran, Pendistribusian, dll',
                'Keuangan Nagari/Kelurahan Termasuk LPN, BPR',
                'Koperasi Nagari/Kelurahan',
                'Penataan Pembangunan Nagari/Kelurahan',
                'Bantuan Pembangunan Nagari/Kelurahan',
                'Pelaksanaan Bantuan Pembangunan Nagari/Kelurahan'
            ],
            413 => [
                1 => 'Pembinaan',
                'Pemukiman',
                'Masyarakat Pra Desa/Pra Nagari/Kelurahan',
                'Pemugaran Perumahan',
                'Lingkungan Hidup'
            ],
            414 => [
                1 => 'Tingkat Perkembangan Nagari/Kelurahan',
                'Unit Daerah Kerja Pembangunan (UDKP)',
                'Tata Nagari/Kelurahan',
                'Perlombaan Nagari/Kelurahan'
            ],
            421 => [
                1 => 'Dasar',
                'Menengah',
                'Tinggi',
                'Khusus',
                'Pendidikan Luar Sekolah'
            ]
        ];

        foreach ($letterCodes as $key => $value) {
            if (array_key_exists($value->code, $subs)) {
                foreach ($subs[$value->code] as $k => $v) {
                    LetterCode::create([
                        'code' => $k,
                        'title' => $v,
                        'letter_code_id' => $value->id
                    ]);
                }
            }
        }
    }
}
