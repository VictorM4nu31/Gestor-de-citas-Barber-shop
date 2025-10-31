<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Barbero;

class CheckActiveBarbero
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
        $user = Auth::user();
        
        // Si el usuario no está autenticado, continuar (será manejado por auth middleware)
        if (!$user) {
            return $next($request);
        }
        
        // Si el usuario tiene rol de barbero, verificar que esté activo
        if ($user->hasRole('barbero')) {
            $barbero = Barbero::where('user_id', $user->id)->first();
            
            // Si no se encuentra el barbero o está inactivo
            if (!$barbero || !$barbero->activo) {
                Auth::logout();
                
                return redirect()->route('login')->with('error', 
                    'Tu cuenta ha sido desactivada. Contacta al administrador para más información.'
                );
            }
        }
        
        return $next($request);
    }
}