<?php

namespace App\Http\Middleware;

use App\Models\Barbero;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateBarberoState
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $requiredState = 'active'): Response
    {
        $barbero = $request->route('barbero');
        
        if (!$barbero instanceof Barbero) {
            return redirect()->back()->withErrors(['error' => 'Barbero no encontrado.']);
        }

        switch ($requiredState) {
            case 'active':
                if (!$barbero->activo) {
                    return redirect()->back()->withErrors([
                        'estado' => 'Esta operación solo se puede realizar en barberos activos.'
                    ]);
                }
                break;

            case 'inactive':
                if ($barbero->activo) {
                    return redirect()->back()->withErrors([
                        'estado' => 'Esta operación solo se puede realizar en barberos inactivos.'
                    ]);
                }
                break;

            case 'any':
                // No validation needed
                break;

            default:
                return redirect()->back()->withErrors(['error' => 'Estado de validación no válido.']);
        }

        return $next($request);
    }
}