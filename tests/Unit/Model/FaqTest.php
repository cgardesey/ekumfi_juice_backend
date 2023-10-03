<?php

namespace Tests\Unit;

use App\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $faq = new Faq();
        $this->assertEquals('faqs', $faq->getTable());
    }

    /** @test */
    public function it_uses_faq_id_as_the_primary_key()
    {
        $faq = new Faq();

        $this->assertEquals('faq_id', $faq->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $faq = new Faq();

        $this->assertFalse($faq->getIncrementing());
    }


    /** @test */
    public function it_returns_faq_id_for_route_key()
    {
        $faq = new Faq();

        $this->assertEquals('faq_id', $faq->getRouteKeyName());
    }
}
