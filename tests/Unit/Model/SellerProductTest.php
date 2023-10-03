<?php

namespace Tests\Unit;

use App\SellerProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $sellerProduct = new SellerProduct();
        $this->assertEquals('seller_products', $sellerProduct->getTable());
    }

    /** @test */
    public function it_uses_sellerProduct_id_as_the_primary_key()
    {
        $sellerProduct = new SellerProduct();

        $this->assertEquals('seller_product_id', $sellerProduct->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $sellerProduct = new SellerProduct();

        $this->assertFalse($sellerProduct->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_product()
    {
        $sellerProduct = new SellerProduct();
        $this->assertEquals('string', $sellerProduct->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $sellerProduct = new SellerProduct();
        $hidden = ['id'];
        $this->assertEquals($hidden, $sellerProduct->getHidden());
    }

    /** @test */
    public function it_returns_sellerProduct_id_for_route_key()
    {
        $sellerProduct = new SellerProduct();

        $this->assertEquals('seller_product_id', $sellerProduct->getRouteKeyName());
    }
}
