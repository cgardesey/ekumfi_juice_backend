<?php

namespace Tests\Unit;

use App\AgentProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $agentProduct = new AgentProduct();
        $this->assertEquals('agent_products', $agentProduct->getTable());
    }

    /** @test */
    public function it_uses_agentProduct_id_as_the_primary_key()
    {
        $agentProduct = new AgentProduct();

        $this->assertEquals('agent_product_id', $agentProduct->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $agentProduct = new AgentProduct();

        $this->assertFalse($agentProduct->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_product()
    {
        $agentProduct = new AgentProduct();
        $this->assertEquals('string', $agentProduct->getKeyType());
    }

    /** @test */
    public function it_returns_agentProduct_id_for_route_key()
    {
        $agentProduct = new AgentProduct();

        $this->assertEquals('agent_product_id', $agentProduct->getRouteKeyName());
    }
}
