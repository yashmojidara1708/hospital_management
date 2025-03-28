<?php

namespace App\Http\Controllers\Admin;

use App\Models\Rooms;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Helpers\GlobalHelper;

class RoomsController extends Controller
{
    //
    public function index()
    {
        $categories= GlobalHelper::getAllRoomCategory();
        return view('admin.Rooms.index',compact('categories'));
    }
    public function save(Request $request)
    {
        $post = $request->post();
      // dd($post);
        $hid = isset($post['hid']) ? intval($post['hid']) : null;
        $response['status'] = 0;
        $response['message'] = "Somthing Gose Wrong!";
        $feilds =   [
            'category_id' => $request->category,
            'total_rooms' => $request->total_rooms,
            'beds_per_room' => $request->beds_per_room,
            'charges_per_bed' => $request->charges,
            'status' => $request->status,
        ];

        $rules = [
             'category_id' => 'required',
             'total_rooms' => 'required|numeric',
             'beds_per_room' => 'required|numeric',
             'charges_per_bed' => 'required|numeric',
             'status' => 'required',
        ];

        $msg = [
            'category_id.required' => 'Please Select Category id',
            'total_rooms.required' => 'Please enter Total number of rooms',
            'total_rooms.numeric' => 'Rooms must be a number',
            'beds_per_room.required' => 'Please enter Beds for per room',
            'beds_per_room.numeric' => 'Beds per room must be a number',
            'charges_per_bed.required' => 'Please enter the charges for per bed',
            'charges_per_bed.numeric' => 'charges must be a number',
            'status.required' => 'Please select status',
            
          //  'name.unique' => 'This role name is already taken. Please choose a different name.'
        ];

        $validator = Validator::make(
            $feilds,
            $rules,
            $msg
        );
        if (!$validator->fails()) {

            $existingRoom=Rooms::where('category_id', $request->category)
                        ->where('total_rooms', $request->total_rooms)
                        ->where('beds_per_room', $request->beds_per_room)
                        ->where('charges_per_bed', $request->charges)
                        ->where('status',$request->status);
    
            if ($hid) {
                $existingRoom->where('id', '!=', $hid); // Exclude current record during update
            }
        
            if ($existingRoom->exists()) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Duplicate room entry! This room already exists.',
                ]);
            }
        
            $insert_room_data = [
                'category_id' => isset($post['category']) ? $post['category'] : "",
                'total_rooms' => isset($post['total_rooms']) ? $post['total_rooms'] : "",
                'beds_per_room' => isset($post['beds_per_room']) ? $post['beds_per_room'] : "",
                'charges_per_bed' => isset($post['charges']) ? $post['charges'] : "",
                'status' => isset($post['status']) ? $post['status'] : "",
                
            ];
            if ($hid) {
                // Update existing record
                $room = Rooms::find($hid);
                if ($room) {
                    $room->update($insert_room_data);
                    $response['status'] = 1;
                    $response['message'] = "Room Details updated successfully!";
                } else {
                    $response['message'] = "Room Details not found!";
                }
            } else {
                // Create new record
                if (Rooms::create($insert_room_data)) {
                    $response['status'] = 1;
                    $response['message'] = "Room added successfully!";
                } else {
                    $response['message'] = "Failed to add Room !";
                }
            }
        }
        return response()->json($response);
        exit;

    }

    public function toggleStatus(Request $request)
    {
        $room =Rooms::find($request->id);
        if ($room) {
            $room->status = $request->status;
            $room->save();
            return response()->json(['message' => 'Status updated successfully.']);
        }
        return response()->json(['message' => 'Room Details not found!'], 404);
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
            $role_data = Rooms::find($id); // Use `find($id)` instead of `where()->get()->first()`

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
    public function roomslist()
    {
        $data = Rooms::select('rooms.*', 
                    'room_categories.name as category')
        ->leftJoin('room_categories', 'rooms.category_id', '=', 'room_categories.id')
        ->get();
    return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('category', function ($row) {
            return $row->category;
        })
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
                             <a class="teamroleDelete dropdown-item" href="javascript:void(0);" data-id="' . $row->id . '">
                                 <i class="bx bx-trash me-1"></i> Delete
                             </a>
                         </div>
                       </div>
                       <div class="actions text-center">
                       <a class="btn btn-sm bg-success-light" data-toggle="modal" href="javascript:void(0);" id="edit_room" data-id="' . $row->id . '">
                               <i class="fe fe-pencil"></i>
                           </a>
                           <a data-toggle="modal" class="btn btn-sm bg-danger-light" href="javascript:void(0);" id="delete_room" data-id="' . $row->id . '">
                               <i class="fe fe-trash"></i>
                           </a>
                       </div>';

            return $action;
        })
        ->rawColumns(['category', 'action', 'status']) // Ensure HTML is not escaped
        ->make(true);
    }

    public function delete(Request $request)
    {
        $post = $request->post();
        $id = isset($post['id']) ? $post['id'] : "";
        $response['status']  = 0;
        $response['message']  = "Somthing Goes Wrong!";
        if (is_numeric($id)) {
            $delete_room = Rooms::where('id', $id);
            if ($delete_room) {
                $delete_room->delete();
                $response['status'] = 1;
                $response['message'] = 'Room deleted successfully.';
            } else {
                $response['message'] = 'something went wrong.';
            }
        }
        echo json_encode($response);
        exit;
    }
}

