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
        $response = $this->get('/api/v1/paquete/1');
        $response -> assertStatus(404);
    }

    public function test_estadoPaqueteEnEspera()
    {
        $response = $this -> get('/api/v1/paquete/2');
        $response -> assertStatus(200);
        $response -> assertJson([
            "id_paquete" => 2,
            "id_pickup_asignada" => 1,
            "id_conductor" => null,
            "destino" => 1
        ]);
    }

    public function test_estadoPaqueteEnTrayecto()
    {
        $response = $this -> get('/api/v1/paquete/3');
        $response -> assertStatus(200);
        $response -> assertJson([
            "id_paquete" => 3,
            "id_pickup_asignada" => 2,
            "id_conductor" => 1,
            "destino" => 1
        ]);
    }

    public function test_estadoPaqueteEnLoteEnTrayecto()
    {
        $response = $this->get('/api/v1/paquete/4');
        $response -> assertStatus(200);
        $response -> assertJson([
            "id_paquete" => 4,
            "id_camion_asignado" => 3,
            "id_conductor" => 2,
            "destino" => 1,
            "id_lote_asignado" => 1
        ]);
    }

    public function test_estadoPaqueteInexistente()
    {
        $response = $this->get('/api/v1/paquete/259281');
        $response -> assertStatus(404);
    }
}
