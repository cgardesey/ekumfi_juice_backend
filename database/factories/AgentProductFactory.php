<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Agent;
use App\AgentProduct;
use App\Product;
use Faker\Generator as Faker;

$factory->define(AgentProduct::class, function (Faker $faker) {
    return [
        'agent_product_id' => $this->faker->unique()->uuid,
        'product_id' => $this->faker->randomNumber(5),
        'agent_id' => $this->faker->randomNumber(5),
        'unit_quantity' => $this->faker->randomNumber(1),
        'unit_price' => $this->faker->randomFloat(2, 0, 100),
        'quantity_available' => $this->faker->randomNumber(2),
        'product_id' => function () {
            return factory(Product::class)->create()->product_id;
        },
        'agent_id' => function () {
            return factory(Agent::class)->create()->agent_id;
        },
    ];
});
