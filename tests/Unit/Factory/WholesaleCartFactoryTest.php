<?php

namespace Tests\Unit;

use App\Wholesaler;
use App\WholesalerCart;
use App\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WholesalerCartFactoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /** @test */
    public function it_can_create_a_wholesaler_cart()
    {
        $wholesaler_cart = factory(WholesalerCart::class)->create();

        $this->assertInstanceOf(WholesalerCart::class, $wholesaler_cart);
        $this->assertDatabaseHas('wholesaler_carts', [
            'wholesaler_cart_id' => $wholesaler_cart->wholesaler_cart_id,
            'order_id' => $wholesaler_cart->order_id,
            'wholesaler_id' => $wholesaler_cart->wholesaler_id,
            'agent_id' => $wholesaler_cart->agent_id,
            'shipping_fee' => $wholesaler_cart->shipping_fee,
            'delivered' => $wholesaler_cart->delivered,
            'paid' => $wholesaler_cart->paid,
            'credited' => $wholesaler_cart->credited,
            'credit_paid' => $wholesaler_cart->credit_paid,
        ]);
    }
}
