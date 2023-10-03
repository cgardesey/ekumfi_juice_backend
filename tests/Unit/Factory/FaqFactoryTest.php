<?php

namespace Tests\Unit\Factories;

use App\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FaqFactoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_faq_factory_creates_faq()
    {
        $attributes = [
            'faq_id' => $this->faker->uuid,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'tag' => $this->faker->word,
        ];

        $faq = factory(Faq::class)->create($attributes);

        $this->assertInstanceOf(Faq::class, $faq);
        $this->assertDatabaseHas('faqs', $attributes);
    }
}
