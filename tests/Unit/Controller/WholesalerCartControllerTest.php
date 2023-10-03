<?php

use App\Consumer;
use App\Agent;
use App\AgentProduct;
use App\Wholesaler;
use App\WholesalerCart;
use App\wholesalerProduct;
use Tests\TestCase;
use App\Http\Controllers\CartController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\User;
use App\Cart;
use App\CartProduct;

class WholesalerCartControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the store method.
     */
    /*public function testStore()
    {
        $user = factory(User::class)->create();
        $agent_id = factory(Agent::class)->create()->agent_id;
        $wholesaler_id = factory(Wholesaler::class)->create()->wholesaler_id;
        $wholesaler_product_id = factory(WholesalerProduct::class)->create()->wholesaler_product_id;
        $quantity = 2;
        $price = 10.99;
        $response = $this->addToCart($agent_id, $wholesaler_id, $wholesaler_product_id, $user,$quantity, $price);
        $response->assertStatus(200);

        $response->assertJsonFragment([
            'success' => true,
            'agent_id' => $agent_id,
            'wholesaler_id' => $wholesaler_id,
        ]);

        $this->assertDatabaseHas('wholesaler_carts', [
            'agent_id' => $agent_id,
            'wholesaler_id' => $wholesaler_id,
        ]);

        $this->assertDatabaseHas('cart_products', [
            'agent_product_id' => $wholesaler_product_id,
            'quantity' => $quantity,
            'price' => $price,
        ]);
    }*/

    /**
     * Test show method of CartController.
     *
     * @return void
     */
    public function testShow()
    {/**/
        $wholesalerCart = factory(WholesalerCart::class)->create();

        $user = factory(User::class)->create();
        $response = $this->get('api/wholesaler-carts/' . $wholesalerCart->wholesaler_cart_id , [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'wholesaler_cart_id' => $wholesalerCart->wholesaler_cart_id,
            'order_id' => $wholesalerCart->order_id,
            'agent_id' => $wholesalerCart->agent_id
        ]);
    }

    public function testUpdate()
    {
        $wholesalerCart = factory(WholesalerCart::class)->create();
        $wholesalerCart->makeVisible('wholesaler_cart_id');

        $requestData = factory(WholesalerCart::class)->make()->toArray();

        $user = factory(User::class)->create();

        $response = $this->patch('/api/wholesaler-carts/' . $wholesalerCart->wholesaler_cart_id, $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'order_id' => $requestData['order_id'],
            'agent_id' => $requestData['agent_id'],
        ]);

        $wholesalerCart->refresh();

        $this->assertDatabaseHas('wholesaler_carts', [
            'order_id' => $requestData['order_id'],
            'agent_id' => $requestData['agent_id'],
        ]);
    }

    /**
     * Test the wholesalerCart method.
     */
    /*public function testWholesalerCart()
    {
        $user = factory(User::class)->create();
        $agent_id = factory(agent::class)->create()->agent_id;
        $consumer_id = factory(Consumer::class)->create()->consumer_id;
        $agent_product_id = factory(AgentProduct::class)->create()->agent_product_id;

        $quantity = 2;
        $price = 10.99;

        $this->addToCart($agent_id, $consumer_id, $agent_product_id, $user,$quantity, $price);

        $requestData = ['consumer_id' => $consumer_id];
        $response = $this->post('/api/scoped-consumer-carts',
            $requestData,
            ['Authorization' => 'Bearer ' . $user->api_token]
        );

        $response->assertStatus(200);

        $response->assertJsonFragment($requestData);
    }*/

    private function addToCart($agent_id, $consumer_id, $agent_product_id, $user,$quantity, $price): \Illuminate\Foundation\Testing\TestResponse
    {
        return $this->post('/api/carts', [
            'agent_id' => $agent_id,
            'consumer_id' => $consumer_id,
            'agent_product_id' => $agent_product_id,
            'quantity' => $quantity,
            'price' => $price,
        ],
            ['Authorization' => 'Bearer ' . $user->api_token]
        );
    }
}
