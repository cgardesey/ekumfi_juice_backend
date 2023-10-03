<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Agent;
use App\User;
use App\Wholesaler;
use Faker\Generator as Faker;

$factory->define(Agent::class, function (Faker $faker) {
    return [
        'agent_id' => $this->faker->unique()->uuid,
        'confirmation_token' => $this->faker->optional()->uuid,
        'agent_type' => $this->faker->randomElement(['Type A', 'Type B', 'Type C', null]),
        'title' => $this->faker->title,
        'first_name' => $this->faker->firstName,
        'last_name' => $this->faker->lastName,
        'other_names' => $this->faker->optional()->name,
        'gender' => $this->faker->randomElement(['Male', 'Female', 'Other', null]),
        'profile_image_url' => $this->faker->optional()->imageUrl(),
        'primary_contact' => $this->faker->phoneNumber,
        'auxiliary_contact' => $this->faker->optional()->phoneNumber,
        'momo_number' => $this->faker->optional()->phoneNumber,
        'longitude' => $this->faker->longitude,
        'latitude' => $this->faker->latitude,
        'digital_address' => $this->faker->optional()->streetAddress,
        'street_address' => $this->faker->optional()->streetAddress,
        'identification_type' => $this->faker->optional()->randomElement(['Passport', 'Driver\'s License', 'National ID', null]),
        'identification_number' => $this->faker->optional()->randomNumber(),
        'identification_image_url' => $this->faker->optional()->imageUrl(),
        'availability' => $this->faker->randomElement(['Available', 'Unavailable']),
        'verified' => $this->faker->boolean,
        'user_id' => function () {
            return factory(User::class)->create()->user_id;
        },
        'wholesaler_id' => function () {
            return factory(Wholesaler::class)->create()->wholesaler_id;
        },
    ];
});
