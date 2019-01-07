<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'atur_pengguna',
            'atur_surat',
            'atur_jabatan',
            'atur_dokumen',
            'lihat_surat_masuk',
            'tanda_tangan_surat',
            'download_dokumen_sendiri',
            'super_user',
            'atur_template_surat',
            'atur_draft_surat_keluar'
        ];

        foreach ($permissions as $key => $permission) {
            Permission::create([
                'can' => $permission
            ]);
        }
    }
}
