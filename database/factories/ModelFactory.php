<?php

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

$factory->define(App\Models\Villager::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'birthplace' => $faker->country,
        'birthdate' => $faker->date,
        'job' => $faker->company,
        'religion' => rand(1, 6),
        'sex' => rand(1, 2),
        'tribe' => rand(1, 7),
        'address' => $faker->address,
        'NIK' => $faker->unique()->randomNumber($nbDigits = 8),
        'status' => rand(1, 2),
        'photo' => $faker->imageUrl($width = 640, $height = 480),
    ];
});
