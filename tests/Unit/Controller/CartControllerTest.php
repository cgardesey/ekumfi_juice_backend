<?php

use App\Consumer;
use App\Seller;
use App\SellerProduct;
use Tests\TestCase;
use App\Http\Controllers\CartController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\User;
use App\Cart;
use App\CartProduct;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the store method.
     */
    public function testStore()
    {
        $user = factory(User::class)->create();
        $seller_id = factory(Seller::class)->create()->seller_id;
        $consumer_id = factory(Consumer::class)->create()->consumer_id;
        $seller_product_id = factory(SellerProduct::class)->create()->seller_product_id;
        $quantity = 2;
        $price = 10.99;
        $response = $this->addToCart($seller_id, $consumer_id, $seller_product_id, $user,$quantity, $price);
        $response->assertStatus(200);

        $response->assertJsonFragment([
            'success' => true
        ]);

        $this->assertDatabaseHas('carts', [
            'seller_id' => $seller_id,
            'consumer_id' => $consumer_id
        ]);

        $this->assertDatabaseHas('cart_products', [
            'seller_product_id' => $seller_product_id,
            'quantity' => $quantity,
            'price' => $price,
        ]);
    }

    /**
     * Test show method of CartController.
     *
     * @return void
     */
    public function testShow()
    {/**/
        $cart = factory(Cart::class)->create();

        $user = factory(User::class)->create();
        $response = $this->get('api/carts/' . $cart->cart_id , [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'cart_id' => $cart->cart_id,
            'order_id' => $cart->order_id,
            'seller_id' => $cart->seller_id
        ]);
    }

    public function testUpdate()
    {
        $cart = factory(Cart::class)->create();
        $cart->makeVisible('cart_id');

        $requestData = factory(Cart::class)->make()->toArray();

        $user = factory(User::class)->create();

        $response = $this->patch('/api/carts/' . $cart->cart_id, $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'order_id' => $requestData['order_id'],
            'seller_id' => $requestData['seller_id'],
        ]);

        $cart->refresh();

        $this->assertDatabaseHas('carts', [
            'order_id' => $requestData['order_id'],
            'seller_id' => $requestData['seller_id'],
        ]);
    }

    /**
     * Test the scopedCarts method.
     */
    public function testScopedConsumerCarts()
    {
        $user = factory(User::class)->create();
        $seller_id = factory(Seller::class)->create()->seller_id;
        $consumer_id = factory(Consumer::class)->create()->consumer_id;
        $seller_product_id = factory(SellerProduct::class)->create()->seller_product_id;

        $quantity = 2;
        $price = 10.99;

        $this->addToCart($seller_id, $consumer_id, $seller_product_id, $user,$quantity, $price);

        $requestData = ['consumer_id' => $consumer_id];
        $response = $this->post('/api/scoped-consumer-carts',
            $requestData,
            ['Authorization' => 'Bearer ' . $user->api_token]
        );

        $response->assertStatus(200);

//        $response->assertJsonFragment($requestData);
    }

    /**
     * Test the scopedCarts method.
     */
    public function testScopedSellerCarts()
    {
        $user = factory(User::class)->create();
        $seller_id = factory(Seller::class)->create()->seller_id;
        $consumer_id = factory(Consumer::class)->create()->consumer_id;
        $seller_product_id = factory(SellerProduct::class)->create()->seller_product_id;

        $quantity = 2;
        $price = 10.99;

        $this->addToCart($seller_id, $consumer_id, $seller_product_id, $user,$quantity, $price);

        $requestData = ['seller_id' => $seller_id];
        $response = $this->post('/api/scoped-seller-carts',
            $requestData,
            ['Authorization' => 'Bearer ' . $user->api_token]
        );

        $response->assertStatus(200);

//        $response->assertJsonFragment($requestData);
    }

    private function addToCart($seller_id, $consumer_id, $seller_product_id, $user,$quantity, $price): \Illuminate\Foundation\Testing\TestResponse
    {
        return $this->post('/api/carts', [
            'seller_id' => $seller_id,
            'consumer_id' => $consumer_id,
            'seller_product_id' => $seller_product_id,
            'quantity' => $quantity,
            'price' => $price,
        ],
            ['Authorization' => 'Bearer ' . $user->api_token]
        );
    }
}
