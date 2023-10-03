<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Agent;
use App\Seller;
use App\User;
use Faker\Generator as Faker;

$factory->define(Seller::class, function (Faker $faker) {
    return [
        'seller_id' => $this->faker->uuid,
        'confirmation_token' => null,
        'seller_type' => $this->faker->randomElement(['Type A', 'Type B', 'Type C', null]),
        'shop_name' => $this->faker->company,
        'shop_image_url' => null,
        'primary_contact' => $this->faker->phoneNumber,
        'auxiliary_contact' => $this->faker->phoneNumber,
        'momo_number' => $this->faker->phoneNumber,
        'longitude' => $this->faker->longitude,
        'latitude' => $this->faker->latitude,
        'digital_address' => $this->faker->address,
        'street_address' => $this->faker->streetAddress,
        'identification_type' => $this->faker->randomElement(['ID Type A', 'ID Type B', 'ID Type C', null]),
        'identification_number' => $this->faker->unique()->numerify('ID#######'),
        'identification_image_url' => null,
        'availability' => $this->faker->randomElement(['Available', 'Not Available']),
        'verified' => $this->faker->boolean,
        'user_id' => function () {
            return factory(User::class)->create()->user_id;
        },
        'agent_id' => function () {
            return factory(Agent::class)->create()->agent_id;
        },
    ];
});
