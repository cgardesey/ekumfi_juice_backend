<?php

namespace Tests\Unit;

use App\Consumer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsumerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $consumer = new Consumer();
        $this->assertEquals('consumers', $consumer->getTable());
    }

    /** @test */
    public function it_uses_consumer_id_as_the_primary_key()
    {
        $consumer = new Consumer();

        $this->assertEquals('consumer_id', $consumer->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $consumer = new Consumer();

        $this->assertFalse($consumer->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_type()
    {
        $consumer = new Consumer();
        $this->assertEquals('string', $consumer->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $consumer = new Consumer();
        $hidden = ['id'];
        $this->assertEquals($hidden, $consumer->getHidden());
    }

    /** @test */
    public function it_returns_consumer_id_for_route_key()
    {
        $consumer = new Consumer();

        $this->assertEquals('consumer_id', $consumer->getRouteKeyName());
    }
}
