<?php

namespace Tests\Unit;

use App\Consumer;
use App\User;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use App\Http\Controllers\ConsumerController;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConsumerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the show method of ConsumerController.
     *
     * @return void
     */
    public function testShow()
    {
        $consumer = factory(Consumer::class)->create();

        $user = factory(User::class)->create();
        $response = $this->get('api/consumers/' . $consumer->consumer_id , [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'profile_image_url' => $consumer->profile_image_url,
            'name' => $consumer->name,
            'gender' => $consumer->gender,
            'primary_contact' => $consumer->primary_contact
        ]);
    }

    public function testStoreMethod()
    {
        // Disable exception handling to see the detailed error messages, if any.
        $this->withoutExceptionHandling();


        $requestData = factory(Consumer::class)->make()->toArray();

        $user = factory(User::class)->create();
        $response = $this->post('/api/consumers', $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        // Assert that the response is successful (status code 200)
        $response->assertOk();

        // Assert that the response contains the expected data
        $response->assertJsonStructure([
            'consumer' => ['consumer_id'],
            'banners' => []
        ]);

        // Assert that the consumer has been created in the database
        $this->assertDatabaseHas('consumers', [
            'name' => $requestData['name'],
        ]);
    }

    public function testUpdate()
    {
        $consumer = factory(Consumer::class)->create();

        $requestData = factory(Consumer::class)->make()->toArray();

        $user = factory(User::class)->create();

        $response = $this->post('/api/consumers/' . $consumer->consumer_id, [
            'name' => $requestData['name'],
            'primary_contact' => $requestData['primary_contact'],
            'longitude' => $requestData['longitude'],
            'latitude' => $requestData['latitude']
        ], [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name' => $requestData['name'],
            'primary_contact' => $requestData['primary_contact']
        ]);

        $consumer->refresh();

        $this->assertDatabaseHas('consumers', [
            'name' => $requestData['name'],
            'primary_contact' => $requestData['primary_contact']
        ]);
    }
}
