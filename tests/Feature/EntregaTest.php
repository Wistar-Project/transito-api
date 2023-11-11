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

class EntregaTest extends TestCase
{
    private function crearConductor(){
        $conductor = User::factory()->create();
        Persona::create(["id" => $conductor->id, "nombre" => "Domingo", "apellido" => "Perez"]);
        Conductor::create(["id" => $conductor -> id]);
        return $conductor;
    }
    
    public function test_mostrar_con_camion()
    {
        $conductor = $this -> crearConductor();
        Alojamiento::create(["id" => 20, "direccion" => "Dirección 20"]);
        Sede::create(["id" => 20]);
        Lote::create(["id" => 20, "destino" => 20]);
        Alojamiento::create(["id" => 21, "direccion" => "Dirección 21"]);
        Sede::create(["id" => 21]);
        Lote::create(["id" => 21, "destino" => 21]);
        Alojamiento::create(["id" => 22, "direccion" => "Dirección 22"]);
        Sede::create(["id" => 22]);
        Lote::create(["id" => 22, "destino" => 22]);
        Lote::create(["id" => 23, "destino" => 22]);
        Vehiculo::create(["id" => 20, "capacidad_en_toneladas" => 5]);
        Camion::create(["id_vehiculo" => 20]);
        ConductorManeja::create(["id_vehiculo" => 20, "id_conductor" => $conductor -> id]);
        LoteAsignadoACamion::create(["id_lote" => 20, "id_camion" => 20]);
        LoteAsignadoACamion::create(["id_lote" => 21, "id_camion" => 20]);
        LoteAsignadoACamion::where(["id_lote" => 21, "id_camion" => 20])->first()->delete();
        LoteAsignadoACamion::create(["id_lote" => 22, "id_camion" => 20]);
        LoteAsignadoACamion::where(["id_lote" => 22, "id_camion" => 20])->first()->delete();
        LoteAsignadoACamion::create(["id_lote" => 23, "id_camion" => 20]);
        LoteAsignadoACamion::where(["id_lote" => 23, "id_camion" => 20])->first()->delete();

        $response = $this->actingAs($conductor)->get('/api/v1/entregas');
        $response->assertStatus(200);
        $response->assertExactJson([
            [
                "idDireccion" => 20,
                "direccion" => "Dirección 20",
                "entregada" => false
            ], [
                "idDireccion" => 21,
                "direccion" => "Dirección 21",
                "entregada" => true
            ], [
                "idDireccion" => 22,
                "direccion" => "Dirección 22",
                "entregada" => true
            ]
        ]);
    }

    public function test_mostrar_con_pickup()
    {
        $conductor = $this -> crearConductor();
        Alojamiento::create(["id" => 24, "direccion" => "Dirección 24"]);
        Sede::create(["id" => 24]);
        Paquete::create(["id" => 24, "destino" => 24, "peso_en_kg" => 5, "email" => "a@gmail.com"]);
        Alojamiento::create(["id" => 25, "direccion" => "Dirección 25"]);
        Sede::create(["id" => 25]);
        Paquete::create(["id" => 25, "destino" => 25, "peso_en_kg" => 5, "email" => "a@gmail.com"]);
        Alojamiento::create(["id" => 26, "direccion" => "Dirección 26"]);
        Sede::create(["id" => 26]);
        Paquete::create(["id" => 26, "destino" => 26, "peso_en_kg" => 5, "email" => "a@gmail.com"]);
        Paquete::create(["id" => 27, "destino" => 26, "peso_en_kg" => 5, "email" => "a@gmail.com"]);
        Vehiculo::create(["id" => 30, "capacidad_en_toneladas" => 5]);
        Pickup::create(["id_vehiculo" => 30]);
        ConductorManeja::create(["id_vehiculo" => 30, "id_conductor" => $conductor -> id]);
        PaqueteAsignadoAPickup::create(["id_paquete" => 24, "id_pickup" => 30]);
        PaqueteAsignadoAPickup::create(["id_paquete" => 25, "id_pickup" => 30]);
        PaqueteAsignadoAPickup::where(["id_paquete" => 25, "id_pickup" => 30])->first()->delete();
        PaqueteAsignadoAPickup::create(["id_paquete" => 26, "id_pickup" => 30]);
        PaqueteAsignadoAPickup::where(["id_paquete" => 26, "id_pickup" => 30])->first()->delete();
        PaqueteAsignadoAPickup::create(["id_paquete" => 27, "id_pickup" => 30]);
        PaqueteAsignadoAPickup::where(["id_paquete" => 27, "id_pickup" => 30])->first()->delete();

        $response = $this->actingAs($conductor)->get('/api/v1/entregas');
        $response->assertStatus(200);
        $response->assertExactJson([
            [
                "idDireccion" => 24,
                "direccion" => "Dirección 24",
                "entregada" => false
            ], [
                "idDireccion" => 25,
                "direccion" => "Dirección 25",
                "entregada" => true
            ], [
                "idDireccion" => 26,
                "direccion" => "Dirección 26",
                "entregada" => true
            ]
        ]);
    }

