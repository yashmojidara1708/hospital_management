<?php

namespace App\Http\Controllers\doctor;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    //
    public function getmedicine()
    {
        $medicine=DB::table('medicines')
                    ->get();
        return response()->json($medicine);
    }
    public function saveprescription()
    {
        return response()->json("function called");
    }
}
