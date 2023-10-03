<?php

namespace Tests\Unit;

use App\Stock;
use App\StockCart;
use App\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StockCartFactoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /** @test */
    public function it_can_create_a_stock_cart()
    {
        $stock_cart = factory(StockCart::class)->create();

        $this->assertInstanceOf(StockCart::class, $stock_cart);
        $this->assertDatabaseHas('stock_carts', [
            'stock_cart_id' => $stock_cart->stock_cart_id,
            'order_id' => $stock_cart->order_id,
            'agent_id' => $stock_cart->agent_id,
            'seller_id' => $stock_cart->seller_id,
            'shipping_fee' => $stock_cart->shipping_fee,
            'delivered' => $stock_cart->delivered,
            'paid' => $stock_cart->paid,
            'credited' => $stock_cart->credited,
            'credit_paid' => $stock_cart->credit_paid,
        ]);
    }
}
