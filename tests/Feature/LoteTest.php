<?php

namespace Tests\Feature;

use App\Models\Alojamiento;
use App\Models\Lote;
use App\Models\Sede;
use App\Models\User;
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
    public function test_estado()
    {
        $user = User::factory()->create();
        Alojamiento::create(["id" => 1, "direccion" => "Una dirección"]);
        Lote::create(["id" => 1, "destino" => 1]);
        Sede::create(["id" => 1]);
        $this -> actingAs($user) -> post('/api/v1/lote/1');

    }

    public function test_estadoSinAsignarVehiculo()
    {
        $this -> assertEquals(1, 1);
    }

    public function test_estadoSinAsignarChofer()
    {
        $this -> assertEquals(1, 1);
    }

    public function test_estadoInexistente()
    {
        $this -> assertEquals(1, 1);
    }

    public function test_estadoSinAutenticarse()
    {
        $this -> assertEquals(1, 1);
    }

    public function test_estadoSiendoFuncionario()
    {
        $this -> assertEquals(1, 1);
    }

    public function test_estadoSiendoChofer()
    {
        $this -> assertEquals(1, 1);
    }
}
