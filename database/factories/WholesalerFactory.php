<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Wholesaler;
use Faker\Generator as Faker;

$factory->define(Wholesaler::class, function (Faker $faker) {
    return [
        'wholesaler_id' => Str::random(10),
        'confirmation_token' => null,
        'shop_name' => $this->faker->company,
        'shop_image_url' => null,
        'primary_contact' => $this->faker->phoneNumber,
        'auxiliary_contact' => $this->faker->phoneNumber,
        'momo_number' => $this->faker->phoneNumber,
        'longitude' => $this->faker->longitude,
        'latitude' => $this->faker->latitude,
        'digital_address' => $this->faker->word,
        'street_address' => $this->faker->streetAddress,
        'identification_type' => $this->faker->word,
        'identification_number' => $this->faker->unique()->randomNumber(),
        'identification_image_url' => null,
        'availability' => 'Available',
        'verified' => false,
        'user_id' => function () {
            return factory(User::class)->create()->user_id;
        },
    ];
});
