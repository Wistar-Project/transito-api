<?php

namespace Tests\Feature;

use App\Models\Administrador;
use App\Models\Alojamiento;
use App\Models\Camion;
use App\Models\Conductor;
use App\Models\ConductorManeja;
use App\Models\Funcionario;
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

    private function crearGerente(){
        $user = User::factory()->create();
        Persona::create(["id" => $user->id, "nombre" => "a", "apellido" => "a"]);
        Gerente::create(["id" => $user->id]);
        return $user;
    }

    public function test_estado()
    {
        Alojamiento::create(["id" => 1, "direccion" => "Una dirección"]);
        Sede::create(["id" => 1]);
        Lote::create(["id" => 1, "destino" => 1]);
        Vehiculo::create(["id" => 1, "capacidad_en_toneladas" => 5]);
        Camion::create(["id_vehiculo" => 1]);
        LoteAsignadoACamion::create(["id_lote" => 1, "id_camion" => 1]);
        ConductorManeja::create(["id_conductor" => $this -> crearConductor() -> id, "id_vehiculo" => 1]);

        $response = $this -> actingAs($this -> crearGerente()) -> get('/api/v1/lote/1');
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
        Alojamiento::create(["id" => 2, "direccion" => "Otra dirección"]);
        Sede::create(["id" => 2]);
        Lote::create(["id" => 2, "destino" => 2]);

        $response = $this -> actingAs($this -> crearGerente()) -> get('/api/v1/lote/2');
        $response -> assertStatus(404);
    }

    public function test_estadoSinAsignarChofer()
    {
        Alojamiento::create(["id" => 3, "direccion" => "Dirección 3"]);
        Sede::create(["id" => 3]);
        Lote::create(["id" => 3, "destino" => 3]);
        Vehiculo::create(["id" => 3, "capacidad_en_toneladas" => 5]);
        Camion::create(["id_vehiculo" => 3]);
        LoteAsignadoACamion::create(["id_lote" => 3, "id_camion" => 3]);

        $response = $this -> actingAs($this -> crearGerente()) -> get('/api/v1/lote/3');
        $response -> assertStatus(200);
        $response -> assertExactJson([
            "idLote" => 3,
            "estado" => "En espera",
            "camionAsignado" => 3,
            "conductor" => "Ninguno",
            "destino" => "Dirección 3",
            "paquetes" => []
        ]);
    }

    public function test_estadoInexistente()
    {
        $response = $this -> actingAs($this -> crearGerente()) -> get('/api/v1/lote/99');
        $response -> assertStatus(404);
    }

    public function test_estadoSinAutenticarse()
    {
        $response = $this -> get('/api/v1/lote/1', [
            "Accept" => "application/json"
        ]);
        $response -> assertStatus(401);
        $response -> assertExactJson([
            "message" => "Unauthenticated."
        ]);
    }

    public function test_estadoSiendoFuncionario()
    {
        $user = User::factory()->create();
        Persona::create(["id" => $user->id, "nombre" => "a", "apellido" => "a"]);
        Conductor::create(["id" => $user->id]);
        $response = $this -> actingAs($user) -> get('/api/v1/lote/1');
        $response -> assertStatus(401);
        $response -> assertExactJson([
            "message" => "No tienes permiso para ver esto."
        ]);
    }

    public function test_estadoSiendoChofer()
    {
        $user = User::factory()->create();
        Persona::create(["id" => $user->id, "nombre" => "a", "apellido" => "a"]);
        Conductor::create(["id" => $user->id]);
        $response = $this -> actingAs($user) -> get('/api/v1/lote/1');
        $response -> assertStatus(401);
        $response -> assertExactJson([
            "message" => "No tienes permiso para ver esto."
        ]);
    }

    public function test_estado_siendo_administrador()
    {
        $user = User::factory()->create();
        Persona::create(["id" => $user->id, "nombre" => "a", "apellido" => "a"]);
        Administrador::create(["id" => $user->id]);

        Alojamiento::create(["id" => 4, "direccion" => "Dirección 4"]);
        Sede::create(["id" => 4]);
        Lote::create(["id" => 4, "destino" => 4]);
        Vehiculo::create(["id" => 4, "capacidad_en_toneladas" => 5]);
        Camion::create(["id_vehiculo" => 4]);
        LoteAsignadoACamion::create(["id_lote" => 4, "id_camion" => 4]);

        $response = $this -> actingAs($user) -> get('/api/v1/lote/4');
        $response -> assertStatus(200);
        $response -> assertExactJson([
            "idLote" => 4,
            "estado" => "En espera",
            "camionAsignado" => 4,
            "conductor" => "Ninguno",
            "destino" => "Dirección 4",
            "paquetes" => []
        ]);
    }
}
