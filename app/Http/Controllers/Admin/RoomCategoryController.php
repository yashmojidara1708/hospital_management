<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\RoomCategory;
use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RoomCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.roomsCategory.index');
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
    public function store(Request $request)
    {
        $response = ['status' => 0, 'message' => "Something went wrong!"];

        $roomId = $request->input('hidden_room_id');

        // Validation rules
        $rules = [
            'room_name' => 'required|string|max:255',
        ];

        // Custom error messages
        $messages = [
            'room_name.required' => 'Please enter the room name',
        ];

        // Validate request data
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'errors' => $validator->errors()], 422);
        }

        try {
            // Check if the room name already exists (excluding current record if updating)
            $existingRoom = RoomCategory::where('name', $request->room_name)
                ->when($roomId, function ($query) use ($roomId) {
                    return $query->where('id', '!=', $roomId);
                })
                ->exists();

            if ($existingRoom) {
                return response()->json(['status' => 0, 'message' => 'Room name already exists!']);
            }

            // Data to insert/update
            $roomData = [
                'name' => isset($request->room_name) ? $request->room_name : '',
            ];

            if ($roomId) {
                // Update existing room
                $room = RoomCategory::find($roomId);
                if ($room) {
                    $room->update($roomData);
                    $response = ['status' => 1, 'message' => 'Room updated successfully!'];
                } else {
                    return response()->json(['status' => 0, 'message' => 'Room not found!'], 404);
                }
            } else {
                // Insert new room
                RoomCategory::create($roomData);
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
        $rooms_data = RoomCategory::select('*')->get();
        return DataTables::of($rooms_data)
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
                             <a class="btn btn-sm bg-success-light" data-toggle="modal" href="javascript:void(0);" id="edit_rooms" data-id="' . $row->id . '">
                                   <i class="fe fe-pencil"></i>
                               </a>
                               <a data-toggle="modal" class="btn btn-sm bg-danger-light" href="javascript:void(0);" id="delete_rooms" data-id="' . $row->id . '">
                                   <i class="fe fe-trash"></i>
                               </a>
                           </div>';

                return $action;
            })
            ->rawColumns(['action'])
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
            $room_data = RoomCategory::where('id', $id)->first();
            if ($room_data) {
                $response = [
                    'status' => 1,
                    'room_data' => $room_data
                ];
            }
        }

        return response()->json($response);
        exit; // Proper JSON response
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $response = [
            'status' => 0,
            'message' => 'Something went wrong!'
        ];

        if (is_numeric($id)) {
            $roomCategory = RoomCategory::find($id);

            if ($roomCategory) {
                // Check if any room is associated with this category
                $roomExists = Room::where('category_id', $id)->where('isdeleted', 0)->exists();

                if ($roomExists) {
                    $response['message'] = 'Cannot delete. Rooms are associated with this room category.';
                } else {
                    $roomCategory->delete();
                    $response['status'] = 1;
                    $response['message'] = 'Room category deleted successfully.';
                }
            } else {
                $response['message'] = 'Room category not found.';
            }
        } else {
            $response['message'] = 'Invalid ID provided.';
        }

        return response()->json($response);
    }
}
