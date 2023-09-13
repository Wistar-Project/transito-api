<?php

namespace App\Http\Controllers;

use App\Models\ConductorManeja;
use App\Models\LoteAsignadoACamion;
use App\Models\LoteFormadoPor;
use App\Models\Paquete;
use App\Models\PaqueteAsignadoAPickup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaqueteController extends Controller
{
    public function ObtenerEstado(Request $request, $idPaquete){
        $destino = Paquete::findOrFail($idPaquete) -> destino;
        $estado = "No está en trayecto";
        $idConductor = "N/A";
        $loteYPaquete = LoteFormadoPor::find($idPaquete);
        if($loteYPaquete == null){
            $idPickupAsignada = $this -> obtenerIdPickup($idPaquete);
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
        $idLote = $loteYPaquete -> id_lote;
        $idCamionAsignado = $this -> obtenerIdCamion($idLote);
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

    private function obtenerIdCamion($idLote){
        $loteYCamion = LoteAsignadoACamion::find($idLote);
        if($loteYCamion != null)
            return $loteYCamion -> id_camion;
        $HTTP_NOT_FOUND = 404;
        abort(response() -> json([
            "message" => "El paquete no está asignado a ningun camión."
        ], $HTTP_NOT_FOUND));
    }

    private function obtenerIdPickup($idPaquete){
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
