<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function create(Request $request)
    {
        $date = $request->query('date');
        return view('appointments.create', compact('date'));
    }

    public function store(Request $request)
    {
        $appointment = new Appointment();
        $appointment->date = $request->date;
        $appointment->time = $request->time;
        $appointment->description = $request->description;
        $appointment->save();

        return redirect()->route('calendar.index')->with('success', 'Appointment created successfully.');
    }
}
