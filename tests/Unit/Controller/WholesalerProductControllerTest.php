<?php

namespace Tests\Unit;

use App\Product;
use App\WholesalerProduct;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WholesalerProductControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test update method of ProductController.
     *
     * @return void
     */
    public function testUpdate()
    {
        $wholesaler_product = factory(WholesalerProduct::class)->create();
        $wholesaler_product->makeVisible('wholesaler_product_id');

        $requestData = factory(WholesalerProduct::class)->make()->toArray();

        $user = factory(User::class)->create();

        $response = $this->patch('/api/wholesaler-products/' . $wholesaler_product->wholesaler_product_id, $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'product_id' => $requestData['product_id']
        ]);

        $wholesaler_product->refresh();

        $this->assertDatabaseHas('products', [
            'product_id' => $requestData['product_id']
        ]);
    }

    public function testDestroy()
    {
        $wholesaler_product = factory(WholesalerProduct::class)->create();

        $user = factory(User::class)->create();
        $response = $this->delete('/api/wholesaler-products/' . $wholesaler_product->wholesaler_product_id, [], [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        // Assert the response status is 200 OK
        $response->assertStatus(200);

        // Assert the JSON response contains the correct status value
        $response->assertJson([
            'status' => true
        ]);

        // Assert the product is deleted from the database
        $this->assertDeleted($wholesaler_product);
    }
}
