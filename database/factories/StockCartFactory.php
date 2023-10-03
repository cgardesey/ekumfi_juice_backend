<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Agent;
use App\Seller;
use App\StockCart;
use Faker\Generator as Faker;

$factory->define(StockCart::class, function (Faker $faker) {
    return [
        'stock_cart_id' => $this->faker->uuid,
        'order_id' => $this->faker->uuid,
        'agent_id' => function () {
            return factory(Agent::class)->create()->agent_id;
        },
        'seller_id' => function () {
            return factory(Seller::class)->create()->seller_id;
        },
        'shipping_fee' => $this->faker->randomFloat(2, 0, 1000),
        'delivered' => $this->faker->boolean,
        'paid' => $this->faker->boolean,
        'credited' => $this->faker->boolean,
        'credit_paid' => $this->faker->boolean,
    ];
});
