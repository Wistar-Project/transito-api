<?php

namespace App\Http\Controllers;

use App\Models\ConductorManeja;
use App\Models\Lote;
use App\Models\LoteAsignadoACamion;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    //TODO hacer test con lote o paquete que no existe
    public function ObtenerEstado(Request $request, $idLote){
        $destino = Lote::findOrFail($idLote) -> destino;
        $estado = "No está en trayecto";
        $idConductor = "N/A";
        $idCamionAsignado = $this -> obtenerIdCamionAsignado($idLote);
        $conductorManeja = ConductorManeja::where("id_vehiculo", $idCamionAsignado) -> first();
        if($conductorManeja != null){
            $estado = "En trayecto";
            $idConductor = $conductorManeja -> id_conductor;
        }
        return [
            "id_lote" => $idLote,
            "id_camion_asignado" => $idCamionAsignado,
            "id_conductor" => $idConductor,
            "estado" => $estado,
            "destino" => $destino
        ];
    }

    private function obtenerIdCamionAsignado($idLote){
        $loteYCamion = LoteAsignadoACamion::find($idLote);
        if($loteYCamion != null)
            return $loteYCamion -> id_camion;
        $HTTP_BAD_REQUEST = 400;
        abort(response() -> json([
            "message" => "El paquete no está asignado a ningun camión."
        ], $HTTP_BAD_REQUEST));
    }
}
