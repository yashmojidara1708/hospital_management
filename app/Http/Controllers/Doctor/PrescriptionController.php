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
        return view('doctor.Prescription.index');
    }
}
