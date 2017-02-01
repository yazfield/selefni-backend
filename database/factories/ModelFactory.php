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

$factory->define(App\Item::class, function (Faker\Generator $faker) {
    #TODO: borrowed_to, other fields..
    return [
        'name' => preg_replace('/[^a-z ]/', '', strtolower($faker->name)),
        'details' => $faker->text,
        'return_at' => $faker->dateTime,
        'borrowed_to' => 1,
        'borrowed_from' => 2,
    ];
});