<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use Illuminate\Support\Facades\Auth;
class BillController extends Controller
{
    //
    public function storebill(Request $request)
    {
        $existingBill = Bill::where('admitted_id', $request->admitted_id)->first();
        if ($existingBill) {
            return response()->json(['status' =>0,'message'=>'Bill has already been generated!']);
        }

        $bill = Bill::create([
            'admitted_id' => $request->admitted_id,
            'patient_id' => $request->patient_id,
            'doctor_id'=>$request->doctor_id,
            'room_number' => $request->room_number,
            'admission_date'=>$request->admission_date,
            'discharge_date' => $request->discharge_date,
            'total_days'=>$request->total_days,
            'room_charge' => $request->room_charge,
            'doctor_fees'=>$request->doctor_fee,
            'discount' => $request->discount,
            'discount_amount'=>$request->discount_amount_hidden,
            'total_amount' => $request->total_amount,
            'generated_by'    =>Auth::user()->id 
        ]);
        return response()->json(['status' => 1, 'message' => 'Bill Saved Successfully!!']);

    }
}
