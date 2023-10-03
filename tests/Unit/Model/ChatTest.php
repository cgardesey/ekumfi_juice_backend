<?php

namespace Tests\Unit;

use App\Chat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $chat = new Chat();
        $this->assertEquals('chats', $chat->getTable());
    }

    /** @test */
    public function it_has_the_correct_primary_key()
    {
        $chat = new Chat();

        $this->assertEquals('chat_id', $chat->getKeyName());
    }

    /** @test */
    public function it_has_the_correct_incrementing_setting()
    {
        $chat = new Chat();

        $this->assertFalse($chat->getIncrementing());
    }

    /** @test */
    public function it_has_the_correct_key_type()
    {
        $chat = new Chat();

        $this->assertEquals('string', $chat->getKeyType());
    }

    /** @test */
    public function it_returns_the_correct_route_key_name()
    {
        $chat = new Chat();

        $this->assertEquals('chat_id', $chat->getRouteKeyName());
    }
}
