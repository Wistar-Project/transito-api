<?php

namespace Database\Seeders;

use App\Models\Alojamiento;
use App\Models\Conductor;
use App\Models\ConductorManeja;
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
    }
}
