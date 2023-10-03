<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Agent;
use App\Wholesaler;
use App\WholesalerCart;
use Faker\Generator as Faker;

$factory->define(WholesalerCart::class, function (Faker $faker) {
    return [
        'wholesaler_cart_id' => $this->faker->unique()->uuid,
        'order_id' => $this->faker->uuid,
        'wholesaler_id' => function () {
            return factory(Wholesaler::class)->create()->wholesaler_id;
        },
        'agent_id' => function () {
            return factory(Agent::class)->create()->agent_id;
        },
        'shipping_fee' => $this->faker->randomFloat(2, 0, 9999),
        'delivered' => $this->faker->boolean,
        'paid' => $this->faker->boolean,
        'credited' => $this->faker->boolean,
        'credit_paid' => $this->faker->boolean,
    ];
});
