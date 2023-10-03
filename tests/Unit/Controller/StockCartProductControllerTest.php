<?php

namespace Tests\Feature\Http\Controllers;

use App\Cart;
use App\StockCartProduct;
use App\Consumer;
use App\Http\Controllers\StockCartProductController;
use App\Product;
use App\Seller;
use App\SellerProduct;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Tests\TestCase;

class StockCartProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUpdate()
    {
        // Create a test StockCartProduct
        $user = factory(User::class)->create();
        $stockCartProduct = factory(StockCartProduct::class)->create();

        // Prepare the update data
        $updatedData = [
            'quantity' => "10",
            'price' => "300",
        ];

        // Perform the update request
        $response = $this->patch("/api/stock-cart-products/{$stockCartProduct->stock_cart_product_id}", $updatedData,
            ['Authorization' => 'Bearer ' . $user->api_token]);

        // Assert the response has the updated data
        $response->assertStatus(200);
        $response->assertJson($updatedData);

        // Check if the StockCartProduct was updated in the database
        $this->assertDatabaseHas('stock_cart_products', $updatedData);
    }

    public function testDestroy()
    {
        // Create a test StockCartProduct
        $user = factory(User::class)->create();
        $stockCartProduct = factory(StockCartProduct::class)->create();

        // Perform the delete request
        $response = $this->delete("/api/stock-cart-products/{$stockCartProduct->stock_cart_product_id}", [],
            ['Authorization' => 'Bearer ' . $user->api_token]);

        // Assert the response has the status of the delete operation
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true, // Assuming the delete operation was successful
        ]);

        // Check if the StockCartProduct was deleted from the database
        $this->assertDatabaseMissing('stock_cart_products', [
            'stock_cart_product_id' => $stockCartProduct->stock_cart_product_id
        ]);
    }
}
