<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteFormadoPor extends Model
{
    use HasFactory;
    protected $table = "lote_formado_por";
    protected $primaryKey = "id_paquete";
    protected $fillable = [
        "id_paquete",
        "id_lote"
    ];

    public function camion(){
        return $this -> hasOne(LoteAsignadoACamion::class, "id_lote", "id_lote");
    }
}
