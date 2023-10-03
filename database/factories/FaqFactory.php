<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Faq;
use Faker\Generator as Faker;

$factory->define(Faq::class, function (Faker $faker) {
    return [
        'faq_id' => $this->faker->unique()->word,
        'title' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'tag' => $this->faker->word,
    ];
});
