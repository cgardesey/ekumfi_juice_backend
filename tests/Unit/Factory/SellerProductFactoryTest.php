<?php

namespace Tests\Unit;

use App\Seller;
use App\SellerProduct;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SellerProductFactoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /** @test */
    public function it_can_create_a_seller_product()
    {
        $seller_product = factory(SellerProduct::class)->create();

        $this->assertInstanceOf(SellerProduct::class, $seller_product);
        $this->assertDatabaseHas('seller_products', [
            'seller_product_id' => $seller_product->seller_product_id,
            'product_id' => $seller_product->product_id,
            'seller_id' => $seller_product->seller_id,
            'unit_quantity' => $seller_product->unit_quantity,
            'unit_price' => $seller_product->unit_price,
            'quantity_available' => $seller_product->quantity_available,
        ]);
    }
}
