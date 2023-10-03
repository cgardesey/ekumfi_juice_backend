<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\EkumfiInfo;
use App\User;
use Faker\Generator as Faker;

$factory->define(EkumfiInfo::class, function (Faker $faker) {
    return [
        'ekumfi_info_id' => $this->faker->uuid,
        'name' => $this->faker->name,
        'profile_image_url' => $this->faker->imageUrl(),
        'primary_contact' => $this->faker->phoneNumber,
        'auxiliary_contact' => $this->faker->phoneNumber,
        'longitude' => $this->faker->longitude,
        'latitude' => $this->faker->latitude,
        'digital_address' => $this->faker->secondaryAddress,
        'street_address' => $this->faker->streetAddress,
        'availability' => $this->faker->randomElement(['Available', 'Unavailable']),
        'user_id' => function () {
            return factory(User::class)->create()->user_id;
        },
    ];
});
