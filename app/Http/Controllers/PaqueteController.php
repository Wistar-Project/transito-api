<?php

namespace App\Http\Controllers;

use App\Models\Alojamiento;
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
        if(!$paquete -> pickup && (!$paquete -> lote || !$paquete -> lote -> camion)){
            $BAD_REQUEST_HTTP = 400;
            return abort($BAD_REQUEST_HTTP, "El paquete no está asignado a un vehículo");
        }
        if($paquete -> pickup)
            return $this -> estadoSinLote($paquete);
        return $this -> estadoConLote($paquete);
    }

    private function estadoConLote($paquete){
        $conductor = $paquete -> lote -> camion -> conductor -> persona ?? "Ninguno";
        if($conductor != "Ninguno")
            $conductor = $conductor -> nombre . " " . $conductor -> apellido;
        return [
            "destino" => $paquete -> alojamiento -> direccion,
            "paqueteId" => $paquete -> id,
            "vehiculoAsignado" => "Camión " . $paquete -> lote -> camion -> id_camion,
            "conductor" => $conductor,
            "estado" => $conductor == "Ninguno" ? "En espera" : "En trayecto",
            "loteAsignado" => $paquete -> lote -> id_lote
        ];
    }

    private function estadoSinLote($paquete){
        $conductor = $paquete -> pickup -> conductor -> persona ?? "Ninguno";
        if($conductor != "Ninguno")
            $conductor = $conductor -> nombre . " " . $conductor -> apellido;
        return [
            "destino" => $paquete -> alojamiento -> direccion,
            "paqueteId" => $paquete -> id,
            "vehiculoAsignado" => "Pickup " . $paquete -> pickup -> id_pickup,
            "conductor" => $conductor,
            "estado" => $conductor == "Ninguno" ? "En espera" : "En trayecto"
        ];
    }
}
