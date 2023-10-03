<?php

namespace Tests\Unit;

use App\Agent;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test the show method of AgentController.
     *
     * @return void
     */
    public function testShow()
    {
        $agent = factory(Agent::class)->create();

        $user = factory(User::class)->create();
        $response = $this->get('api/agents/' . $agent->agent_id , [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'gender' => $agent->gender,
            'primary_contact' => $agent->primary_contact,
            'auxiliary_contact' => $agent->auxiliary_contact,
        ]);
    }

    public function testStoreMethod()
    {
        // Disable exception handling to see the detailed error messages, if any.
        $this->withoutExceptionHandling();


        $requestData = factory(Agent::class)->make()->toArray();

        $user = factory(User::class)->create();
        $response = $this->post('/api/agents', $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        // Assert that the response is successful (status code 200)
        $response->assertOk();

        // Assert that the response contains the expected data
        $response->assertJsonStructure([
            'agent' => ['agent_id'],
            'banners' => [],
        ]);

        // Assert that the agent has been created in the database
        $this->assertDatabaseHas('agents', [
            'primary_contact' => $requestData['primary_contact'],
        ]);
    }

    public function testUpdate()
    {
        $agent = factory(Agent::class)->create();

        $requestData = factory(Agent::class)->make()->toArray();

        $user = factory(User::class)->create();

        $data = [
            'primary_contact' => $requestData['primary_contact'],
            'longitude' => $requestData['longitude'],
            'latitude' => $requestData['latitude']
        ];
        $response = $this->post('/api/agents/' . $agent->agent_id, $data, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'primary_contact' => $requestData['primary_contact']
        ]);

        $agent->refresh();

        $this->assertDatabaseHas('agents', [
            'primary_contact' => $requestData['primary_contact']
        ]);
    }
}
