<?php

namespace Tests\Unit;

use App\Agent;
use App\Wholesaler;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WholesalerFactoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /** @test */
    public function it_can_create_a_wholesaler()
    {
        $wholesaler = factory(Wholesaler::class)->create();

        $this->assertInstanceOf(Wholesaler::class, $wholesaler);
        $this->assertDatabaseHas('wholesalers', [
            'wholesaler_id' => $wholesaler->wholesaler_id,
            'confirmation_token' => $wholesaler->confirmation_token,
            'shop_name' => $wholesaler->shop_name,
            'shop_image_url' => $wholesaler->shop_image_url,
            'primary_contact' => $wholesaler->primary_contact,
            'auxiliary_contact' => $wholesaler->auxiliary_contact,
            'momo_number' => $wholesaler->momo_number,
            'longitude' => $wholesaler->longitude,
            'latitude' => $wholesaler->latitude,
            'digital_address' => $wholesaler->digital_address,
            'street_address' => $wholesaler->street_address,
            'identification_type' => $wholesaler->identification_type,
            'identification_number' => $wholesaler->identification_number,
            'identification_image_url' => $wholesaler->identification_image_url,
            'availability' => $wholesaler->availability,
            'verified' => $wholesaler->verified,
            'user_id' => $wholesaler->user_id,
        ]);
    }
}
