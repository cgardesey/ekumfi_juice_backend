<?php

namespace Tests\Unit;

use App\CartProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $cartProduct = new CartProduct();
        $this->assertEquals('cart_products', $cartProduct->getTable());
    }

    /** @test */
    public function it_uses_cartProduct_id_as_the_primary_key()
    {
        $cartProduct = new CartProduct();

        $this->assertEquals('cart_product_id', $cartProduct->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $cartProduct = new CartProduct();

        $this->assertFalse($cartProduct->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_product()
    {
        $cartProduct = new CartProduct();
        $this->assertEquals('string', $cartProduct->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $cartProduct = new CartProduct();
        $hidden = ['id'];
        $this->assertEquals($hidden, $cartProduct->getHidden());
    }

    /** @test */
    public function it_returns_cartProduct_id_for_route_key()
    {
        $cartProduct = new CartProduct();

        $this->assertEquals('cart_product_id', $cartProduct->getRouteKeyName());
    }
}
