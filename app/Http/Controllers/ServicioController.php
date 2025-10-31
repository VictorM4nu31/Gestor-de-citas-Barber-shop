<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Servicio\StoreRequest;
use App\Http\Requests\Servicio\UpdateRequest;

class ServicioController extends Controller
{
    // Public methods for displaying servicios (no admin functionality)
    public function index()
    {
        $servicios = \App\Models\Servicio::publicadosOrdenados()->get();
        return view('usuario.publico.servicios-index', compact('servicios'));
    }

    public function show(Servicio $servicio)
    {
        return view('usuario.publico.servicios-show', compact('servicio'));
    }
}

