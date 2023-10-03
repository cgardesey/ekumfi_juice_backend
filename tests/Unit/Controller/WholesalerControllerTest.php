<?php

namespace Tests\Unit;

use App\Wholesaler;
use App\User;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WholesalerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the show method of WholesalerController.
     *
     * @return void
     */
    public function testShow()
    {
        $wholesaler = factory(Wholesaler::class)->create();

        $user = factory(User::class)->create();
        $response = $this->get('api/wholesalers/' . $wholesaler->wholesaler_id , [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        Log::info('message', [$response]);

        $response->assertJsonFragment([
            'shop_image_url' => $wholesaler->shop_image_url,
            'shop_name' => $wholesaler->shop_name,
            'primary_contact' => $wholesaler->primary_contact,
            'auxiliary_contact' => $wholesaler->auxiliary_contact
        ]);
    }

    /*public function testStoreMethod()
    {
        $this->assertTrue(true);
    }*/

    public function testUpdate()
    {
        $wholesaler = factory(Wholesaler::class)->create();

        $requestData = factory(Wholesaler::class)->make()->toArray();

        $user = factory(User::class)->create();

        $response = $this->post('/api/wholesalers/' . $wholesaler->wholesaler_id, [
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
            'primary_contact' => $requestData['primary_contact']
        ]);

        $wholesaler->refresh();

        $this->assertDatabaseHas('wholesalers', [
            'shop_name' => $requestData['shop_name'],
            'primary_contact' => $requestData['primary_contact']
        ]);
    }
}
