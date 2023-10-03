<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Seller;
use App\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SellerFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_seller()
    {
        $seller = factory(Seller::class)->create();

        $this->assertInstanceOf(Seller::class, $seller);
        $this->assertDatabaseHas('sellers', [
            'seller_id' => $seller->seller_id,
            'confirmation_token' => $seller->confirmation_token,
            'seller_type' => $seller->seller_type,
            'shop_name' => $seller->shop_name,
            'shop_image_url' => $seller->shop_image_url,
            'primary_contact' => $seller->primary_contact,
            'auxiliary_contact' => $seller->auxiliary_contact,
            'momo_number' => $seller->momo_number,
            'longitude' => $seller->longitude,
            'latitude' => $seller->latitude,
            'digital_address' => $seller->digital_address,
            'street_address' => $seller->street_address,
            'identification_type' => $seller->identification_type,
            'identification_number' => $seller->identification_number,
            'identification_image_url' => $seller->identification_image_url,
            'availability' => $seller->availability,
            'verified' => $seller->verified,
            'user_id' => $seller->user_id,
            'agent_id' => $seller->agent_id,
        ]);
    }
}
