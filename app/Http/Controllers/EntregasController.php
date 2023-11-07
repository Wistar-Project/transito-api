<?php

namespace App\Http\Controllers;

use App\Models\Alojamiento;
use App\Models\ConductorManeja;
use App\Models\Lote;
use App\Models\LoteAsignadoACamion;
use App\Models\PaqueteAsignadoAPickup;
use Illuminate\Http\Request;

class EntregasController extends Controller
{

    public function MostrarDescarga(Request $request, $direccion)
    {
        $vehiculoAsignado = ConductorManeja::findOrFail("id_conductor", $request->user()->user_id);
        if($vehiculoAsignado ->tipo->tipo == "camión")
            return $this->descargaDeLotes($vehiculoAsignado->id_vehiculo, $direccion);
        return $this -> descargaDePaquetes($vehiculoAsignado->id_vehiculo, $direccion);
    }

    private function descargaDeLotes($idCamion, $direccion){
        $lotesAsignados = LoteAsignadoACamion::where('id_camion', $idCamion) -> get();
        return array_filter($lotesAsignados, function($loteAsignado) use($direccion){
            return $loteAsignado -> lote -> alojamiento -> direccion == $direccion;
        }) -> pluck("id_lote");
    }

    private function descargaDePaquetes($idPickup, $direccion){
        $paquetesAsignados = PaqueteAsignadoAPickup::where('id_pickup', $idPickup) -> get();
        return array_filter($paquetesAsignados, function($paqueteAsignado) use($direccion){
            return $paqueteAsignado -> paquete -> alojamiento -> direccion == $direccion;
        }) -> pluck("id_pickup");
    }

}