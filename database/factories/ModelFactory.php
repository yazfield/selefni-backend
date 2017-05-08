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

use Carbon\Carbon;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
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
    //TODO: borrowed_to, other fields..
    return [
        'name' => preg_replace('/[^a-z ]/', '', strtolower($faker->name)),
        'details' => $faker->text,
        'return_at' => (new Carbon)->toDateTimeString(),
        'type' => 'object',
        'borrowed_to' => 1,
        'borrowed_from' => 2,
        'owner_id' => 2,
    ];
});

$factory->defineAs(App\Item::class, 'book', function ($faker) use ($factory) {
    $item = $factory->raw(App\Item::class);

    return array_merge($item, [ 'type' => 'book' ]);
});

$factory->defineAs(App\Item::class, 'money', function ($faker) use ($factory) {
    $item = $factory->raw(App\Item::class);

    return array_merge($item, [ 'type' => 'money', 'amount' => rand(1, 10000) ]);
});

$factory->defineAs(App\Item::class, 'object', function ($faker) use ($factory) {
    $item = $factory->raw(App\Item::class);

    return array_merge($item, [ 'type' => 'object' ]);
});

