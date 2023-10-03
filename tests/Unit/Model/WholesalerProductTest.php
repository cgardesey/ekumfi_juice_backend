<?php

namespace Tests\Unit;

use App\WholesalerProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WholesalerProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $wholesalerProduct = new WholesalerProduct();
        $this->assertEquals('wholesaler_products', $wholesalerProduct->getTable());
    }

    /** @test */
    public function it_uses_wholesalerProduct_id_as_the_primary_key()
    {
        $wholesalerProduct = new WholesalerProduct();

        $this->assertEquals('wholesaler_product_id', $wholesalerProduct->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $wholesalerProduct = new WholesalerProduct();

        $this->assertFalse($wholesalerProduct->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_product()
    {
        $wholesalerProduct = new WholesalerProduct();
        $this->assertEquals('string', $wholesalerProduct->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $wholesalerProduct = new WholesalerProduct();
        $hidden = ['id'];
        $this->assertEquals($hidden, $wholesalerProduct->getHidden());
    }

    /** @test */
    public function it_returns_wholesalerProduct_id_for_route_key()
    {
        $wholesalerProduct = new WholesalerProduct();

        $this->assertEquals('wholesaler_product_id', $wholesalerProduct->getRouteKeyName());
    }
}
