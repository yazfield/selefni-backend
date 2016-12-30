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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => preg_replace('/[^a-z ]/', '', strtolower($faker->name)),
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = 'mysecret',
        'remember_token' => str_random(10),
        'phone_number' => $faker->unique()->e164PhoneNumber,
    ];
});
