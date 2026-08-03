<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Municipio;
use App\Models\SubRole;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */


$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'apellido_p' => $faker->lastName,
        'apellido_m' => $faker->lastName,
        'rfc' => $faker->randomDigit,
        'curp' => $faker->randomDigit,
        'celular' => $faker->phoneNumber,
        'id_municipio' => 1,
        'password' => Hash::make('123456')
    ];
});


$factory->define(Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'last_name' => $faker->lastName,
        'address' => $faker->address,
        'identification' => Str::random(20)
    ];
});

$factory->define(Product::class, function (Faker $faker) {
    $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

    return [
        'sku' => Str::random(20),
        'name' => $faker->productName,
        'description' => $faker->text(200),
        'price' => $faker->numberBetween(1, 250)
    ];
});

$factory->define(SubRole::class, function (Faker $faker) {
    $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

    return [
        'nombre' => $faker->productName,
        'descripcion' => $faker->text(200),
    ];
});