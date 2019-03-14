<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'Wali Nagari',
                'Ini wali nagari coy'
            ],
            [
                'Sekretaris',
                'Ini sekretaris'
            ],
            [
                'Kaur Pembangunan',
                'Ini Kepala Urusan Pembangunan'
            ]
        ];

        foreach ($roles as $key => $role) {
            Role::create([
                'title' => $role[0],
                'description' => $role[1]
            ]);
        }

        $role = Role::create([
            'title' => 'Admin',
            'description' => 'Ini adalah Super User'
        ]);

        $role->syncPermissionsByName('atur_pengguna', 'atur_jabatan');
    }
}
