<?php

namespace App\Http\Controllers\Doctor;
use Yajra\DataTables\DataTables;
use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Appointments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
        // Pass the data to the view
        $totalAppointments = Appointments::join('doctors', 'appointments.doctor', '=', 'doctors.id')
        ->where('doctors.email', $DoctorEmail)
        ->count();
        $totalPatientCount = Appointments::where('doctor', Auth::user()->id)
                            ->distinct('patient')
                            ->count('patient');
        $todayPatients = Appointments::where('doctor', Auth::user()->id)
        ->whereDate('date', Carbon::today())
        ->distinct('patient')
        ->count('patient');

        return view('doctor.dashboard.index', compact('appointments','totalAppointments','totalPatientCount','todayPatients'));
    }
    public function appointments()
    {
        $doctorId = Auth::user()->id; 

        // Fetch appointments for this doctor
        $appointments = DB::table('appointments')
                        ->where('doctor', $doctorId)    
                        ->where('appointments.is_completed', '0')
                        ->leftjoin('patients', 'patients.patient_id', '=', 'appointments.patient')
                        ->join('cities', 'patients.city', '=', 'cities.id')
                        ->join('states', 'cities.state_id', '=', 'states.id')
                        ->join('countries', 'states.country_id', '=', 'countries.id')    
                        ->select(
                            'appointments.*',
                            'patients.name as patient_id',
                            'patients.name as patient_name',
                            'patients.email as patient_email',
                            'patients.phone as patient_phone',
                            'patients.address as patient_address',
                            'cities.name as city_name',
                            'states.name as state_name',
                            'countries.name as country_name'
                        )
                        ->orderBy('date', 'asc')
                        ->get();

        return view('doctor.Appointments.appointments',compact('appointments'));
    }
    
    public function getAppointmentDetails(Request $request)
    {
            $id = $request->id;
        
            // Fetch the appointment details
            $appointment = DB::table('appointments')->where('id', $id)->first();
        
            if ($appointment) {
                return response()->json([
                    'success' => true,
                    'data' => $appointment
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointment not found'
                ]);
            }
        }
  
}
