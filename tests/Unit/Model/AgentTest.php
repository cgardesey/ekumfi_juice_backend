<?php

namespace Tests\Unit;

use App\Agent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $agent = new Agent();
        $this->assertEquals('agents', $agent->getTable());
    }

    /** @test */
    public function it_uses_agent_id_as_the_primary_key()
    {
        $agent = new Agent();

        $this->assertEquals('agent_id', $agent->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $agent = new Agent();

        $this->assertFalse($agent->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_type()
    {
        $agent = new Agent();
        $this->assertEquals('string', $agent->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $agent = new Agent();
        $hidden = ['id'];
        $this->assertEquals($hidden, $agent->getHidden());
    }

    /** @test */
    public function it_returns_agent_id_for_route_key()
    {
        $agent = new Agent();

        $this->assertEquals('agent_id', $agent->getRouteKeyName());
    }
}
