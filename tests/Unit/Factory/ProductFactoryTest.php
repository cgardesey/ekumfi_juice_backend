<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Product;
use App\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_product()
    {
        $product = factory(Product::class)->create();

        $this->assertInstanceOf(Product::class, $product);
        $this->assertDatabaseHas('products', [
            'product_id' => $product->product_id,
            'name' => $product->name,
            'description' => $product->description,
            'image_url' => $product->image_url,
            'unit_quantity' => $product->unit_quantity,
            'unit_price' => $product->unit_price,
            'quantity_available' => $product->quantity_available,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ]);
    }
}
