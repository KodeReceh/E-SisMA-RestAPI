<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'birthplace' => $faker->country,
        'birthdate' => $faker->date,
        'password' => app('hash')->make('secret'),
        'sex' => 1,
        'address' => $faker->address,
        'handphone' => '08282828282'
    ];
});
