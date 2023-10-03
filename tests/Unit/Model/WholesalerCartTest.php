<?php

namespace Tests\Unit;

use App\WholesalerCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WholesalerCartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $wholesalerCart = new WholesalerCart();
        $this->assertEquals('wholesaler_carts', $wholesalerCart->getTable());
    }

    /** @test */
    public function it_uses_wholesalerCart_id_as_the_primary_key()
    {
        $wholesalerCart = new WholesalerCart();

        $this->assertEquals('wholesaler_cart_id', $wholesalerCart->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $wholesalerCart = new WholesalerCart();

        $this->assertFalse($wholesalerCart->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_cart()
    {
        $wholesalerCart = new WholesalerCart();
        $this->assertEquals('string', $wholesalerCart->getKeyType());
    }


    /** @test */
    public function it_returns_wholesalerCart_id_for_route_key()
    {
        $wholesalerCart = new WholesalerCart();

        $this->assertEquals('wholesaler_cart_id', $wholesalerCart->getRouteKeyName());
    }
}
