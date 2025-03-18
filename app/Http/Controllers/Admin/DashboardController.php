<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Appointments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tables = ['doctors', 'staff', 'patients'];
        $counts = [];

        foreach ($tables as $table) {
            $counts[$table] = DB::table($table)
                ->where('isdeleted', '!=', 1)
                ->count();
        }

        return view('admin.dashboard.index', compact('counts'));
        // return view('admin.dashboard.index');
    }

    public function fetchUpdatedAppointments()
    {
        $DoctorData = session('doctors_data');
        $DoctorEmail = isset($DoctorData['email']) ? $DoctorData['email'] : '';

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
            ->where('appointments.is_completed', '0')
            ->orderBy('appointments.date', 'asc')
            ->get();

        return response()->json(['status' => 'success', 'appointments' => $appointments]);
    }

    public function updateAppointmentStatus(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['appointmentId'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid request'], 400);
        }
 
        // Find the appointment
        $appointment = Appointments::find($data['appointmentId']);
  
 
        if (!$appointment) {
            return response()->json(['status' => 'error', 'message' => 'Appointment not found'], 404);
        }
    
        // Update status
        $appointment->is_completed = $data['is_completed'];
        $appointment->save();
    
        return response()->json(['status' => 'success', 'message' => 'Appointment status updated']);
    }

}
