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
        $destino = Paquete::findOrFail($idPaquete) -> destino;
        $loteYPaquete = LoteFormadoPor::find($idPaquete);
        if($loteYPaquete == null)
            return $this -> estadoSinLote($idPaquete, $destino);
        return $this -> estadoConLote($loteYPaquete, $idPaquete, $destino);
    }

    private function estadoConLote($loteYPaquete, $idPaquete, $destino){
        $estado = "No está en trayecto";
        $idConductor = "N/A";
        $idLote = $loteYPaquete -> id_lote;
        $idCamionAsignado = $this -> obtenerIdCamionAsignado($idLote);
        $conductorManeja = ConductorManeja::where("id_vehiculo", $idCamionAsignado) -> first();
        if($conductorManeja != null){
            $estado = "En trayecto";
            $idConductor = $conductorManeja -> id_conductor;
        }
        return [
            "id_paquete" => $idPaquete,
            "id_camion_asignado" => $idCamionAsignado,
            "id_conductor" => $idConductor,
            "estado" => $estado,
            "destino" => $destino,
            "id_lote_asignado" => $idLote
        ];
    }

    private function estadoSinLote($idPaquete, $destino){
        $estado = "No está en trayecto";
        $idConductor = "N/A";
        $idPickupAsignada = $this -> obtenerIdPickupAsignada($idPaquete);
        $conductorManeja = ConductorManeja::where("id_vehiculo", $idPickupAsignada) -> first();
        if($conductorManeja != null){
            $estado = "En trayecto";
            $idConductor = $conductorManeja -> id_conductor;
        }
        return [
            "id_paquete" => $idPaquete,
            "id_pickup_asignada" => $idPickupAsignada,
            "id_conductor" => $idConductor,
            "estado" => $estado,
            "destino" => $destino
        ];
    }

    private function obtenerIdCamionAsignado($idLote){
        $loteYCamion = LoteAsignadoACamion::find($idLote);
        if($loteYCamion != null)
            return $loteYCamion -> id_camion;
        $HTTP_NOT_FOUND = 404;
        abort(response() -> json([
            "message" => "El paquete no está asignado a ningun camión."
        ], $HTTP_NOT_FOUND));
    }

    private function obtenerIdPickupAsignada($idPaquete){
        $pickupYPaquete = PaqueteAsignadoAPickup::find($idPaquete);
        if($pickupYPaquete == null){
            $HTTP_NOT_FOUND = 404;
            abort(response() -> json([
                "message" => "El paquete no está asignado a ninguna pickup."
            ], $HTTP_NOT_FOUND));
        }
        return $pickupYPaquete -> id_pickup;
    }
}
