<?php

namespace Tests\Unit;

use App\StockCartProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockCartProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $stockCartProduct = new StockCartProduct();
        $this->assertEquals('stock_cart_products', $stockCartProduct->getTable());
    }

    /** @test */
    public function it_uses_stockCartProduct_id_as_the_primary_key()
    {
        $stockCartProduct = new StockCartProduct();

        $this->assertEquals('stock_cart_product_id', $stockCartProduct->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $stockCartProduct = new StockCartProduct();

        $this->assertFalse($stockCartProduct->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_cart_product()
    {
        $stockCartProduct = new StockCartProduct();
        $this->assertEquals('string', $stockCartProduct->getKeyType());
    }

    /** @test */
    public function it_returns_stockCartProduct_id_for_route_key()
    {
        $stockCartProduct = new StockCartProduct();

        $this->assertEquals('stock_cart_product_id', $stockCartProduct->getRouteKeyName());
    }
}
