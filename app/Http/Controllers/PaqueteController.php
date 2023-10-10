<?php

namespace App\Http\Controllers;

use App\Models\ConductorManeja;
use App\Models\LoteAsignadoACamion;
use App\Models\LoteFormadoPor;
use App\Models\Paquete;
use App\Models\PaqueteAsignadoAPickup;
use Illuminate\Http\Request;

class PaqueteController extends Controller
{
    public function ObtenerEstado(Request $request, $idPaquete){
        $paquete = Paquete::findOrFail($idPaquete);
        if($paquete -> lote == null)
            return $this -> estadoSinLote($paquete);
        return $this -> estadoConLote($paquete);
    }

    private function estadoConLote($paquete){
        $idConductor = null;
        $camionAsignado = LoteAsignadoACamion::findOrFail($paquete -> lote -> id_lote);
        $conductorManeja = $camionAsignado -> conductor;
        if($conductorManeja != null)
            $idConductor = $conductorManeja -> id_conductor;
        return [
            "id_paquete" => $paquete -> id,
            "id_camion_asignado" => $camionAsignado -> id_camion,
            "id_conductor" => $idConductor,
            "destino" => $paquete -> destino,
            "id_lote_asignado" => $paquete -> lote -> id_lote
        ];
    }

    private function estadoSinLote($paquete){
        $idConductor = null;
        $pickupAsignada = PaqueteAsignadoAPickup::findOrFail($paquete -> id);
        if($pickupAsignada -> conductor != null){
            $idConductor = $pickupAsignada -> conductor -> id_conductor;
        }
        return [
            "id_paquete" => $paquete -> id,
            "id_pickup_asignada" => $pickupAsignada -> id_pickup,
            "id_conductor" => $idConductor,
            "destino" => $paquete -> destino
        ];
    }
}
