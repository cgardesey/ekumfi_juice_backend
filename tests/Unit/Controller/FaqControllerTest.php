<?php

namespace Tests\Unit\Controllers;

use App\Faq;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index method of FaqController.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = factory(User::class)->create();

        $faqs = factory(Faq::class, 3)->create();

        $response = $this->get('/api/faqs', [
            'Authorization' => 'Bearer ' . $user->api_token,
        ]);

        $response->assertStatus(200);


        $response->assertJsonFragment([
            'faq_id' => $faqs[0]->faq_id,
            'title' => $faqs[0]->title,
            'description' => $faqs[0]->description
        ]);
    }

    /**
     * Test store method of FaqController.
     *
     * @return void
     */
    public function testStore()
    {
        $requestData = [
            'title' => "Odit quas aperiam placeat nemo.",
            'description' => "Velit rerum aperiam quod. Eligendi fugit voluptate quia expedita sint. Nihil est dolor distinctio ipsum. Praesentium magni ex dolore ut."
        ];

        $user = factory(User::class)->create();
        $response = $this->post('/api/faqs', $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment($requestData);

        $this->assertDatabaseHas('faqs', $requestData);
    }

    /**
     * Test show method of FaqController.
     *
     * @return void
     */
    public function testShow()
    {
        $faq = factory(Faq::class)->create();

        $user = factory(User::class)->create();
        $response = $this->get('api/faqs/' . $faq->faq_id , [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'faq_id' => $faq->faq_id,
            'title' => $faq->title,
            'description' => $faq->description
        ]);
    }

    /**
     * Test update method of FaqController.
     *
     * @return void
     */
    public function testUpdate()
    {
        $faq = factory(Faq::class)->create();
        $faq->makeVisible('faq_id');

        $requestData = [
            'title' => "Odit quas aperiam placeat nemo.",
            'description' => "Velit rerum aperiam quod. Eligendi fugit voluptate quia expedita sint. Nihil est dolor distinctio ipsum. Praesentium magni ex dolore ut."
        ];

        $user = factory(User::class)->create();

        $response = $this->patch('/api/faqs/' . $faq->faq_id, $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment($requestData);

        $faq->refresh();

        $this->assertDatabaseHas('faqs', $requestData);
    }
}
