<?php

namespace Tests\Unit;

use App\StockCart;
use App\StockCartProduct;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StockCartProductFactoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /** @test */
    public function it_can_create_a_stock_cart_product()
    {
        $stock_cart_product = factory(StockCartProduct::class)->create();

        $this->assertInstanceOf(StockCartProduct::class, $stock_cart_product);
        $this->assertDatabaseHas('stock_cart_products', [
            'stock_cart_product_id' => $stock_cart_product->stock_cart_product_id,
            'stock_cart_id' => $stock_cart_product->stock_cart_id,
            'agent_product_id' => $stock_cart_product->agent_product_id,
            'quantity' => $stock_cart_product->quantity,
            'price' => $stock_cart_product->price,
        ]);
    }
}
