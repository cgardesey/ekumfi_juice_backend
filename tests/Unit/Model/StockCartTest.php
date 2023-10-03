<?php

namespace Tests\Unit;

use App\StockCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockCartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $stockCart = new StockCart();
        $this->assertEquals('stock_carts', $stockCart->getTable());
    }

    /** @test */
    public function it_uses_stockCart_id_as_the_primary_key()
    {
        $stockCart = new StockCart();

        $this->assertEquals('stock_cart_id', $stockCart->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $stockCart = new StockCart();

        $this->assertFalse($stockCart->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_cart()
    {
        $stockCart = new StockCart();
        $this->assertEquals('string', $stockCart->getKeyType());
    }
    /** @test */
    public function it_returns_stockCart_id_for_route_key()
    {
        $stockCart = new StockCart();

        $this->assertEquals('stock_cart_id', $stockCart->getRouteKeyName());
    }
}
