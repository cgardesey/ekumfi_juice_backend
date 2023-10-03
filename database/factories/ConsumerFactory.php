<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Consumer;
use App\User;
use Faker\Generator as Faker;

$factory->define(Consumer::class, function (Faker $faker) {
    return [
        'consumer_id' => $this->faker->uuid,
        'confirmation_token' => null,
        'name' => $this->faker->name,
        'profile_image_url' => null,
        'gender' => $this->faker->randomElement(['male', 'female', 'other']),
        'employment_category' => $this->faker->randomElement(['full-time', 'part-time', 'contractor', 'freelancer', 'unemployed']),
        'primary_contact' => $this->faker->phoneNumber,
        'auxiliary_contact' => $this->faker->phoneNumber,
        'longitude' => $this->faker->longitude,
        'latitude' => $this->faker->latitude,
        'digital_address' => $this->faker->optional()->address,
        'street_address' => $this->faker->optional()->streetAddress,
        'user_id' => function () {
            return factory(User::class)->create()->user_id;
        },
    ];
});
