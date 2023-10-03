<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cart;
use App\CartProduct;
use App\SellerProduct;
use Faker\Generator as Faker;

$factory->define(CartProduct::class, function (Faker $faker) {
    return [
        'cart_product_id' => Str::uuid()->toString(),
        'cart_id' => function () {
            return factory(Cart::class)->create()->cart_id;
        },
        'seller_product_id' => function () {
            return factory(SellerProduct::class)->create()->seller_product_id;
        },
        'quantity' => $faker->numberBetween(1, 10),
        'price' => $faker->randomFloat(2, 10, 100),
    ];
});
