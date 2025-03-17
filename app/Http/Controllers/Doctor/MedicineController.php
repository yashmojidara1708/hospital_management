<?php

namespace App\Http\Controllers\doctor;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Prescriptions;
use App\Models\PrescriptionsItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    //
    public function getmedicine()
    {
        $medicine = DB::table('medicines')->get();
        return response()->json($medicine);
    }
    public function saveprescription(Request $request)
    {
        $prescription = Prescriptions::create([
            'doctor_id' => Auth::id(),
            'patient_id' => $request->patient_id,
            'instructions' => $request->instructions,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Save medicines in `prescription_items` table
        foreach ($request->medicines as $medicine) {
            foreach ($medicine['medicine_name'] as $medicineName) {  // Loop for multiple names
                // Check if the medicine exists
                $medicineRecord = Medicine::where('id', $medicineName)->first();

                // If medicine doesn't exist, insert it and get the ID
                if (!$medicineRecord) {
                    $medicineRecord = Medicine::create([
                        'name' => $medicineName,  // Assuming 'name' is the correct column
                        'stock' => 10,            // Default stock, modify if needed
                        'price' => 10,            // Default price, modify if needed
                        'expiry_date' => null,   // Default expiry date, modify if needed
                        'status' => 1            // Default status, modify if needed
                    ]);
                }
                PrescriptionsItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_name' => $medicineRecord->id,  // Now handles multiple entries
                    'quantity' => $medicine['quantity'],
                    'days' => $medicine['days'],
                    'time' => isset($medicine['time'])
                        ? implode(', ', $medicine['time'])
                        : null
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Prescription saved successfully!',
            'patient_id' => $request->patient_id
        ]);
    }
}
