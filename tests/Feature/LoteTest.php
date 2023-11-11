<?php

namespace Tests\Feature;

use App\Models\Alojamiento;
use App\Models\Camion;
use App\Models\Conductor;
use App\Models\ConductorManeja;
use App\Models\Gerente;
use App\Models\Lote;
use App\Models\LoteAsignadoACamion;
use App\Models\Persona;
use App\Models\Sede;
use App\Models\User;
use App\Models\Vehiculo;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LoteTest extends TestCase
{
    private function crearConductor(){
        $conductor = User::factory()->create();
        Persona::create(["id" => $conductor->id, "nombre" => "Domingo", "apellido" => "Perez"]);
        Conductor::create(["id" => $conductor -> id]);
        return $conductor;
    }

    public function test_estado()
    {
        $user = User::factory()->create();
        Persona::create(["id" => $user->id, "nombre" => "a", "apellido" => "a"]);
        Alojamiento::create(["id" => 1, "direccion" => "Una dirección"]);
        Sede::create(["id" => 1]);
        Gerente::create(["id" => $user->id]);
        Lote::create(["id" => 1, "destino" => 1]);
        Vehiculo::create(["id" => 1, "capacidad_en_toneladas" => 5]);
        Camion::create(["id_vehiculo" => 1]);
        LoteAsignadoACamion::create(["id_lote" => 1, "id_camion" => 1]);
        $conductor = $this -> crearConductor();
        ConductorManeja::create(["id_conductor" => $conductor -> id, "id_vehiculo" => 1]);
        $response = $this -> actingAs($user) -> get('/api/v1/lote/1');

        $response -> assertStatus(200);
        $response -> assertExactJson([
            "idLote" => 1,
            "estado" => "En trayecto",
            "camionAsignado" => 1,
            "conductor" => "Domingo Perez",
            "destino" => "Una dirección",
            "paquetes" => []
        ]);
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
