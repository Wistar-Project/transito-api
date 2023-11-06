<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lote extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        "destino"
    ];

    public function camion(): HasOne
    {
        return $this->hasOne(LoteAsignadoACamion::class, "id_lote");
    }

    public function alojamiento()
    {
        return $this->hasOne(Alojamiento::class, "id", "destino");
    }
}