    public function test_mostrar_sin_vehiculo_asignado()
    {
        $conductor = $this -> crearConductor();
        $response = $this->actingAs($conductor)->get('/api/v1/entregas');
        $response->assertStatus(404);
    }

    public function test_mostrar_siendo_administrador()
    {
        $administrador = User::factory()->create();
        Persona::create(["id" => $administrador->id, "nombre" => "Domingo", "apellido" => "Perez"]);
        Administrador::create(["id" => $administrador -> id]);
        $response = $this->actingAs($administrador)->get('/api/v1/entregas');
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "No tienes permiso para ver esto."
        ]);
    }

    public function test_mostrar_siendo_gerente()
    {
        $gerente = User::factory()->create();
        Persona::create(["id" => $gerente->id, "nombre" => "Domingo", "apellido" => "Perez"]);
        Gerente::create(["id" => $gerente -> id]);
        $response = $this->actingAs($gerente)->get('/api/v1/entregas');
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "No tienes permiso para ver esto."
        ]);
    }

    public function test_mostrar_siendo_funcionario()
    {
        $funcionario = User::factory()->create();
        Persona::create(["id" => $funcionario->id, "nombre" => "Domingo", "apellido" => "Perez"]);
        Funcionario::create(["id" => $funcionario -> id]);
        $response = $this->actingAs($funcionario)->get('/api/v1/entregas');
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "No tienes permiso para ver esto."
        ]);
    }

    public function test_mostrar_sin_autenticarse()
    {
        $response = $this->get('/api/v1/entregas', [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "Unauthenticated."
        ]);
    }

    public function test_mostrar_descargas_en_camion()
    {
        $conductor = $this -> crearConductor();
        Alojamiento::create(["id" => 35, "direccion" => "Dirección 35"]);
        Sede::create(["id" => 35]);
        Vehiculo::create(["id" => 35, "capacidad_en_toneladas" => 5]);
        Camion::create(["id_vehiculo" => 35]);
        ConductorManeja::create(["id_vehiculo" => 35, "id_conductor" => $conductor -> id]);
        Lote::create(["id" => 35, "destino" => 35]);
        Lote::create(["id" => 36, "destino" => 35]);
        Lote::create(["id" => 37, "destino" => 35]);
        LoteAsignadoACamion::create(["id_lote" => 35, "id_camion" => 35]);
        LoteAsignadoACamion::create(["id_lote" => 36, "id_camion" => 35]);
        LoteAsignadoACamion::create(["id_lote" => 37, "id_camion" => 35]);

        $response = $this->actingAs($conductor)->get('/api/v1/entregas/35');
        $response->assertStatus(200);
        $response->assertExactJson([ 35, 36, 37 ]);
    }

    public function test_mostrar_descargas_en_pickup()
    {
        $conductor = $this -> crearConductor();
        Alojamiento::create(["id" => 36, "direccion" => "Dirección 36"]);
        Sede::create(["id" => 36]);
        Vehiculo::create(["id" => 36, "capacidad_en_toneladas" => 5]);
        Pickup::create(["id_vehiculo" => 36]);
        ConductorManeja::create(["id_vehiculo" => 36, "id_conductor" => $conductor -> id]);
        Paquete::create(["id" => 40, "destino" => 36, "peso_en_kg" => 5, "email" => "a@gmail.com"]);
        Paquete::create(["id" => 41, "destino" => 36, "peso_en_kg" => 5, "email" => "a@gmail.com"]);
        Paquete::create(["id" => 42, "destino" => 36, "peso_en_kg" => 5, "email" => "a@gmail.com"]);
        PaqueteAsignadoAPickup::create(["id_paquete" => 40, "id_pickup" => 36]);
        PaqueteAsignadoAPickup::create(["id_paquete" => 41, "id_pickup" => 36]);
        PaqueteAsignadoAPickup::create(["id_paquete" => 42, "id_pickup" => 36]);

        $response = $this->actingAs($conductor)->get('/api/v1/entregas/36');
        $response->assertStatus(200);
        $response->assertExactJson([ 40, 41, 42 ]);
    }

    public function test_mostrar_descargas_sin_vehiculo()
    {
        $conductor = $this -> crearConductor();
        Alojamiento::create(["id" => 37, "direccion" => "Dirección 37"]);
        Sede::create(["id" => 37]);

        $response = $this->actingAs($conductor)->get('/api/v1/entregas/37');
        $response->assertStatus(404);
    }

    public function test_mostrar_descargas_sin_autenticarse()
    {
        Alojamiento::create(["id" => 38, "direccion" => "Dirección 38"]);
        Sede::create(["id" => 38]);

        $response = $this->get('/api/v1/entregas/38', [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "Unauthenticated."
        ]);
    }

    public function test_mostrar_descargas_siendo_administrador()
    {
        $administrador = User::factory()->create();
        Persona::create(["id" => $administrador->id, "nombre" => "Domingo", "apellido" => "Perez"]);
        Administrador::create(["id" => $administrador -> id]);
        Alojamiento::create(["id" => 39, "direccion" => "Dirección 39"]);
        Sede::create(["id" => 39]);

        $response = $this->actingAs($administrador)->get('/api/v1/entregas/39');
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "No tienes permiso para ver esto."
        ]);
    }

    public function test_mostrar_descargas_siendo_gerente()
    {
        $gerente = User::factory()->create();
        Persona::create(["id" => $gerente->id, "nombre" => "Domingo", "apellido" => "Perez"]);
        Gerente::create(["id" => $gerente -> id]);
        Alojamiento::create(["id" => 40, "direccion" => "Dirección 40"]);
        Sede::create(["id" => 40]);

        $response = $this->actingAs($gerente)->get('/api/v1/entregas/40');
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "No tienes permiso para ver esto."
        ]);
    }

    public function test_mostrar_descargas_siendo_funcionario()
    {
        $funcionario = User::factory()->create();
        Persona::create(["id" => $funcionario->id, "nombre" => "Domingo", "apellido" => "Perez"]);
        Funcionario::create(["id" => $funcionario -> id]);
        Alojamiento::create(["id" => 41, "direccion" => "Dirección 41"]);
        Sede::create(["id" => 41]);

        $response = $this->actingAs($funcionario)->get('/api/v1/entregas/41');
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "No tienes permiso para ver esto."
        ]);
    }

    public function test_marcar_entregado()
    {
        $conductor = $this -> crearConductor();
        Alojamiento::create(["id" => 42, "direccion" => "Dirección 42"]);
        Sede::create(["id" => 42]);
        Vehiculo::create(["id" => 42, "capacidad_en_toneladas" => 5]);
        Camion::create(["id_vehiculo" => 42]);
        ConductorManeja::create(["id_vehiculo" => 42, "id_conductor" => $conductor -> id]);
        Lote::create(["id" => 42, "destino" => 42]);
        Lote::create(["id" => 43, "destino" => 42]);
        Lote::create(["id" => 44, "destino" => 42]);
        LoteAsignadoACamion::create(["id_lote" => 42, "id_camion" => 42]);
        LoteAsignadoACamion::create(["id_lote" => 43, "id_camion" => 42]);
        LoteAsignadoACamion::create(["id_lote" => 44, "id_camion" => 42]);
        Alojamiento::create(["id" => 43, "direccion" => "Dirección 43"]);
        Sede::create(["id" => 43]);
        Lote::create(["id" => 45, "destino" => 43]);
        Lote::create(["id" => 46, "destino" => 43]);
        LoteAsignadoACamion::create(["id_lote" => 45, "id_camion" => 42]);
        LoteAsignadoACamion::create(["id_lote" => 46, "id_camion" => 42]);

        $response = $this->actingAs($conductor)->get('/api/v1/entregas');
        $response->assertStatus(200);
        $response->assertExactJson([
            [
                "idDireccion" => 42,
                "direccion" => "Dirección 42",
                "entregada" => false
            ], [
                "idDireccion" => 43,
                "direccion" => "Dirección 43",
                "entregada" => false
            ]
        ]);
        $response = $this->actingAs($conductor)->delete('/api/v1/entregas/43');
        $response->assertStatus(200);
        $response = $this->actingAs($conductor)->get('/api/v1/entregas');
        $response->assertStatus(200);
        $response->assertExactJson([
            [
                "idDireccion" => 42,
                "direccion" => "Dirección 42",
                "entregada" => false
            ], [
                "idDireccion" => 43,
                "direccion" => "Dirección 43",
                "entregada" => true
            ]
        ]);
    }
}
