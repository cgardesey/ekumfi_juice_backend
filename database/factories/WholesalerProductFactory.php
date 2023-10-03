<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use App\Wholesaler;
use App\WholesalerProduct;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(WholesalerProduct::class, function (Faker $faker) {
    return [
        'wholesaler_product_id' => Str::uuid()->toString(),
        'product_id' => function () {
            return factory(Product::class)->create()->product_id;
        },
        'wholesaler_id' => function () {
            return factory(Wholesaler::class)->create()->wholesaler_id;
        },
        'unit_quantity' => $faker->numberBetween(1, 100),
        'unit_price' => $faker->randomFloat(2, 1, 1000),
        'quantity_available' => $faker->numberBetween(0, 1000),
    ];
});
