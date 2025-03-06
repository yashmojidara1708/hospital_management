<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class SpecialitiesController extends Controller
{
    public function index()
    {
        return view('admin.specialities.index');
    }
    public function toggleStatus(Request $request)
    {
        $specialities = Specialities::find($request->id);
        if ($specialities) {
            $specialities->status = $request->status;
            $specialities->save();
            return response()->json(['message' => 'Status updated successfully.']);
        }
        return response()->json(['message' => 'Specialities not found!'], 404);
    }
    public function save(Request $request)
    {
        $post = $request->post();
        $response['status'] = 0;
        $response['message'] = "Somthing Gose Wrong!";
        $feilds =   [
            'name' => $request->name,
        ];

        $rules = [
            'name' => ['required'],
        ];

        $msg = [
            'name.required' => 'Please enter specialities name',
        ];

        $validator = Validator::make(
            $feilds,
            $rules,
            $msg
        );
        if (!$validator->fails()) {
            $insert_team_data = [
                'name' => isset($post['name']) ? $post['name'] : "",
                'status' => isset($post['status']) ? $post['status'] : "",
            ];
            $id = isset($post['hid']) ? intval($post['hid']) : null;

            $NameexistingQuery = Specialities::where('name', $request->name)
                ->where('isdeleted', '!=', 1);

            // Exclude the current record from the check if updating
            if ($id) {
                $NameexistingQuery->where('id', '!=', $id);
            }

            if ($NameexistingQuery->exists()) {
                return response()->json([
                    'status' => 0,
                    'message' => 'The Specialities already exists.',
                ]);
            }
            if ($id) {
                // Update existing record
                $speciality = Specialities::find($id);
                if ($speciality) {
                    $speciality->update($insert_team_data);
                    $response['status'] = 1;
                    $response['message'] = "Speciality updated successfully!";
                } else {
                    $response['message'] = "Speciality not found!";
                }
            } else {
                // Create new record
                if (Specialities::create($insert_team_data)) {
                    $response['status'] = 1;
                    $response['message'] = "Speciality added successfully!";
                } else {
                    $response['message'] = "Failed to add Speciality!";
                }
            }
        }
        return response()->json($response);
        exit;
    }

    // List Show
    public function specialitieslist()
    {
        $specialities_data = Specialities::select('*')->where('isdeleted', '!=', 1)->get();
        return Datatables::of($specialities_data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                $checked = $row->status ? 'checked' : '';
                return '
                <div class="status-toggle">
                    <input type="checkbox" id="status_' . $row->id . '" class="check toggle-status" data-id="' . $row->id . '" ' . $checked . '>
                    <label for="status_' . $row->id . '" class="checktoggle">checkbox</label>
                </div>';
            })
            ->addColumn('action', function ($row) {
                $action = '<div class="dropdown dropup d-flex justify-content-center">
                            <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="bx bx-dots-vertical-rounded"></i>
                             </button>
                             <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                 <a class="dropdown-item" href="javascript:void(0);" data-id="' . $row->id . '">
                                     <i class="bx bx-edit-alt me-1"></i> Edit
                                 </a>
                                 <a class="teamroleDelete dropdown-item" href="javascript:void(0);" data-id="' . $row->id . '">
                                     <i class="bx bx-trash me-1"></i> Delete
                                 </a>
                             </div>
                           </div>
                           <div class="actions text-center">
                               <a class="btn btn-sm bg-success-light" data-toggle="modal" href="javascript:void(0);" id="specialitiesEdit" data-id="' . $row->id . '">
                                   <i class="fe fe-pencil"></i>
                               </a>
                               <a data-toggle="modal" class="btn btn-sm bg-danger-light" href="javascript:void(0);" id="delete_specialities" data-id="' . $row->id . '">
                                   <i class="fe fe-trash"></i>
                               </a>
                           </div>';

                return $action;
            })
            ->rawColumns(['action', 'status']) // Ensure HTML is not escaped
            ->make(true);
    }

    public function delete(Request $request)
    {
        $post = $request->post();
        $id = isset($post['id']) ? $post['id'] : "";
        $response['status']  = 0;
        $response['message']  = "Somthing Goes Wrong!";
        if (is_numeric($id)) {
            $delete_specialities = Specialities::where('id', $id)->update(['isdeleted' => 1]);
            if ($delete_specialities) {
                $response['status'] = 1;
                $response['message'] = 'Speciality deleted successfully.';
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
            $specialities_data = Specialities::find($id); // Use `find($id)` instead of `where()->get()->first()`

            if ($specialities_data) {
                $response = [
                    'status' => 1,
                    'specialities_data' => $specialities_data
                ];
            }
        }

        return response()->json($response);
        exit; // Proper JSON response
    }
}
