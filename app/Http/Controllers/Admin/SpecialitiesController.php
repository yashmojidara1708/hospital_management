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
            if (Specialities::create($insert_team_data)) {
                $response['status'] = 1;
                $response['message'] = "Specialiti added Successfully";
            } else {
                $response['message'] = "Fail to Add Specialiti Data!!";
            }
        }
        return response()->json($response);
        exit;
    }

    // List Show
    public function specialitieslist()
    {
        $specialities_data = Specialities::select('*')->get();
        return Datatables::of($specialities_data)
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
                                 <a class="dropdown-item" href="javascript:void(0);" data-id="' . $row->id . '" id="teamroleEdit">
                                     <i class="bx bx-edit-alt me-1"></i> Edit
                                 </a>
                                 <a class="teamroleDelete dropdown-item" href="javascript:void(0);" data-id="' . $row->id . '">
                                     <i class="bx bx-trash me-1"></i> Delete
                                 </a>
                             </div>
                           </div>
                           <div class="actions text-right">
                               <a class="btn btn-sm bg-success-light" data-toggle="modal" href="#edit_specialities_details">
                                   <i class="fe fe-pencil"></i> Edit
                               </a>
                               <a data-toggle="modal" href="#delete_modal" class="btn btn-sm bg-danger-light">
                                   <i class="fe fe-trash"></i> Delete
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
        $response['msg']  = "Somthing Goes Wrong!";
        if ($id != "") {
            $team_data = Specialities::where("id", $id)->get()->toArray();
            if (empty($team_data)) {
                $team_remove = Specialities::where('id', $id)->delete();
                if ($team_remove) {
                    $response['status']  = 1;
                    $response['msg']  = "Teme Role Deleted";
                }
            } else {
                $response['msg']  = "Role have Attached with Member.";
            }
        }

        echo json_encode($response);
        exit;
    }
}
