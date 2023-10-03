<?php

namespace Tests\Unit;

use App\Seller;
use App\User;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SellerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the show method of SellerController.
     *
     * @return void
     */
    public function testShow()
    {
        $seller = factory(Seller::class)->create();

        $user = factory(User::class)->create();
        $response = $this->get('api/sellers/' . $seller->seller_id , [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        Log::info('message', [$response]);

        $response->assertJsonFragment([
            'shop_image_url' => $seller->shop_image_url,
            'shop_name' => $seller->shop_name,
            'primary_contact' => $seller->primary_contact,
            'auxiliary_contact' => $seller->auxiliary_contact
        ]);
    }

    /*public function testStoreMethod()
    {
        $this->assertTrue(true);
    }*/

    public function testUpdate()
    {
        $seller = factory(Seller::class)->create();

        $requestData = factory(Seller::class)->make()->toArray();

        $user = factory(User::class)->create();

        $response = $this->post('/api/sellers/' . $seller->seller_id, [
            'shop_name' => $requestData['shop_name'],
            'primary_contact' => $requestData['primary_contact'],
            'longitude' => $requestData['longitude'],
            'latitude' => $requestData['latitude']
        ], [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'shop_name' => $requestData['shop_name'],
            'primary_contact' => $requestData['primary_contact'],
        ]);

        $seller->refresh();

        $this->assertDatabaseHas('sellers', [
            'shop_name' => $requestData['shop_name'],
            'primary_contact' => $requestData['primary_contact']
        ]);
    }
}
