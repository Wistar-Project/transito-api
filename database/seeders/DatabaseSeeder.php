<?php

namespace Database\Seeders;

use App\Models\Alojamiento;
use App\Models\Camion;
use App\Models\Conductor;
use App\Models\ConductorManeja;
use App\Models\Lote;
use App\Models\LoteAsignadoACamion;
use App\Models\LoteFormadoPor;
use Illuminate\Database\Seeder;
use App\Models\Paquete;
use App\Models\PaqueteAsignadoAPickup;
use App\Models\Persona;
use App\Models\Pickup;
use App\Models\Sede;
use App\Models\User;
use App\Models\Vehiculo;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Alojamiento::create([
            "id" => 1,
            "direccion" => "Algun destino"
        ]);
        Sede::create([
            "id" => 1
        ]);
        Paquete::create([
            "id" => 1,
            "peso_en_kg" => 5,
            "email" => "prueba@email.com",
            "destino" => 1
        ]);

        Paquete::create([
            "id" => 2,
            "peso_en_kg" => 5,
            "email" => "prueba@email.com",
            "destino" => 1
        ]);
        Vehiculo::create([
            "id" => 1,
            "capacidad_en_toneladas" => 5
        ]);
        Pickup::create([
            "id_vehiculo" => 1
        ]);
        PaqueteAsignadoAPickup::create([
            "id_paquete" => 2,
            "id_pickup" => 1
        ]);

        Paquete::create([
            "id" => 3,
            "peso_en_kg" => 5,
            "email" => "prueba@email.com",
            "destino" => 1
        ]);
        Vehiculo::create([
            "id" => 2,
            "capacidad_en_toneladas" => 5
        ]);
        Pickup::create([
            "id_vehiculo" => 2
        ]);
        PaqueteAsignadoAPickup::create([
            "id_paquete" => 3,
            "id_pickup" => 2
        ]);
        User::factory([
            "id" => 1
        ]) -> create();
        Persona::create([
            "id" => 1,
            "nombre" => "Una",
            "apellido" => "Prueba"
        ]);
        Conductor::create([
            "id" => 1
        ]);
        ConductorManeja::create([
            "id_conductor" => 1,
            "id_vehiculo" => 2
        ]);

        Lote::create([
            "id" => 1,
            "destino" => 1
        ]);
        Paquete::create([
            "id" => 4,
            "peso_en_kg" => 5,
            "email" => "prueba@email.com",
            "destino" => 1
        ]);
        LoteFormadoPor::create([
            "id_paquete" => 4,
            "id_lote" => 1
        ]);
        Vehiculo::create([
            "id" => 3,
            "capacidad_en_toneladas" => 5
        ]);
        Camion::create([
            "id_vehiculo" => 3
        ]);
        LoteAsignadoACamion::create([
            "id_lote" => 1,
            "id_camion" => 3
        ]);
        User::factory([
            "id" => 2
        ]) -> create();
        Persona::create([
            "id" => 2,
            "nombre" => "Otra",
            "apellido" => "Persona"
        ]);
        Conductor::create([
            "id" => 2
        ]);
        ConductorManeja::create([
            "id_conductor" => 2,
            "id_vehiculo" => 3
        ]);
    }
}
