<?php

namespace Tests\Unit;

use App\Banner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BannerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $banner = new Banner();
        $this->assertEquals('banners', $banner->getTable());
    }

    /** @test */
    public function it_uses_banner_id_as_the_primary_key()
    {
        $banner = new Banner();

        $this->assertEquals('banner_id', $banner->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $banner = new Banner();

        $this->assertFalse($banner->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_type()
    {
        $banner = new Banner();
        $this->assertEquals('string', $banner->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $banner = new Banner();
        $hidden = ['id'];
        $this->assertEquals($hidden, $banner->getHidden());
    }

    /** @test */
    public function it_returns_banner_id_for_route_key()
    {
        $banner = new Banner();

        $this->assertEquals('banner_id', $banner->getRouteKeyName());
    }
}
