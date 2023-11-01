<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConductorManeja extends Model
{
    use HasFactory;
    protected $table = "conductor_maneja";
    protected $fillable = [
        "id_conductor",
        "id_vehiculo"
    ];
    public $timestamps = false;

    public function persona()
    {
        return $this->hasOne(Persona::class, "id");
    }
}
