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
    // public function saveprescription(Request $request)
    // {
    //     $prescription = Prescriptions::create([
    //         'doctor_id' => Auth::id(),
    //         'patient_id' => $request->patient_id,
    //         'instructions' => $request->instructions,
    //         'created_at' => now(),
    //         'updated_at' => now()
    //     ]);

    //     // Save medicines in `prescription_items` table
    //     foreach ($request->medicines as $medicine) {
    //         foreach ($medicine['medicine_name'] as $medicineName) {  // Loop for multiple names
    //             // Check if the medicine exists
    //             $medicineRecord = Medicine::where('id', $medicineName)->first();

    //             // If medicine doesn't exist, insert it and get the ID
    //             if (!$medicineRecord) {
    //                 $medicineRecord = Medicine::create([
    //                     'name' => $medicineName,  // Assuming 'name' is the correct column
    //                     'stock' => 10,            // Default stock, modify if needed
    //                     'price' => 10,            // Default price, modify if needed
    //                     'expiry_date' => null,   // Default expiry date, modify if needed
    //                     'status' => 1            // Default status, modify if needed
    //                 ]);
    //             }
    //             PrescriptionsItem::create([
    //                 'prescription_id' => $prescription->id,
    //                 'medicine_name' => $medicineRecord->id,  // Now handles multiple entries
    //                 'quantity' => $medicine['quantity'],
    //                 'days' => $medicine['days'],
    //                 'time' => isset($medicine['time'])
    //                     ? implode(', ', $medicine['time'])
    //                     : null
    //             ]);
    //         }
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Prescription saved successfully!',
    //         'patient_id' => $request->patient_id
    //     ]);
    // }

    public function saveprescription(Request $request)
    {
        // Check if updating an existing prescription
        if ($request->prescription_id) {
            $prescription = Prescriptions::find($request->prescription_id);
            if (!$prescription) {
                return response()->json(['status' => 'error', 'message' => 'Prescription not found'], 404);
            }

            // Update prescription details
            $prescription->update([
                'instructions' => $request->instructions,
                'updated_at' => now()
            ]);

            // Remove old medicines
            PrescriptionsItem::where('prescription_id', $prescription->id)->delete();
        } else {
            // Create a new prescription
            $prescription = Prescriptions::create([
                'doctor_id' => Auth::id(),
                'patient_id' => $request->patient_id,
                'instructions' => $request->instructions,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        // Save medicines in `prescription_items` table
        foreach ($request->medicines as $medicine) {
            foreach ($medicine['medicine_name'] as $medicineName) {
                // Check if the medicine exists
                $medicineRecord = Medicine::where('id', $medicineName)->orWhere('name', $medicineName)->first();
                // Only insert if the medicine does not exist
                if (!$medicineRecord) {
                    $medicineRecord = Medicine::create([
                        'name' => $medicineName,
                        'stock' => 10,
                        'price' => 10,
                        'expiry_date' => date('Y-m-d', strtotime('+1 year')),
                        'status' => 1
                    ]);
                }

                // Insert prescription item
                PrescriptionsItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_name' => $medicineRecord->id,
                    'quantity' => $medicine['quantity'],
                    'days' => $medicine['days'],
                    'time' => isset($medicine['time']) ? implode(', ', $medicine['time']) : null
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
