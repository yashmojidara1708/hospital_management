<?php

namespace App\Http\Controllers\admin;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    //
    public function index()
    {
        return view('admin.HMS_Role.index');
    }
    public function rolelist(Request $request)
    {
        $role_data = role::select('*')->where('status', '!=', -1)->get();
        return Datatables::of($role_data)
            ->addIndexColumn()
            ->addColumn('status', function ($data) {
                $status = $data->status == 1
                    ? '<span class="badge badge-pill bg-success inv-badge">Active</span>'
                    : '<span class="badge badge-pill bg-danger inv-badge ">Inactive</span>';
                return $status;
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
                               <a class="btn btn-sm bg-success-light" data-toggle="modal" href="javascript:void(0);" id="edit_role" data-id="' . $row->id . '">
                                   <i class="fe fe-pencil"></i>
                               </a>
                               <a data-toggle="modal" class="btn btn-sm bg-danger-light" href="javascript:void(0);" id="delete_role" data-id="' . $row->id . '">
                                   <i class="fe fe-trash"></i>
                               </a>
                           </div>';

                return $action;
            })
            ->rawColumns(['action', 'status']) // Ensure HTML is not escaped
            ->make(true);
    
    }
    public function togglechange(Request $request,string $id)
    {

        $role=role::find($id);
        $role->status = $request->status;
        $role->save();
        return response()->json(['message'=>'Status Updated Successfully!!']);
    }
    
        // Create a new role
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
                'name.required' => 'Please enter role name',
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
                if ($id) {
                    // Update existing record
                    $role = role::find($id);
                    if ($role) {
                        $role->update($insert_team_data);
                        $response['status'] = 1;
                        $response['message'] = "Role updated successfully!";
                    } else {
                        $response['message'] = "Role not found!";
                    }
                } else {
                    // Create new record
                    if (role::create($insert_team_data)) {
                        $response['status'] = 1;
                        $response['message'] = "Role added successfully!";
                    } else {
                        $response['message'] = "Failed to add Role!";
                    }
                }
            }
            return response()->json($response);
            exit;
        }
        public function delete(Request $request)
        {
            $post = $request->post();
            $id = isset($post['id']) ? $post['id'] : "";
            $response['status']  = 0;
            $response['message']  = "Somthing Goes Wrong!";
            if (is_numeric($id)) {
                $delete_role = role::where('id', $id)->update(['status' => -1]);
                if ($delete_role) {
                    $response['status'] = 1;
                    $response['message'] = 'Role deleted successfully.';
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
            $role_data = role::find($id); // Use `find($id)` instead of `where()->get()->first()`

            if ($role_data) {
                $response = [
                    'status' => 1,
                    'role_data' => $role_data
                ];
            }
        }

        return response()->json($response);
        exit; // Proper JSON response
    }
}
