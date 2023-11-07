<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ConductorManeja;

class PaqueteAsignadoAPickup extends Model
{
    use HasFactory;
    protected $table = "paquete_asignado_a_pickup";
    protected $primaryKey = "id_paquete";
    protected $fillable = [
        "id_paquete",
        "id_pickup"
    ];

    public function conductor(){
        return $this -> hasOne(ConductorManeja::class, "id_vehiculo", "id_pickup");
    }

    public function paquete(){
        return $this -> hasOne(Paquete::class, "id", "id_paquete");
    }
}
