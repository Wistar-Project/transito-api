<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LoteFormadoPor;

class Paquete extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "peso_en_kg",
        "email",
        "destino"
    ];

    public function lote(){
        return $this -> hasOne(LoteFormadoPor::class, "id_paquete");
    }
}
