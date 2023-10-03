<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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
        'user_id' => $faker->uuid,
        'phone_number' => $faker->phoneNumber,
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'confirmation_token' => Str::random(10),
        'password' => bcrypt('password'),
        'api_token' => Str::random(60),
        'role' => $faker->randomElement(['admin', 'user']),
        'email_verified' => $faker->randomDigitNotNull,
        'active' => true,
        'connected' => false,
        'otp' => Str::random(6),
        'app_hash' => Str::random(40),
        'os_version' => $faker->randomDigitNotNull,
        'sdk_version' => $faker->randomDigitNotNull,
        'device' => $faker->word,
        'device_model' => $faker->word,
        'device_product' => $faker->word,
        'manufacturer' => $faker->word,
        'android_id' => Str::random(16),
        'version_release' => $faker->randomDigitNotNull,
        'device_height' => $faker->randomDigitNotNull,
        'device_width' => $faker->randomDigitNotNull,
        'guid' => Str::uuid(),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
