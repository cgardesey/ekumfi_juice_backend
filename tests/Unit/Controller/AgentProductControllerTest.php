<?php

namespace Tests\Unit;

use App\Product;
use App\AgentProduct;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentProductControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test update method of ProductController.
     *
     * @return void
     */
    public function testUpdate()
    {
        $agent_product = factory(AgentProduct::class)->create();
        $agent_product->makeVisible('agent_product_id');

        $requestData = factory(AgentProduct::class)->make()->toArray();

        $user = factory(User::class)->create();

        $response = $this->patch('/api/agent-products/' . $agent_product->agent_product_id, $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'product_id' => $requestData['product_id']
        ]);

        $agent_product->refresh();

        $this->assertDatabaseHas('products', [
            'product_id' => $requestData['product_id']
        ]);
    }

    public function testDestroy()
    {
        $agent_product = factory(AgentProduct::class)->create();

        $user = factory(User::class)->create();
        $response = $this->delete('/api/agent-products/' . $agent_product->agent_product_id, [], [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        // Assert the response status is 200 OK
        $response->assertStatus(200);

        // Assert the JSON response contains the correct status value
        $response->assertJson([
            'status' => true
        ]);

        // Assert the product is deleted from the database
        $this->assertDeleted($agent_product);
    }
}
