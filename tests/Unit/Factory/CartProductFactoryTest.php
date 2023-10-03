<?php

namespace Tests\Unit;

use App\Cart;
use App\CartProduct;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartProductFactoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /** @test */
    public function it_can_create_a_cart_product()
    {
        $cart_product = factory(CartProduct::class)->create();

        $this->assertInstanceOf(CartProduct::class, $cart_product);
        $this->assertDatabaseHas('cart_products', [
            'cart_product_id' => $cart_product->cart_product_id,
            'cart_id' => $cart_product->cart_id,
            'seller_product_id' => $cart_product->seller_product_id,
            'quantity' => $cart_product->quantity,
            'price' => $cart_product->price
        ]);
    }
}
