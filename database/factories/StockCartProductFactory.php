<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AgentProduct;
use App\StockCart;
use App\StockCartProduct;
use Faker\Generator as Faker;

$factory->define(StockCartProduct::class, function (Faker $faker) {
    return [
        'stock_cart_product_id' => $this->faker->unique()->uuid,
        'stock_cart_id' => function () {
            return factory(StockCart::class)->create()->stock_cart_id;
        },
        'agent_product_id' => function () {
            return factory(AgentProduct::class)->create()->agent_product_id;
        },
        'quantity' => $this->faker->randomNumber(2),
        'price' => $this->faker->randomFloat(2, 0, 9999.99),
    ];
});
