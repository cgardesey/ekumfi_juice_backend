<?php

namespace Tests\Unit;

use App\EkumfiInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EkumfiInfoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $ekumfiInfo = new EkumfiInfo();
        $this->assertEquals('ekumfi_infos', $ekumfiInfo->getTable());
    }

    /** @test */
    public function it_uses_ekumfiInfo_id_as_the_primary_key()
    {
        $ekumfiInfo = new EkumfiInfo();

        $this->assertEquals('ekumfi_info_id', $ekumfiInfo->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $ekumfiInfo = new EkumfiInfo();

        $this->assertFalse($ekumfiInfo->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_info()
    {
        $ekumfiInfo = new EkumfiInfo();
        $this->assertEquals('string', $ekumfiInfo->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $ekumfiInfo = new EkumfiInfo();
        $hidden = ['id'];
        $this->assertEquals($hidden, $ekumfiInfo->getHidden());
    }

    /** @test */
    public function it_returns_ekumfiInfo_id_for_route_key()
    {
        $ekumfiInfo = new EkumfiInfo();

        $this->assertEquals('ekumfi_info_id', $ekumfiInfo->getRouteKeyName());
    }
}
