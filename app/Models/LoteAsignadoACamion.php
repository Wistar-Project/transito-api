<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteAsignadoACamion extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = "id_lote";
    protected $table = "lote_asignado_a_camion";
    protected $fillable = [
        "id_lote",
        "id_camion"
    ];

    public function conductor(){
        return $this -> hasOne(ConductorManeja::class, "id_vehiculo", "id_camion");
    }

    public function camion(){
        return $this -> hasOne(Camion::class, "id_vehiculo", "id_camion");
    }

    public function lote(){
        return $this -> hasOne(Lote::class, "id", "id_lote");
    }
}
