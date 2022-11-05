<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class ConverterControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPositive()
    {
        $response = $this->get('/api/8USD/asEUR');
        $response->assertStatus(200);
    }

    public function testNullInputSum()
    {
        $response = $this->get('/api/0USD/asEUR');
        $response->assertStatus(200);

        $val = [
            "currency" => "EUR",
            "sum"      => 0
        ];
        $this->assertJsonStringEqualsJsonString($response->content(), json_encode($val));
    }

    public function testWrongInputCurrency()
    {
        $response = $this->get('/api/8USDDASD8D/asEUR');
        $response->assertStatus(422);

        $val = [
            "inputSum" => [
                "The input sum format is invalid."
            ]
        ];
        $this->assertJsonStringEqualsJsonString($response->content(), json_encode($val));
    }

    public function testNegativeInputSum()
    {
        $response = $this->get('/api/-8USD/asEUR');
        $response->assertStatus(422);

        $val = [
            "inputSum" => [
                "The input sum format is invalid."
            ]
        ];
        $this->assertJsonStringEqualsJsonString($response->content(), json_encode($val));
    }

    public function testWrongOutputCurrency()
    {
        $response = $this->get('/api/8USD/asEURDASD');
        $response->assertStatus(422);

        $val = [
            "outputCurrency" => [
                "The output currency format is invalid."
            ]
        ];
        $this->assertJsonStringEqualsJsonString($response->content(), json_encode($val));
    }
}
