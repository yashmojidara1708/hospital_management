<?php

namespace App\Http\Controllers\Doctor;

use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Appointments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
    public function appointments()
    {
        $doctorId = Auth::user()->id; 

        // Fetch appointments for this doctor
        $appointments = DB::table('appointments')
                        ->where('doctor', $doctorId)     
                        ->leftjoin('patients', 'patients.patient_id', '=', 'appointments.patient')
                        ->join('cities', 'patients.city', '=', 'cities.id')
                        ->join('states', 'cities.state_id', '=', 'states.id')
                        ->join('countries', 'states.country_id', '=', 'countries.id')    
                        ->select(
                            'appointments.*',
                            'patients.name as patient_name',
                            'patients.email as patient_email',
                            'patients.phone as patient_phone',
                            'patients.address as patient_address',
                            'cities.name as city_name',
                            'states.name as state_name',
                            'countries.name as country_name'
                        )
                        ->get();

        return view('doctor.dashboard.appointments',compact('appointments'));
    }
    public function changepassword()
    {
        return view('doctor.dashboard.ChangePassword');
    }
    public function doctorUpdatePassword(Request $request)
    {
        $post = $request->post();
        $hid = isset($post['hid']) ? intval($post['hid']) : null;
        $request->validate([
            'oldpassword' => 'required',
            'newpassword' => 'required|min:8',
            'confirmpassword' => 'required|same:newpassword',
        ],
        $messages = [
            'oldpassword.required' => 'This field is required',
            'newpassword.required' => 'This field is required',
            'newpassword.min' => 'Password must be at least 8 characters long',
           // 'newpassword.different' => 'New password cannot be the same as the old password.',
            'confirmpassword.required' => 'This field is required',
            'confirmpassword.same' => 'Confirm password must match the new password',
        ]);
        $doctor = DB::table('doctors')->where('id', Auth::id())->first();
       // dd($staff);
    if (!$doctor) {
        return response()->json(['status' => 0, 'message' => 'User not found.']);
    }

    if (!Hash::check($request->oldpassword, $doctor->password)) {
        return response()->json(['status' => 0, 'message' => 'The current password is incorrect.']);
    }
     // Ensure old password and new password are not the same
     if (Hash::check($request->newpassword, $doctor->password)) {
        return response()->json(['status' => 0, 'message' => 'New password cannot be the same as the old password.']);
    }

    DB::table('doctors')->where('id', Auth::id())->update([
        'password' => Hash::make($request->newpassword),
    ]);

    return response()->json(['status' => 1, 'message' => 'Password changed successfully.']);

    }
}
