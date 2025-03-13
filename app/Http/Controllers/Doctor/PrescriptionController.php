<?php

namespace App\Http\Controllers\doctor;
use Yajra\DataTables\DataTables;
use App\Helpers\GlobalHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    //
    public function index()
    {
        $doctor = DB::table('doctors')
        ->join('cities', 'doctors.city', '=', 'cities.id')
        ->join('states', 'doctors.state', '=', 'states.id')
        ->join('specialities','doctors.specialization','=','specialities.id')
        ->select(
            'doctors.id', 
            'doctors.name', 
            'specialities.name as specialization',
            'cities.name as city', 
            'states.name as state'
        )
        ->where('doctors.id', Auth::id())
        ->where('doctors.role', 'doctor') // Ensure only doctors can access
        ->first();
        return view('doctor.Prescription.index',compact('doctor'));
    }
}
