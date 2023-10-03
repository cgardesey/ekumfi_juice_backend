<?php

namespace Tests\Unit;

use App\Wholesaler;
use App\WholesalerProduct;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WholesalerProductFactoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /** @test */
    public function it_can_create_a_wholesaler_product()
    {
        $wholesaler_product = factory(WholesalerProduct::class)->create();

        $this->assertInstanceOf(WholesalerProduct::class, $wholesaler_product);
        $this->assertDatabaseHas('wholesaler_products', [
            'wholesaler_product_id' => $wholesaler_product->wholesaler_product_id,
            'product_id' => $wholesaler_product->product_id,
            'wholesaler_id' => $wholesaler_product->wholesaler_id,
            'unit_quantity' => $wholesaler_product->unit_quantity,
            'unit_price' => $wholesaler_product->unit_price,
            'quantity_available' => $wholesaler_product->quantity_available,
        ]);
    }
}
