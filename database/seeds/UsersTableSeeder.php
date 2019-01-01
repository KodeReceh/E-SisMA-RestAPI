<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Faker\Generator as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        
        // factory(App\Models\User::class, 5)->make();
        $roles = [
            $waliNagari = Role::find(1),
            $sekretaris = Role::find(2),
            $kasiKeuangan = Role::find(3)
        ];

        $signatures = [
            'tandatangan.svg',
            'tandatangan2.png',
            'tandatangan3.png'
        ];
        
        foreach ($roles as $key => $role) {
            $user = new User();
            $user->name = $faker->name;
            $user->email = 'email'.$key.'@email.com';
            $user->password = app('hash')->make('rahasia');
            $user->birthplace = $faker->city;
            $user->birthdate = '1996-02-02';
            $user->sex = 1;
            $user->address = $faker->address;
            $user->handphone = $faker->phoneNumber;
            $user->signature = $signatures[$key];
            $user->role()->associate($role);
            $user->save();
        }
    }
}
