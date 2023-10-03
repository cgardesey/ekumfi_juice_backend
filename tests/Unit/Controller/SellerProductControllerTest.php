<?php

namespace Tests\Unit;

use App\Product;
use App\SellerProduct;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerProductControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test update method of ProductController.
     *
     * @return void
     */
    public function testUpdate()
    {
        $seller_product = factory(SellerProduct::class)->create();
        $seller_product->makeVisible('seller_product_id');

        $requestData = factory(SellerProduct::class)->make()->toArray();

        $user = factory(User::class)->create();

        $response = $this->patch('/api/seller-products/' . $seller_product->seller_product_id, $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'product_id' => $requestData['product_id']
        ]);

        $seller_product->refresh();

        $this->assertDatabaseHas('products', [
            'product_id' => $requestData['product_id']
        ]);
    }

    public function testDestroy()
    {
        $seller_product = factory(SellerProduct::class)->create();

        $user = factory(User::class)->create();
        $response = $this->delete('/api/seller-products/' . $seller_product->seller_product_id, [], [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        // Assert the response status is 200 OK
        $response->assertStatus(200);

        // Assert the JSON response contains the correct status value
        $response->assertJson([
            'status' => true
        ]);

        // Assert the product is deleted from the database
        $this->assertDeleted($seller_product);
    }
}
