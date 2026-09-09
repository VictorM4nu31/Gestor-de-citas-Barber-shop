<?php

namespace App\Http\Controllers;

use App\Models\Servicio;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::publicadosOrdenados()->get();

        return view('usuario.publico.servicios-index', compact('servicios'));
    }

    public function show(Servicio $servicio)
    {
        return view('usuario.publico.servicios-show', compact('servicio'));
    }
}
