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
        $response -> assertStatus(400);
        $response -> assertJson([
            "message" => "El paquete no está asignado a ninguna pickup."
        ]);
    }

    public function test_estadoPaqueteEnEspera()
    {
        $response = $this -> get('/api/v1/paquete/2');
        $response -> assertStatus(200);
        $response -> assertJson([
            "id_paquete" => "2",
            "id_pickup_asignada" => 1,
            "id_conductor" => "N/A",
            "estado" => "No está en trayecto",
            "destino" => 1
        ]);
    }

    public function test_estadoPaqueteEnTrayecto()
    {
        $response = $this -> get('/api/v1/paquete/3');
        $response -> assertStatus(200);
        $response -> assertJson([
            "id_paquete" => "3",
            "id_pickup_asignada" => 2,
            "id_conductor" => 1,
            "estado" => "En trayecto",
            "destino" => 1
        ]);
    }

    public function test_estadoPaqueteEnLoteEnTrayecto()
    {
        $response = $this->get('/api/v1/paquete/4');
        $response -> assertStatus(200);
        $response -> assertJson([
            "id_paquete" => "4",
            "id_camion_asignado" => 3,
            "id_conductor" => 2,
            "estado" => "En trayecto",
            "destino" => 1,
            "id_lote_asignado" => 1
        ]);
    }
}
