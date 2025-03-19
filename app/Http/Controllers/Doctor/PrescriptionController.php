<?php

namespace App\Http\Controllers\doctor;

use Yajra\DataTables\DataTables;
use App\Helpers\GlobalHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Prescriptions;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $patient_id = $request->patient_id;
        $prescription_id = $request->prescription_id;
        
        $allMedicines = DB::table('medicines')->get();
        
        $patient = DB::table('patients')
            ->join('countries', 'patients.country', '=', 'countries.id')
            ->join('states', 'patients.state', '=', 'states.id')
            ->join('cities', 'patients.city', '=', 'cities.id')
            ->select(
                'patients.patient_id',
                'patients.name',
                'patients.email',
                'patients.phone',
                'patients.age',
                'patients.last_visit'
            )->where('patients.patient_id', $patient_id)
            ->first();

        $doctor = DB::table('doctors')
            ->join('cities', 'doctors.city', '=', 'cities.id')
            ->join('states', 'doctors.state', '=', 'states.id')
            ->join('specialities', 'doctors.specialization', '=', 'specialities.id')
            ->select(
                'doctors.id',
                'doctors.name',
                'specialities.name as specialization',
                'cities.name as city',
                'states.name as state'
            )
            ->where('doctors.id', Auth::id())
            ->where('doctors.role', 'doctor')
            ->first();

        $prescription = null;
        // $medicineData = null; 
        $medicineData = [];

        
        if ($prescription_id) {
            $prescription = DB::table('prescriptions')
                ->where('prescriptions.id', $prescription_id)
                ->first();

            $medicines = DB::table('prescriptions_item')
                ->join('medicines', 'prescriptions_item.medicine_name', '=', 'medicines.id')
                ->select(
                    'prescriptions_item.prescription_id',
                    DB::raw('GROUP_CONCAT(medicines.id ORDER BY medicines.id SEPARATOR ",") as medicine_id'),
                    DB::raw('GROUP_CONCAT(medicines.name ORDER BY medicines.id SEPARATOR ",") as medicine_name'),
                    'prescriptions_item.quantity',
                    'prescriptions_item.days',
                    'prescriptions_item.time'
                )
                ->where('prescriptions_item.prescription_id', $prescription_id)
                ->groupBy(
                    'prescriptions_item.prescription_id',
                    'prescriptions_item.quantity', 
                    'prescriptions_item.days', 
                    'prescriptions_item.time'
                )
                ->get();

            if (!$medicines->isEmpty()) {
                foreach ($medicines as $medicine) {
                    $medicineData[] = [
                        'prescription_id' => $medicine->prescription_id,
                        'medicine_id' => explode(',', $medicine->medicine_id), // Convert to array
                        'medicine_name' => explode(',', $medicine->medicine_name),
                        'quantity' => $medicine->quantity,
                        'days' => $medicine->days,
                        'time' => explode(', ', $medicine->time) // Convert time to array
                    ];
                }
            }
        }

        return view('doctor.Prescription.index', compact('doctor', 'patient', 'prescription'))
            ->with('medicineData', $medicineData);
    }

    public function destroy($id)
    {
        $prescription = Prescriptions::find($id);

        if ($prescription) {
            $prescription->update(['isdeleted' => 1]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

    public function edit($id) 
    {
        $prescription = Prescriptions::find($id);
        $patient_id = $prescription->patient_id;
        $doctor_id = $prescription->doctor_id;
       
        $patient = DB::table('patients')
            ->join('countries', 'patients.country', '=', 'countries.id')
            ->join('states', 'patients.state', '=', 'states.id')
            ->join('cities', 'patients.city', '=', 'cities.id')
            ->select(
                'patients.patient_id',
                'patients.name',
                'patients.email',
                'patients.phone',
                'patients.age',
                'patients.last_visit'
            )->where('patients.patient_id', $patient_id)
            ->first();

        $doctor = DB::table('doctors')
            ->join('cities', 'doctors.city', '=', 'cities.id')
            ->join('states', 'doctors.state', '=', 'states.id')
            ->join('specialities', 'doctors.specialization', '=', 'specialities.id')
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

        $data = [
            'doctor' => $doctor,
            'patient' => $patient,
            'prescription' => $prescription
        ];
        return response()->json(['success' => true, 'data' => $data], 200);
    }
}
