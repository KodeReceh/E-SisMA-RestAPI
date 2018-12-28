<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // factory(App\Models\User::class, 5)->make();
        $user = new User();
        $user->name = 'Nama Saya Pengguna';
        $user->email = 'email@email.com';
        $user->password = app('hash')->make('rahasia');
        $user->birthplace = 'Padang';
        $user->birthdate = '1996-02-02';
        $user->sex = 1;
        $user->address = 'Padang';
        $user->handphone = '08222222222';
        $role = Role::first();
        $user->role()->associate($role);
        $user->save();
    }
}
