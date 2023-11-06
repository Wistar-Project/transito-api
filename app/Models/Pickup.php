<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pickup extends Model
{
    use HasFactory;
    protected $fillable = [
        "id_vehiculo"
    ];
    public $timestamps  = false;

    public function conductor()
    {
        return $this->hasOne(ConductorManeja::class, "id_vehiculo");
    }
}
