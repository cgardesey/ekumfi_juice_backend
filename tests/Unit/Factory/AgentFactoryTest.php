<?php

namespace Tests\Unit\Factories;

use App\Agent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AgentFactoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_create_an_agent()
    {
        $attributes = [
            'agent_id' => $this->faker->uuid,
            'confirmation_token' => $this->faker->optional()->uuid,
            'agent_type' => $this->faker->randomElement(['Type A', 'Type B', 'Type C', null]),
            'title' => $this->faker->title,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'other_names' => $this->faker->optional()->name,
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Other', null]),
            'profile_image_url' => $this->faker->optional()->imageUrl(),
            'primary_contact' => $this->faker->phoneNumber,
            'auxiliary_contact' => $this->faker->optional()->phoneNumber,
            'momo_number' => $this->faker->optional()->phoneNumber,
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
            'digital_address' => $this->faker->optional()->streetAddress,
            'street_address' => $this->faker->optional()->streetAddress,
            'identification_type' => $this->faker->optional()->randomElement(['Passport', 'Driver\'s License', 'National ID', null]),
            'identification_number' => $this->faker->optional()->randomNumber(),
            'identification_image_url' => $this->faker->optional()->imageUrl(),
            'availability' => $this->faker->randomElement(['Available', 'Unavailable']),

        ];;

        $agent = factory(Agent::class)->create($attributes);

        $this->assertInstanceOf(Agent::class, $agent);
        $this->assertDatabaseHas('agents', $attributes);
    }
}
