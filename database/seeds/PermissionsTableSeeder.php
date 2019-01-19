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
            'atur_surat_masuk',
            'atur_surat_keluar',
            'atur_jabatan',
            'atur_template_surat',
            'atur_draft_surat_keluar',
            'atur_penduduk',
            'regular'
        ];

        foreach ($permissions as $key => $permission) {
            Permission::create([
                'can' => $permission
            ]);
        }
    }
}
