<?php

namespace Tests\Unit;

use App\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $payment = new Payment();
        $this->assertEquals('payments', $payment->getTable());
    }

    /** @test */
    public function it_uses_payment_id_as_the_primary_key()
    {
        $payment = new Payment();

        $this->assertEquals('payment_id', $payment->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $payment = new Payment();

        $this->assertFalse($payment->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_type()
    {
        $payment = new Payment();
        $this->assertEquals('string', $payment->getKeyType());
    }

    /** @test */
    public function it_returns_payment_id_for_route_key()
    {
        $payment = new Payment();

        $this->assertEquals('payment_id', $payment->getRouteKeyName());
    }
}
