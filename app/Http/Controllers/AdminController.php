<?php

namespace App\Http\Controllers;

use App\Models\Barbero;
use App\Models\Servicio;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $barberos = \App\Models\Barbero::all();
        $servicios = \App\Models\Servicio::all();
        return view('admin.dashboard', compact('barberos', 'servicios'));
    }

    public function tableUsers()
    {
        $barberos = \App\Models\Barbero::with('user')->get(); // cargar usuario relacionado
        return view('admin.barberos.index', compact('barberos'));
    }

    public function tableUsersCreate()
    {
        return view('admin.barberos.table-users-create');
    }

    public function tableUsersEdit($id)
    {
        $barbero = Barbero::findOrFail($id);
        return view('admin.barberos.table-users-edit', compact('barbero'));
    }

    public function manageServices()
    {
        $servicios = Servicio::all();
        return view('admin.servicios.index', compact('servicios'));
    }

    public function servicesCreate()
    {
        return view('admin.servicios.services-create');
    }

    public function servicesEdit($id)
    {
        $servicio = Servicio::findOrFail($id);
        return view('admin.servicios.services-edit', compact('servicio'));
    }
}
