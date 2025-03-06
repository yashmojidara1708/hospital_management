<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Patients;
use App\Models\Specialities;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class MedicinesController extends Controller
{
    public function index()
    {
        return view('admin.medicines.index');
    }

    public function save(Request $request)
    {
        $post = $request->post();
        $hid = isset($post['hid']) ? intval($post['hid']) : null;
        $response['status'] = 0;
        $response['message'] = "Somthing Gose Wrong!";

        $feilds = [
            'name' => $request->name,
            'expiry_date' => $request->expiry_date,
            'price' => $request->price,
            'stock' => $request->stock,
        ];

        // Validation rules
        $rules = [
            'name' => 'required',
            'expiry_date' => 'required|date',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ];

        // Custom error messages
        $msg = [
            'name.required' => 'Please enter the medicines name',
            'expiry_date.required' => 'Please select the expiry date',
            'expiry_date.date' => 'Please enter a valid date',
            'price.required' => 'Please enter the amount price',
            'price.numeric' => 'price amount must be a number',
            'stock.required' => 'Please enter the amount stock',
            'stock.numeric' => 'stock amount must be a number',
        ];

        $validator = Validator::make(
            $feilds,
            $rules,
            $msg
        );

        if (!$validator->fails()) {
            $NameexistingQuery = Medicine::where('name', $request->name)
                ->where('isdeleted', '!=', 1);

            // hid the current record from the check if updating
            if ($hid) {
                $NameexistingQuery->where('id', '!=', $hid);
            }

            if ($NameexistingQuery->exists()) {
                return response()->json([
                    'status' => 0,
                    'message' => 'The Medicines already exists.',
                ]);
            }
            $insert_medicin_data = [
                'name' => isset($post['name']) ? $post['name'] : "",
                'expiry_date' => isset($post['expiry_date']) ? $post['expiry_date'] : "",
                'price' => isset($post['price']) ? $post['price'] : "",
                'stock' => isset($post['stock']) ? $post['stock'] : "",
            ];
            if ($hid) {
                // Update existing record
                $Medicine = Medicine::where('id', $hid)->first();
                if ($Medicine) {
                    $Medicine->update($insert_medicin_data);
                    $response['status'] = 1;
                    $response['message'] = "Medicines updated successfully!";
                } else {
                    $response['message'] = "Medicines not found!";
                }
            } else {
                // Create new record
                if (Medicine::create($insert_medicin_data)) {
                    $response['status'] = 1;
                    $response['message'] = "Medicines added successfully!";
                } else {
                    $response['message'] = "Failed to add Medicines!";
                }
            }
        }
        return response()->json($response);
        exit;
    }
    // // // List Show
    public function medicineslist()
    {
        $medicines_data = Medicine::select('*')->where('isdeleted', '!=', 1)->get();
        return Datatables::of($medicines_data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $action = '<div class="dropdown dropup d-flex justify-content-center">
                            <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="bx bx-dots-vertical-rounded"></i>
                             </button>
                             <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                 <a class="teamroleDelete dropdown-item" href="javascript:void(0);" data-id="' . $row->id . '">
                                     <i class="bx bx-trash me-1"></i> Delete
                                 </a>
                             </div>
                           </div>
                           <div class="actions text-center">
                             <a class="btn btn-sm bg-success-light" data-toggle="modal" href="javascript:void(0);" id="edit_medicines" data-id="' . $row->id . '">
                                   <i class="fe fe-pencil"></i>
                               </a>
                               <a data-toggle="modal" class="btn btn-sm bg-danger-light" href="javascript:void(0);" id="delete_medicines" data-id="' . $row->id . '">
                                   <i class="fe fe-trash"></i>
                               </a>
                           </div>';

                return $action;
            })
            ->rawColumns(['action']) // Ensure HTML is not escaped
            ->make(true);
    }

    public function delete(Request $request)
    {
        $post = $request->post();
        $id = isset($post['id']) ? $post['id'] : "";
        $response['status']  = 0;
        $response['message']  = "Somthing Goes Wrong!";
        if (is_numeric($id)) {
            $delete_medicines = Medicine::where('id', $id)->update(['isdeleted' => 1]);
            if ($delete_medicines) {
                $response['status'] = 1;
                $response['message'] = 'Medicine deleted successfully.';
            } else {
                $response['message'] = 'something went wrong.';
            }
        }
        echo json_encode($response);
        exit;
    }

    public function edit(Request $request)
    {
        $id = $request->query('id');
        // Initialize response
        $response = [
            'status' => 0,
            'message' => 'Something went wrong!'
        ];

        // Check if ID is valid
        if (is_numeric($id)) {
            $medicines_data = Medicine::where('id', $id)->first();
            if ($medicines_data) {
                $response = [
                    'status' => 1,
                    'medicines_data' => $medicines_data
                ];
            }
        }

        return response()->json($response);
        exit; // Proper JSON response
    }
}
