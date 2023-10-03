<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Consumer;
use App\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConsumerFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_consumer()
    {
        $consumer = factory(Consumer::class)->create();

        $this->assertInstanceOf(Consumer::class, $consumer);
        $this->assertDatabaseHas('consumers', [
            'consumer_id' => $consumer->consumer_id,
            'confirmation_token' => $consumer->confirmation_token,
            'name' => $consumer->name,
            'profile_image_url' => $consumer->profile_image_url,
            'gender' => $consumer->gender,
            'employment_category' => $consumer->employment_category,
            'primary_contact' => $consumer->primary_contact,
            'auxiliary_contact' => $consumer->auxiliary_contact,
            'longitude' => $consumer->longitude,
            'latitude' => $consumer->latitude,
            'digital_address' => $consumer->digital_address,
            'street_address' => $consumer->street_address,
            'user_id' => $consumer->user_id,

        ]);
    }
}
