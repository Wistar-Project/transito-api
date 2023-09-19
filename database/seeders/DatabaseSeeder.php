<?php

namespace Database\Seeders;

use App\Models\Alojamiento;
use Illuminate\Database\Seeder;
use App\Models\Paquete;
use App\Models\PaqueteAsignadoAPickup;
use App\Models\Pickup;
use App\Models\Sede;
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

    }
}
