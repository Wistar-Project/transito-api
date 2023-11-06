<?php

namespace App\Http\Controllers;

use App\Models\ConductorManeja;
use App\Models\Lote;
use App\Models\LoteAsignadoACamion;
use App\Models\LoteFormadoPor;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    public function ObtenerEstado(Request $request, $idLote){
        $lote = Lote::findOrFail($idLote);
        $camionAsignado = LoteAsignadoACamion::findOrFail($lote -> id);
        $conductor = "Ninguno";
        $estado = "En espera";
        if($camionAsignado -> conductor != null){
            $estado = "En trayecto";
            $conductor = $camionAsignado -> conductor -> id_conductor -> persona;
            $conductor = $conductor -> nombre . " " . $conductor -> apellido;
        }
        return [
            "idLote" => $lote -> id,
            "estado" => $estado,
            "camionAsignado" => $camionAsignado -> id_camion,
            "conductor" => $conductor,
            "destino" => $lote -> alojamiento -> direccion,
            "lotes" => LoteFormadoPor::where("id_lote", $idLote) -> pluck("id_paquete")
        ];
    }
}
