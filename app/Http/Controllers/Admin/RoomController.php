<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = RoomCategory::all();
        return view('admin.room.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function toggleStatus(Request $request)
    {
        $role =Room::find($request->id);
        if ($role) {
            $role->status = $request->status;
            $role->save();
            return response()->json(['message' => 'Status updated successfully.']);
        }
        return response()->json(['message' => 'Role not found!'], 404);
    }

    public function store(Request $request)
    {
        $response = ['status' => 0, 'message' => "Something went wrong!"];

        $roomId = $request->input('hidden_room_id');

        // Validation rules
        $rules = [
            'room_number' => 'required|string|max:255',
            'category_id' => 'required|numeric',
            'beds' => 'required|integer|min:1',
            'charges' => 'required|numeric|min:0',
            'status' => 'required',
        ];

        // Custom error messages
        $messages = [
            'room_number.required' => 'Please enter the room number',
            'category_id.required' => 'Please select a valid category',
            'beds.required' => 'Please enter the number of beds',
            'charges.required' => 'Please enter the room charges',
        ];

        // Validate request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'errors' => $validator->errors()], 422);
        }

        try {
            // Check if the room number already exists (excluding current record if updating)
            $existingRoom = Room::where('room_number', $request->room_number)
                ->when($roomId, function ($query) use ($roomId) {
                    return $query->where('id', '!=', $roomId);
                })
                ->exists();

            if ($existingRoom) {
                return response()->json(['status' => 0, 'message' => 'Room number already exists!'], 409);
            }

            // Data to insert/update
            $roomData = [
                'room_number' => isset($request->room_number) ? $request->room_number : 0,
                'category_id' => isset($request->category_id) ? $request->category_id : "",
                'beds' => isset($request->beds) ? $request->beds : 0,
                'charges' => isset($request->charges) ? $request->charges : 0,
                'status' => isset($request->status) ? $request->status : 1,
            ];

            if ($roomId) {
                // Update existing room
                $room = Room::find($roomId);
                if ($room) {
                    $room->update($roomData);
                    $response = ['status' => 1, 'message' => 'Room updated successfully!'];
                } else {
                    return response()->json(['status' => 0, 'message' => 'Room not found!'], 404);
                }
            } else {
                // Insert new room
                Room::create($roomData);
                $response = ['status' => 1, 'message' => 'Room added successfully!'];
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => 'Database error: ' . $e->getMessage()], 500);
        }

        return response()->json($response);
    }


    /**
     * Display the specified resource.
     */
    public function show()
    {
        $rooms_data = Room::select('rooms.*', 'rooms_category.name as category_name')
        ->leftJoin('rooms_category', 'rooms.category_id', '=', 'rooms_category.id')
        ->where('rooms.status', 1)
        ->where('rooms.isdeleted', 0)
        ->get();

        return DataTables::of($rooms_data)
            ->addIndexColumn()
            ->addColumn('category_name', function ($row) {
                return $row->category_name ?: 'N/A'; // If no category found, show 'N/A'
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
                            <a class="btn btn-sm bg-success-light" data-toggle="modal" href="javascript:void(0);" id="edit_rooms" data-id="' . $row->id . '">
                                <i class="fe fe-pencil"></i>
                            </a>
                            <a data-toggle="modal" class="btn btn-sm bg-danger-light" href="javascript:void(0);" id="delete_rooms" data-id="' . $row->id . '">
                                <i class="fe fe-trash"></i>
                            </a>
                        </div>';
                return $action;
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $id = $request->query('id');
        $response = [
            'status' => 0,
            'message' => 'Something went wrong!'
        ];

        // Check if ID is valid
        if (is_numeric($id)) {
            $room_data = Room::where('id', $id)->first();
            if ($room_data) {
                $response = [
                    'status' => 1,
                    'room_data' => $room_data
                ];
            }
        }

        return response()->json($response);
        exit;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        $response['status']  = 0;
        $response['message']  = "Somthing Goes Wrong!";
        if (is_numeric($id)) {
            $delete_room = Room::where('id', $id)->update(['isdeleted' => 1]);
            if ($delete_room) {
                $response['status'] = 1;
                $response['message'] = 'Room deleted successfully.';
            } else {
                $response['message'] = 'something went wrong.';
            }
        }
        return response()->json($response);
    }
}
