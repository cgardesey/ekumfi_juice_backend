<?php

namespace Tests\Unit;

use App\Cart;
use App\CartProduct;
use App\Chat;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChatFactoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /** @test */
    public function it_can_create_a_cart_chat()
    {
        $chat = factory(Chat::class)->create();

        $this->assertInstanceOf(Chat::class, $chat);
        $this->assertDatabaseHas('chats', [
            'chat_id' => $chat->chat_id,
            'chat_ref_id' => $chat->chat_ref_id,
            'text' => $chat->text,
            'link' => $chat->link,
            'link_title' => $chat->link_title,
            'link_description' => $chat->link_description,
            'link_image' => $chat->link_image,
            'attachment_url' => $chat->attachment_url,
            'attachment_type' => $chat->attachment_type,
            'attachment_title' => $chat->attachment_title,
            'read_by_recipient' => $chat->read_by_recipient,
            'sent_by_consumer' => $chat->sent_by_consumer,
            'sender_role' => $chat->sender_role,
            'tag' => $chat->tag,
            'consumer_id' => $chat->consumer_id,
            'seller_id' => $chat->seller_id,
            'agent_id' => $chat->agent_id,
            'wholesaler_id' => $chat->wholesaler_id,
            'ekumfi_info_id' => $chat->ekumfi_info_id
        ]);
    }
}
