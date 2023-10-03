<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Agent;
use App\Chat;
use App\Consumer;
use App\EkumfiInfo;
use App\Seller;
use App\Wholesaler;
use Faker\Generator as Faker;

$factory->define(Chat::class, function (Faker $faker) {
    return [
        'chat_id' => $faker->unique()->uuid,
        'chat_ref_id' => $faker->uuid,
        'text' => $faker->sentence,
        'link' => $faker->url,
        'link_title' => $faker->sentence,
        'link_description' => $faker->paragraph,
        'link_image' => $faker->imageUrl(),
        'attachment_url' => $faker->url,
        'attachment_type' => $faker->randomElement(['image', 'video', 'document']),
        'attachment_title' => $faker->sentence,
        'read_by_recipient' => $faker->boolean,
        'sent_by_consumer' => $faker->boolean,
        'sender_role' => $faker->randomElement(['user', 'admin', 'agent']),
        'tag' => $faker->word,
        'consumer_id' => function () {
            return factory(Consumer::class)->create()->consumer_id;
        },
        'seller_id' => function () {
            return factory(Seller::class)->create()->seller_id;
        },
        'agent_id' => function () {
            return factory(Agent::class)->create()->agent_id;
        },
        'wholesaler_id' => function () {
            return factory(Wholesaler::class)->create()->wholesaler_id;
        },
        'ekumfi_info_id' => function () {
            return factory(EkumfiInfo::class)->create()->ekumfi_info_id;
        },
        'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
        'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
    ];
});
