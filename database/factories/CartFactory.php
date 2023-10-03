<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cart;
use App\Consumer;
use App\Seller;
use Faker\Generator as Faker;

$factory->define(Cart::class, function (Faker $faker) {
    return [
        'cart_id' => $this->faker->unique()->uuid,
        'order_id' => $this->faker->uuid,
        'seller_id' => function () {
            return factory(Seller::class)->create()->seller_id;
        },
        'consumer_id' => function () {
            return factory(Consumer::class)->create()->consumer_id;
        },
        'shipping_fee' => $this->faker->randomFloat(2, 0, 100), // Adjust the range as needed
        'delivered' => $this->faker->boolean,
    ];
});
