<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaqueteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_estadoPaqueteNoAsignado()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_estadoPaqueteEnEspera()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_estadoPaqueteEnTrayecto()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_estadoPaqueteEnLoteEnTrayecto()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
