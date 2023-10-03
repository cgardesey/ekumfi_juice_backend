<?php

namespace Tests\Unit;

use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $product = new Product();
        $this->assertEquals('products', $product->getTable());
    }

    /** @test */
    public function it_uses_product_id_as_the_primary_key()
    {
        $product = new Product();

        $this->assertEquals('product_id', $product->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $product = new Product();

        $this->assertFalse($product->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_type()
    {
        $product = new Product();
        $this->assertEquals('string', $product->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $product = new Product();
        $hidden = ['id'];
        $this->assertEquals($hidden, $product->getHidden());
    }

    /** @test */
    public function it_returns_product_id_for_route_key()
    {
        $product = new Product();

        $this->assertEquals('product_id', $product->getRouteKeyName());
    }
}
