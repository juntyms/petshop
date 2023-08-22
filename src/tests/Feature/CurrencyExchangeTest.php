<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CurrencyExchangeTest extends TestCase
{
    /** @test */
    public function currency_exchange_endpoint_can_be_accessed(): void
    {
        $response = $this->get('/api/v1/currency/USD/1');

        $response->assertStatus(200);
    }

}
