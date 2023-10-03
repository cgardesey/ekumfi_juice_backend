<?php

namespace Tests\Unit;

use App\Payment;
use Tests\TestCase;
use App\Product;
use App\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_payment()
    {
        $payment = factory(Payment::class)->create();

        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertDatabaseHas('payments', [
            'payment_id' => $payment->payment_id,
            'msisdn' => $payment->msisdn,
            'country_code' => $payment->country_code,
            'network' => $payment->network,
            'currency' => $payment->currency,
            'amount' => $payment->amount,
            'description' => $payment->description,
            'payment_ref' => $payment->payment_ref,
            'message' => $payment->message,
            'response_message' => $payment->response_message,
            'status' => $payment->status,
            'external_reference_no' => $payment->external_reference_no,
            'transaction_status_reason' => $payment->transaction_status_reason,
            'cart_id' => $payment->cart_id,
            'stock_cart_id' => $payment->stock_cart_id,
            'wholesaler_cart_id' => $payment->wholesaler_cart_id,
            'created_at' => $payment->created_at,
            'updated_at' => $payment->updated_at,
        ]);
    }
}
