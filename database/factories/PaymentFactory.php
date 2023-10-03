<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cart;
use App\Payment;
use App\StockCart;
use App\WholesalerCart;
use Faker\Generator as Faker;

$factory->define(Payment::class, function (Faker $faker) {
    return [
        'payment_id' => $this->faker->unique()->uuid,
        'msisdn' => $this->faker->phoneNumber,
        'country_code' => $this->faker->countryCode,
        'network' => $this->faker->word,
        'currency' => $this->faker->currencyCode,
        'amount' => $this->faker->randomFloat(2, 0, 999999.99),
        'description' => $this->faker->text,
        'payment_ref' => $this->faker->text,
        'message' => $this->faker->sentence,
        'response_message' => $this->faker->sentence,
        'status' => $this->faker->randomElement(['pending', 'success', 'failure']),
        'external_reference_no' => $this->faker->text,
        'transaction_status_reason' => $this->faker->sentence,
        'cart_id' => function () {
            return factory(Cart::class)->create()->cart_id;
        },
        'stock_cart_id' => function () {
            return factory(StockCart::class)->create()->stock_cart_id;
        },
        'wholesaler_cart_id' => function () {
            return factory(WholesalerCart::class)->create()->wholesaler_cart_id;
        },
        'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
    ];
});
