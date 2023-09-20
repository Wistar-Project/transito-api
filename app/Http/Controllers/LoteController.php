<?php

namespace App\Http\Controllers;

use App\Models\ConductorManeja;
use App\Models\Lote;
use App\Models\LoteAsignadoACamion;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    public function ObtenerEstado(Request $request, $idLote){
        $lote = Lote::findOrFail($idLote);
        $idConductor = null;
        $camionAsignado = LoteAsignadoACamion::findOrFail($lote -> id);
        if($camionAsignado -> conductor != null)
            $idConductor = $camionAsignado -> conductor -> id_conductor;
        return [
            "id_lote" => $lote -> id,
            "id_camion_asignado" => $camionAsignado -> id_camion,
            "id_conductor" => $idConductor,
            "destino" => $lote -> destino
        ];
    }
}
