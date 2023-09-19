<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaqueteAsignadoAPickup extends Model
{
    use HasFactory;
    protected $table = "paquete_asignado_a_pickup";
    protected $primaryKey = "id_paquete";
    protected $fillable = [
        "id_paquete",
        "id_pickup"
    ];
}
