<?php

namespace App\Http\Controllers\doctor;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PatientController extends Controller
{
    //
    public function patients()
    {
        return view('doctor.Patients.index');
    }
    public function patientprofile($patientId)
    {
        $globalHelper = new GlobalHelper();
        $countries = GlobalHelper::getAllCountries();
        $patient = $globalHelper->getPatientById($patientId);
        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found or deleted.');
        }
        return view('doctor.Patients.patientprofile', compact('patient'));
    }
    public function patientslist()
    {
        $doctorId = Auth::user()->id;
        $patients = DB::table('patients')
            ->join('appointments', 'patients.patient_id', '=', 'appointments.patient')
            ->Join('countries', 'patients.country', '=', 'countries.id')
            ->Join('states', 'patients.state', '=', 'states.id')
            ->Join('cities', 'patients.city', '=', 'cities.id')
            ->where('appointments.doctor', $doctorId)
            ->select(
                'patients.patient_id',
                'patients.name',
                'patients.email',
                'patients.phone',
                'patients.age',
                'patients.last_visit',
                'cities.name as city_name',
                'states.name as state_name',  // Fetch state name
                'countries.name as country_name'
            )
            ->distinct()
            ->get();
        return Datatables::of($patients)
            ->addIndexColumn()
            ->addColumn('address', function ($row) {
                return  $row->city_name . ', ' . $row->state_name . ', ' . $row->country_name;
            })
            ->addcolumn('last_visit', function ($row) {
                return Carbon::parse($row->last_visit)->format('d M Y');
            })
            ->addColumn('name', function ($row) {
                return '<a href="' . route('doctor.patientprofile', $row->patient_id) . '" class="patient-link">' . $row->name . '</a>';
            })
            /* ->addColumn('action', function ($row) {
                $action = '<div class="dropdown dropup d-flex justify-content-center">
                            <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="bx bx-dots-vertical-rounded"></i>
                             </button>
                             <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                 <a class="teamroleDelete dropdown-item" href="javascript:void(0);" data-id="' . $row->patient_id . '">
                                     <i class="bx bx-trash me-1"></i> Delete
                                 </a>
                             </div>
                           </div>
                           <div class="actions text-center">
                           <a class="btn btn-sm bg-success-light" data-toggle="modal" href="javascript:void(0);" id="patientsEdit" data-id="' . $row->patient_id . '">
                                   <i class="fe fe-pencil"></i>
                               </a>
                               <a data-toggle="modal" class="btn btn-sm bg-danger-light" href="javascript:void(0);" id="delete_patients" data-id="' . $row->patient_id . '">
                                   <i class="fe fe-trash"></i>
                               </a>
                           </div>';

                return $action;
            })*/
            ->rawColumns(['address', 'last_visit', 'name']) // Ensure HTML is not escaped
            ->make(true);
    }
    public function fetchAppointments($id)
    {
        $appointments = DB::table('appointments')
            ->join('patients', 'patients.patient_id', '=', 'appointments.patient')
            ->join('doctors', 'doctors.id', '=', 'appointments.doctor')
            ->where('appointments.patient', $id)
            ->select(
                'appointments.date',
                'patients.last_visit',
                'doctors.name',
                'doctors.image',
                'appointments.status'
            )
            ->get();
        return response()->json($appointments);
    }
}
