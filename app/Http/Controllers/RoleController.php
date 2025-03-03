<?php

namespace App\Http\Controllers\admin;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
    public function index()
    {
        return view('admin.HMS_Role.index');
    }
    public function list(Request $request)
    {
       if ($request->ajax()) {
            $data = role::get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $checked = $row->status ? 'checked':'';
                    return '<div class="form-check form-switch">
                            <input class="form-check-input toggle-status" 
                            type="checkbox" data-id="' . $row->id . '" ' . $checked . '>
                        </div>';
                })
                ->addColumn('action', function ($row) {
                return '<button class="edit btn btn-info btn-sm"  onclick="editcategory('.$row->id.')"><i class="fas fa-edit"></i></button>
                        <button class="delete btn btn-danger btn-sm" onclick="deletecategory('.$row->id.')"><i class="fa fa-trash" aria-hidden="true"></i>
            </button>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
    }
    public function togglechange(Request $request,string $id)
    {

        $role=role::find($id);
        $role->status = $request->status;
        $role->save();
        return response()->json(['message'=>'Status Updated Successfully!!']);
    }
    public function getRole()
    {
        $role = Roles::get();
        return response()->json($role);
    }
    public function addRole(Request $request)
    {
        // Create a new employee
        $post = $request->post();
        $id = $request['hid'];
        if ($request['hid'] != "") {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
            ], [
                'name.required' => 'The name field is required.',
                'status.required' => 'The status field is required.',
            ]);
            $update_about = role::where("id", $id);
            $update_data = [
                "name" => isset($post['name']) ? $post['name'] : "",
                "status" => isset($post['status']) ? $post['status'] : "",
            ];
            $update_about->update($update_data);
            return response()->json(['success' => true, 'message' => 'Role updated successfully!']);
        } else {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
                // Ensure skills is an array
            ], [
                'name.required' => 'The name field is required.',
                'status.required' => 'The status field is required.',

            ]);
            role::create([
                'name' => $request->name,
                'status' => $request->status,
            ]);
            return response()->json(['success' => true, 'message' => 'Role created successfully!']);
        }
    }
    public function deletedata(string $id)
    {
        $role = roles::find($id);
        $role->delete();
        return response()->json(['success' => true, 'message' => 'Role deleted successfully!']);
    }
    public function editdata(string $id)
    {
        $role = role::find($id);
        return response()->json($role);
    }
}
