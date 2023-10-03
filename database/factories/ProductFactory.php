<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'product_id' => $this->faker->unique()->uuid,
        'name' => $this->faker->word,
        'description' => $this->faker->paragraph,
        'image_url' => $this->faker->imageUrl(),
        'unit_quantity' => $this->faker->randomNumber(2),
        'unit_price' => $this->faker->randomFloat(2, 0, 1000),
        'quantity_available' => $this->faker->randomNumber(3),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
