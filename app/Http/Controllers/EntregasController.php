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
        $lotesAsignados = LoteAsignadoACamion::where('id_camion', $idCamion)->withTrashed()->get();

        $lotesAsignadosConEseDestino = [];
        foreach ($lotesAsignados as $loteAsignado) {
            if ($loteAsignado->lote->destino == $idSede)
                array_push($lotesAsignadosConEseDestino, $loteAsignado->id_lote);
        }
        return $lotesAsignadosConEseDestino;
    }

    private function descargaDePaquetes($idPickup, $idSede)
    {
        $paquetesAsignados = PaqueteAsignadoAPickup::where('id_pickup', $idPickup)->withTrashed()->get();
        $paquetesConEseDestino = [];
        foreach ($paquetesAsignados as $paqueteAsignado) {
            if ($paqueteAsignado->paquete->alojamiento->id == $idSede)
                array_push($paquetesConEseDestino, $paqueteAsignado->id_paquete);
        }
        return $paquetesConEseDestino;
    }

    public function Mostrar(Request $request)
    {
        $vehiculoAsignado = ConductorManeja::findOrFail($request->user()->id);
        if ($vehiculoAsignado->tipo->tipo == "camión")
            return $this->mostrarDeCamion($request, $vehiculoAsignado->id_vehiculo);
        return $this->mostrarDePickup($request, $vehiculoAsignado->id_vehiculo);
    }

    private function mostrarDeCamion(Request $request, $idCamion)
    {
        $lotesAsignados = $this->obtenerLotes($request, $idCamion);
        $direccionesDistintas = [];
        $distintosDestinos = [];
        foreach ($lotesAsignados as $loteAsignado) {
            $alojamientoLote = $loteAsignado->lote->alojamiento;
            if (!in_array($alojamientoLote->direccion, $direccionesDistintas)) {
                array_push($direccionesDistintas, $alojamientoLote->direccion);
                array_push($distintosDestinos, [
                    "idDireccion" => $alojamientoLote->id,
                    "direccion" => $alojamientoLote->direccion,
                    "entregada" => $loteAsignado->trashed()
                ]);
            }

        }
        return $distintosDestinos;
    }

    private function mostrarDePickup(Request $request, $idPickup)
    {
        $paquetesAsignados = $this->obtenerPaquetes($request, $idPickup);
        $direccionesDistintas = [];
        $distintosDestinos = [];
        foreach ($paquetesAsignados as $paqueteAsignado) {
            $alojamientoPaquete = $paqueteAsignado->paquete->alojamiento;
            if (!in_array($alojamientoPaquete->direccion, $direccionesDistintas)) {
                array_push($direccionesDistintas, $alojamientoPaquete->direccion);
                array_push($distintosDestinos, [
                    "idDireccion" => $alojamientoPaquete->id,
                    "direccion" => $alojamientoPaquete->direccion,
                    "entregada" => $paqueteAsignado->trashed()
                ]);
            }
        }
        return $distintosDestinos;
    }

    private function obtenerLotes(Request $request, $idCamion)
    {
        $lotesAsignados = LoteAsignadoACamion::where('id_camion', $idCamion);
        return $this->obtenerPendientesOTodos($request, $lotesAsignados);
    }

    private function obtenerPaquetes(Request $request, $idPickup)
    {
        $paquetesAsignados = PaqueteAsignadoAPickup::where('id_pickup', $idPickup);
        return $this->obtenerPendientesOTodos($request, $paquetesAsignados);
    }

    private function obtenerPendientesOTodos(Request $request, $paquetesOLotesAsignados)
    {
        if ($request->get("soloPendientes"))
            return $paquetesOLotesAsignados->get();
        return $paquetesOLotesAsignados->withTrashed()->get();
    }

    public function MarcarEntregada(Request $request, $idDireccion){
        $vehiculoAsignado = ConductorManeja::findOrFail($request->user()->id);
        if($vehiculoAsignado->tipo->tipo == "camión")
            return $this -> marcarLotesEntregados($vehiculoAsignado->id_vehiculo, $idDireccion);
        $this -> marcarPaquetesEntregados($vehiculoAsignado->id_vehiculo, $idDireccion);
    }

    private function marcarLotesEntregados($idCamion, $idDireccion){
        $lotesAsignados = LoteAsignadoACamion::where('id_camion', $idCamion)->get();
        foreach ($lotesAsignados as $loteAsignado){
            if($loteAsignado->lote->alojamiento->id == $idDireccion)
                $loteAsignado->delete();   
        }
    }

    private function marcarPaquetesEntregados($idPickup, $idDireccion){
        $paquetesAsignados = PaqueteAsignadoAPickup::where('id_camion', $idPickup)->get();
        foreach ($paquetesAsignados as $paqueteAsignado){
            if($paqueteAsignado->paquete->alojamiento->id == $idDireccion)
                $paqueteAsignado->delete();
        }
    }
}