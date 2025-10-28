<?php

namespace App\Http\Controllers;

use App\Models\Barbero;
use App\Models\Servicio;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function dashboard()
    {
        $barberos = Barbero::all();
        $servicios = Servicio::all();
        return view('admin.dashboard', compact('barberos', 'servicios'));
    }

    public function tableUsers()
    {
        $barberos = Barbero::all();
        return view('admin.barberos.table-users', compact('barberos'));
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
        return view('admin.servicios.manage-services', compact('servicios'));
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
