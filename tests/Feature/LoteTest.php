<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_estadoLoteNoAsignado()
    {
        $response = $this->get('/api/v1/lote/2');
        $response -> assertStatus(404);
    }

    public function test_estadoLoteEnEspera()
    {
        $response = $this -> get('/api/v1/lote/3');
        $response -> assertStatus(200);
        $response -> assertJson([
            "id_lote" => 3,
            "id_camion_asignado" => 4,
            "id_conductor" => null,
            "destino" => 1
        ]);
    }

    public function test_estadoLoteEnTrayecto()
    {
        $response = $this->get('/api/v1/lote/1');
        $response -> assertStatus(200);
        $response -> assertJson([
            "id_lote" => 1,
            "id_camion_asignado" => 3,
            "id_conductor" => 2,
            "destino" => 1,
        ]);
    }

    public function test_estadoLoteInexistente()
    {
        $response = $this->get('/api/v1/lote/259281');
        $response -> assertStatus(404);
    }
}
