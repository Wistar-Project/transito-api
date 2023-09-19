<?php

namespace Database\Seeders;

use App\Models\Alojamiento;
use Illuminate\Database\Seeder;
use App\Models\Paquete;
use App\Models\Sede;

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
    }
}
