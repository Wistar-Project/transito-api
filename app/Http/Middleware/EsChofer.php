<?php

namespace App\Http\Middleware;

use App\Models\PersonaRol;
use Closure;
use Illuminate\Http\Request;

class EsChofer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request -> user();
        $rol = PersonaRol::findOrFail($user->id) -> rol;
        if($rol != "conductor") return response([
            "message" => "No tienes permiso para ver esto."
        ], 401);
        return $next($request);
    }
}
