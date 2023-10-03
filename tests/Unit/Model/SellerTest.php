<?php

namespace Tests\Unit;

use App\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $seller = new Seller();
        $this->assertEquals('sellers', $seller->getTable());
    }

    /** @test */
    public function it_uses_seller_id_as_the_primary_key()
    {
        $seller = new Seller();

        $this->assertEquals('seller_id', $seller->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $seller = new Seller();

        $this->assertFalse($seller->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_type()
    {
        $seller = new Seller();
        $this->assertEquals('string', $seller->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $seller = new Seller();
        $hidden = ['id'];
        $this->assertEquals($hidden, $seller->getHidden());
    }

    /** @test */
    public function it_returns_seller_id_for_route_key()
    {
        $seller = new Seller();

        $this->assertEquals('seller_id', $seller->getRouteKeyName());
    }
}
