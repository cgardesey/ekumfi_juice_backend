<?php

namespace Tests\Feature\Http\Controllers;

use App\Cart;
use App\CartProduct;
use App\Consumer;
use App\Http\Controllers\CartProductController;
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

class CartProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /*public function testScopedCartProducts()
    {
        $user = factory(User::class)->create();
        $seller_id = factory(Seller::class)->create()->seller_id;
        $consumer_id = factory(Consumer::class)->create()->consumer_id;
        $seller_product_id = factory(SellerProduct::class)->create()->seller_product_id;

        $response = $this->post('/api/carts', [
            'seller_id' => $seller_id,
            'consumer_id' => $consumer_id,
            'seller_product_id' => $seller_product_id,
            'quantity' => 2,
            'price' => 10.99,
        ],
            ['Authorization' => 'Bearer ' . $user->api_token]
        );

        Log::info("resp", $response->json());

        $requestData = [
            'cart_id' => $response->json()['cart']['cart_id'],
            'order_id' => $response->json()['cart']['order_id']
        ];
        $response = $this->post('/api/scoped-cart-products',
            $requestData,
            ['Authorization' => 'Bearer ' . $user->api_token]
        );

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [
                'seller_product_id',
                'unit_quantity',
                'quantity_available',
                'unit_price',
            ]
        ]);
    }*/

    public function testScopedCartTotalCount()
    {
        $user = factory(User::class)->create();
        $response = $this->post('/api/cart-total', [
            'cart_id' => factory(Cart::class)->create()->cart_id
        ],
            ['Authorization' => 'Bearer ' . $user->api_token]
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'cart_total'
        ]);
    }

    public function testUpdate()
    {
        // Create a test CartProduct
        $user = factory(User::class)->create();
        $cartProduct = factory(CartProduct::class)->create();

        // Prepare the update data
        $updatedData = [
            'quantity' => "10",
            'price' => "300",
        ];

        // Perform the update request
        $response = $this->patch("/api/cart-products/{$cartProduct->cart_product_id}", $updatedData,
            ['Authorization' => 'Bearer ' . $user->api_token]);

        // Assert the response has the updated data
        $response->assertStatus(200);
        $response->assertJson($updatedData);

        // Check if the CartProduct was updated in the database
        $this->assertDatabaseHas('cart_products', $updatedData);
    }

    public function testDestroy()
    {
        // Create a test CartProduct
        $user = factory(User::class)->create();
        $cartProduct = factory(CartProduct::class)->create();

        $controller = new CartProductController();

        // Perform the delete request
        $response = $this->delete("/api/cart-products/{$cartProduct->cart_product_id}", [],
            ['Authorization' => 'Bearer ' . $user->api_token]);

        // Assert the response has the status of the delete operation
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true, // Assuming the delete operation was successful
        ]);

        // Check if the CartProduct was deleted from the database
        $this->assertDatabaseMissing('cart_products', [
            'cart_product_id' => $cartProduct->cart_product_id
        ]);
    }

    /**
     * Test show method of CartController.
     *
     * @return void
     */
    public function testShow()
    {
        $cart = factory(Cart::class)->create();

        $user = factory(User::class)->create();
        $response = $this->get('api/carts/' . $cart->cart_id , [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'cart_id' => $cart->cart_id,
            'seller_id' => $cart->seller_id,
            'consumer_id' => $cart->consumer_id
        ]);
    }
}
