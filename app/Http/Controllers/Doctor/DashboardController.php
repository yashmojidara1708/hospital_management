<?php

namespace App\Http\Controllers\Doctor;

use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Appointments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $DoctorData = session('doctors_data');
        $DoctorEmail = isset($DoctorData['email']) ? $DoctorData['email'] : '';
        // Fetch appointments with patient details
        $appointments = Appointments::join('Patients', 'appointments.patient', '=', 'Patients.patient_id')
            ->join('doctors', 'appointments.doctor', '=', 'doctors.id')
            ->select(
                'appointments.id',
                'appointments.doctor',
                'appointments.specialization',
                'appointments.date',
                'appointments.time',
                'appointments.status',
                'Patients.name as patient_name',
                'Patients.paid as amount',
                'Patients.phone as phone',
                'Patients.last_visit as last_visit',
                'Patients.patient_id',
                'Patients.phone',
                'Patients.email',
                'doctors.name as doctor_name',
                'doctors.email as doctor_email'
            )
            ->where('doctors.email', $DoctorEmail)
            ->where('appointments.status', '1')
            ->orderBy('appointments.date', 'asc')
            ->get();
        // Pass the data to the view
        return view('doctor.dashboard.index', compact('appointments'));
    }
}
