<?php

namespace Tests\Unit;

use App\Wholesaler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WholesalerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $wholesaler = new Wholesaler();
        $this->assertEquals('wholesalers', $wholesaler->getTable());
    }

    /** @test */
    public function it_uses_wholesaler_id_as_the_primary_key()
    {
        $wholesaler = new Wholesaler();

        $this->assertEquals('wholesaler_id', $wholesaler->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $wholesaler = new Wholesaler();

        $this->assertFalse($wholesaler->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_type()
    {
        $wholesaler = new Wholesaler();
        $this->assertEquals('string', $wholesaler->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $wholesaler = new Wholesaler();
        $hidden = ['id'];
        $this->assertEquals($hidden, $wholesaler->getHidden());
    }

    /** @test */
    public function it_returns_wholesaler_id_for_route_key()
    {
        $wholesaler = new Wholesaler();

        $this->assertEquals('wholesaler_id', $wholesaler->getRouteKeyName());
    }
}
