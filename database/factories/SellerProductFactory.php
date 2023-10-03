<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use App\Seller;
use App\SellerProduct;
use Faker\Generator as Faker;

$factory->define(SellerProduct::class, function (Faker $faker) {
    return [
        'seller_product_id' => $faker->unique()->uuid,
        'product_id' => function () {
            return factory(Product::class)->create()->product_id;
        },
        'seller_id' => function () {
            return factory(Seller::class)->create()->seller_id;
        },
        'unit_quantity' => $faker->randomNumber(2),
        'unit_price' => $faker->randomFloat(2, 1, 1000),
        'quantity_available' => $faker->randomNumber(3),
    ];
});
