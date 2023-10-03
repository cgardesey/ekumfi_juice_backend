<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Cart;
use App\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_cart()
    {
        $cart = factory(Cart::class)->create();

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertDatabaseHas('carts', [
            'cart_id' => $cart->cart_id,
            'order_id' => $cart->order_id,
            'seller_id' => $cart->seller_id,
            'consumer_id' => $cart->consumer_id,
            'shipping_fee' => $cart->shipping_fee,
            'delivered' => $cart->delivered,
        ]);
    }
}
