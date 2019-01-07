<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(LetterCodesTableSeeder::class);
        $this->call(SubLetterCodesTableSeeder::class);
        $this->call(VillagersTableSeeder::class);
        $this->call(ArchiveTypesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        Model::reguard();
    }
}
