<?php

namespace Tests\Unit\Factories;

use App\Banner;
use App\User;
use App\Wholesaler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BannerFactoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_create_a_Banner()
    {
        $attributes = [
            'banner_id' => $this->faker->unique()->uuid,
            'title' => $this->faker->sentence,
            'url' => $this->faker->url,
        ];

        $Banner = factory(Banner::class)->create($attributes);

        $this->assertInstanceOf(Banner::class, $Banner);
        $this->assertDatabaseHas('banners', $attributes);
    }
}
