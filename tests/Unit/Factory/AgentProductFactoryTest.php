<?php

namespace Tests\Unit;

use App\Agent;
use App\AgentProduct;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AgentProductFactoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $agent_product = factory(AgentProduct::class)->create();

        $this->assertInstanceOf(AgentProduct::class, $agent_product);
        $this->assertDatabaseHas('agent_products', [
            'agent_product_id' => $agent_product->agent_product_id,
            'product_id' => $agent_product->product_id,
            'agent_id' => $agent_product->agent_id,
            'unit_quantity' => $agent_product->unit_quantity,
            'unit_price' => $agent_product->unit_price,
            'quantity_available' => $agent_product->quantity_available,
            'product_id' => $agent_product->product_id,
            'agent_id' => $agent_product->agent_id
        ]);
    }
}
