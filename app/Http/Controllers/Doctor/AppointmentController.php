<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointments; // Assuming you have an Appointment model

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // This method can be used to display the main dashboard view
        return view('doctor.dashboard.index'); // Adjust the view name as needed
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Fetch and display upcoming appointments.
     */
    public function upcomingAppointments()
    {
        // Fetch upcoming appointments (appointments with a date greater than today)
        $upcomingAppointments = Appointments::where('date', '>', now())
            ->with('patient') // Assuming you have a relationship with the Patient model
            ->get();

        // Return the view with the data
        return view('doctor.dashboard.upcoming_appointments', compact('upcomingAppointments'));
    }

    /**
     * Fetch and display today's appointments.
     */
    public function todayAppointments()
    {
        $todayAppointments = Appointments::whereDate('date', now()->toDateString())
            ->with('patient') 
            ->get();

        return view('doctor.dashboard.today_appointments', compact('todayAppointments'));
    }
}