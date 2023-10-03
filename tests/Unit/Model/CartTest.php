<?php

namespace Tests\Unit;

use App\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $cart = new Cart();
        $this->assertEquals('carts', $cart->getTable());
    }

    /** @test */
    public function it_uses_cart_id_as_the_primary_key()
    {
        $cart = new Cart();

        $this->assertEquals('cart_id', $cart->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $cart = new Cart();

        $this->assertFalse($cart->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_type()
    {
        $cart = new Cart();
        $this->assertEquals('string', $cart->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $cart = new Cart();
        $hidden = ['id'];
        $this->assertEquals($hidden, $cart->getHidden());
    }

    /** @test */
    public function it_returns_cart_id_for_route_key()
    {
        $cart = new Cart();

        $this->assertEquals('cart_id', $cart->getRouteKeyName());
    }
}
