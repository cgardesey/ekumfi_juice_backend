<?php

namespace Tests\Unit;

use App\EkumfiInfo;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EkumfiInfoControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test the show method of EkumfiInfoController.
     *
     * @return void
     */
    public function testShow()
    {
        $ekumfi_info = factory(EkumfiInfo::class)->create();

        $user = factory(User::class)->create();
        $response = $this->get('api/ekumfi-infos/' . $ekumfi_info->ekumfi_info_id , [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'primary_contact' => $ekumfi_info->primary_contact,
            'auxiliary_contact' => $ekumfi_info->auxiliary_contact,
        ]);
    }

    public function testStoreMethod()
    {
        // Disable exception handling to see the detailed error messages, if any.
        $this->withoutExceptionHandling();


        $requestData = factory(EkumfiInfo::class)->make()->toArray();

        $user = factory(User::class)->create();
        $response = $this->post('/api/ekumfi-infos', $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        // Assert that the response is successful (status code 200)
        $response->assertOk();

        // Assert that the response contains the expected data
        $response->assertJsonStructure([
            'ekumfi_info' => ['ekumfi_info_id'],
            'banners' => [],
        ]);

        // Assert that the ekumfiInfo has been created in the database
        $this->assertDatabaseHas('ekumfi_infos', [
            'primary_contact' => $requestData['primary_contact'],
        ]);
    }

    public function testUpdate()
    {
        $ekumfi_info = factory(EkumfiInfo::class)->create();

        $requestData = factory(EkumfiInfo::class)->make()->toArray();

        $user = factory(User::class)->create();

        $data = [
            'primary_contact' => $requestData['primary_contact']
        ];
        $response = $this->post('/api/ekumfi-infos/' . $ekumfi_info->ekumfi_info_id, [
            'primary_contact' => $requestData['primary_contact'],
            'longitude' => $requestData['longitude'],
            'latitude' => $requestData['latitude']
        ], [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'primary_contact' => $requestData['primary_contact']
        ]);

        $ekumfi_info->refresh();

        $this->assertDatabaseHas('ekumfi_infos', [
            'primary_contact' => $requestData['primary_contact']
        ]);
    }
}
