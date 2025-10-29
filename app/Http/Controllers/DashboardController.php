<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Redirigir según el rol del usuario
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('barbero')) {
            return redirect()->route('barbero.dashboard');
        } elseif ($user->hasRole('usuario')) {
            return view('dashboard'); // Dashboard para usuarios normales
        }

        // Si no tiene rol específico, mostrar vista genérica
        return view('dashboard');
    }
}
