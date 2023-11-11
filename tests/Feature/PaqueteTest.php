<?php

namespace Tests\Feature;

use App\Models\Administrador;
use App\Models\Alojamiento;
use App\Models\Camion;
use App\Models\Conductor;
use App\Models\ConductorManeja;
use App\Models\Gerente;
use App\Models\Lote;
use App\Models\LoteAsignadoACamion;
use App\Models\LoteFormadoPor;
use App\Models\Paquete;
use App\Models\PaqueteAsignadoAPickup;
use App\Models\Persona;
use App\Models\Pickup;
use App\Models\Sede;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaqueteTest extends TestCase
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

    public function test_estado_sin_lote()
    {
        Alojamiento::create(["id" => 10, "direccion" => "Dirección 10"]);
        Sede::create(["id" => 10]);
        Paquete::create(["id" => 10, "destino" => 10, "peso_en_kg" => 5, "email" => "a@gmail.com"]);
        Vehiculo::create(["id" => 10, "capacidad_en_toneladas" => 5]);
        Pickup::create(["id_vehiculo" => 10]);
        PaqueteAsignadoAPickup::create(["id_paquete" => 10, "id_pickup" => 10]);
        ConductorManeja::create(["id_conductor" => $this -> crearConductor() -> id, "id_vehiculo" => 10]);

        $response = $this -> actingAs($this -> crearGerente()) -> get('/api/v1/paquete/10');
        $response -> assertStatus(200);
        $response -> assertExactJson([
            "paqueteId" => 10,
            "estado" => "En trayecto",
            "vehiculoAsignado" => "Pickup 10",
            "conductor" => "Domingo Perez",
            "destino" => "Dirección 10",
        ]);
    }

    public function test_estado_sin_asignar_vehiculo()
    {
        Alojamiento::create(["id" => 11, "direccion" => "Dirección 11"]);
        Sede::create(["id" => 11]);
        Paquete::create(["id" => 11, "destino" => 11, "peso_en_kg" => 5, "email" => "a@gmail.com"]);

        $response = $this -> actingAs($this -> crearGerente()) -> get('/api/v1/paquete/11');
        $response -> assertStatus(400);
    }

    public function test_estado_sin_asignar_chofer()
    {
        Alojamiento::create(["id" => 12, "direccion" => "Dirección 12"]);
        Sede::create(["id" => 12]);
        Paquete::create(["id" => 12, "destino" => 12, "peso_en_kg" => 5, "email" => "a@gmail.com"]);
        Vehiculo::create(["id" => 12, "capacidad_en_toneladas" => 5]);
        Pickup::create(["id_vehiculo" => 12]);
        PaqueteAsignadoAPickup::create(["id_paquete" => 12, "id_pickup" => 12]);

        $response = $this -> actingAs($this -> crearGerente()) -> get('/api/v1/paquete/12');
        $response -> assertStatus(200);
        $response -> assertExactJson([
            "paqueteId" => 12,
            "estado" => "En espera",
            "vehiculoAsignado" => "Pickup 12",
            "conductor" => "Ninguno",
            "destino" => "Dirección 12"
        ]);
    }

    public function test_estado_inexistente()
    {
        $response = $this -> actingAs($this -> crearGerente()) -> get('/api/v1/paquete/99');
        $response -> assertStatus(404);
    }

    public function test_estado_sin_autenticarse()
    {
        $response = $this -> get('/api/v1/paquete/10', [
            "Accept" => "application/json"
        ]);
        $response -> assertStatus(401);
        $response -> assertExactJson([
            "message" => "Unauthenticated."
        ]);
    }

    public function test_estado_siendo_funcionario()
    {
        $user = User::factory()->create();
        Persona::create(["id" => $user->id, "nombre" => "a", "apellido" => "a"]);
        Conductor::create(["id" => $user->id]);
        $response = $this -> actingAs($user) -> get('/api/v1/paquete/10');
        $response -> assertStatus(401);
        $response -> assertExactJson([
            "message" => "No tienes permiso para ver esto."
        ]);
    }

    public function test_estado_siendo_chofer()
    {
        $user = User::factory()->create();
        Persona::create(["id" => $user->id, "nombre" => "a", "apellido" => "a"]);
        Conductor::create(["id" => $user->id]);
        $response = $this -> actingAs($user) -> get('/api/v1/paquete/10');
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

        Alojamiento::create(["id" => 13, "direccion" => "Dirección 13"]);
        Sede::create(["id" => 13]);
        Paquete::create(["id" => 13, "destino" => 13, "peso_en_kg" => 5, "email" => "a@gmail.com"]);
        Vehiculo::create(["id" => 13, "capacidad_en_toneladas" => 5]);
        Pickup::create(["id_vehiculo" => 13]);
        PaqueteAsignadoAPickup::create(["id_paquete" => 13, "id_pickup" => 13]);

        $response = $this -> actingAs($user) -> get('/api/v1/paquete/13');
        $response -> assertStatus(200);
        $response -> assertExactJson([
            "paqueteId" => 13,
            "estado" => "En espera",
            "vehiculoAsignado" => "Pickup 13",
            "conductor" => "Ninguno",
            "destino" => "Dirección 13",
        ]);
    }

    public function test_estado_con_lote()
    {
        Alojamiento::create(["id" => 14, "direccion" => "Dirección 14"]);
        Sede::create(["id" => 14]);
        Paquete::create(["id" => 14, "destino" => 14, "peso_en_kg" => 5, "email" => "a@gmail.com"]);
        Lote::create(["id" => 14, "destino" => 14]);
        LoteFormadoPor::create(["id_lote" => 14, "id_paquete" => 14]);
        Vehiculo::create(["id" => 14, "capacidad_en_toneladas" => 5]);
        Camion::create(["id_vehiculo" => 14]);
        LoteAsignadoACamion::create(["id_lote" => 14, "id_camion" => 14]);
        ConductorManeja::create(["id_conductor" => $this -> crearConductor() -> id, "id_vehiculo" => 14]);

        $response = $this -> actingAs($this -> crearGerente()) -> get('/api/v1/paquete/14');
        $response -> assertStatus(200);
        $response -> assertExactJson([
            "paqueteId" => 14,
            "estado" => "En trayecto",
            "vehiculoAsignado" => "Camión 14",
            "conductor" => "Domingo Perez",
            "destino" => "Dirección 14",
            "loteAsignado" => 14
        ]);
    }
}
