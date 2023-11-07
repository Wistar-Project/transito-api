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

    public function MostrarDescarga(Request $request, $idSede)
    {
        $vehiculoAsignado = ConductorManeja::findOrFail($request->user()->id);
        if ($vehiculoAsignado->tipo->tipo == "camión")
            return $this->descargaDeLotes($vehiculoAsignado->id_vehiculo, $idSede);
        return $this->descargaDePaquetes($vehiculoAsignado->id_vehiculo, $idSede);
    }

    private function descargaDeLotes($idCamion, $idSede)
    {
        $lotesAsignados = LoteAsignadoACamion::where('id_camion', $idCamion)->get();

        $lotesAsignadosConEseDestino = [];
        foreach ($lotesAsignados as $loteAsignado) {
            if ($loteAsignado->lote->destino == $idSede)
                array_push($lotesAsignadosConEseDestino, $loteAsignado->id_lote);
        }
        return $lotesAsignadosConEseDestino;
    }

    private function descargaDePaquetes($idPickup, $idSede)
    {
        $paquetesAsignados = PaqueteAsignadoAPickup::where('id_pickup', $idPickup)->get();
        $paquetesConEseDestino = [];
        foreach ($paquetesAsignados as $paqueteAsignado) {
            if ($paqueteAsignado->paquete->alojamiento->id == $idSede)
                array_push($paquetesConEseDestino, $paqueteAsignado->id_paquete);
        }
        return $paquetesConEseDestino;
    }

}