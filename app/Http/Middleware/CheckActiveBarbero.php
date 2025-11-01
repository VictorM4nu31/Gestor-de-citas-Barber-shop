<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Barbero;

class CheckActiveBarbero
{

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        if (!$user) {
            return $next($request);
        }
        
        if ($user->hasRole('barbero')) {
            $barbero = Barbero::where('user_id', $user->id)->first();
            
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