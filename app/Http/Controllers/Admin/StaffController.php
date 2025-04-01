<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Models\Patients;
use App\Models\Specialities;
use App\Models\Staff;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $countries = GlobalHelper::getAllCountries();
        $cities = GlobalHelper::getAllCities();
        $states = GlobalHelper::getAllStates();
        $roles = GlobalHelper::getAllRoles();
        return view('admin.staff.index', compact('countries', 'cities','states','roles'));
    }

    public function save(Request $request)
    {
        $post = $request->post();
        $hid = isset($post['hid']) ? intval($post['hid']) : null;
        $response['status'] = 0;
        $response['message'] = "Something went wrong!";

        $fields = [
            'name' => $request->name,
            'roles' => $request->roles,
            'date_of_birth' => $request->date_of_birth,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => $request->password,
            'address' => $request->address,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'zip' => $request->zip,
        ];

        // Validation rules
        $rules = [
            'name' => 'required',
            'roles' => 'required',
            'date_of_birth' => 'required|date',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zip' => 'required',
        ];
        if (!$hid) {
            $rules['password'] = 'required|min:6';
        }

        // Custom error messages
        $msg = [
            'name.required' => 'Please enter the name',
            'roles.required' => 'Please select the role',
            'date_of_birth.required' => 'Please enter the date of birth',
            'date_of_birth.date' => 'Please enter a valid date',
            'phone.required' => 'Please enter the phone number',
            'email.required' => 'Please enter the email address',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Please enter the password',
            'password.min' => 'Password must be at least 6 characters',
            'address.required' => 'Please enter the address',
            'country.required' => 'Please select a country',
            'state.required' => 'Please enter the state',
            'city.required' => 'Please enter the city',
            'zip.required' => 'Please enter the ZIP code',
        ];

        $validator = Validator::make($fields, $rules, $msg);

        if (!$validator->fails()) {
            // Check if email already exists for another user and is not deleted
            $existingEmailQuery = Staff::where('email', $request->email)
                ->where('isdeleted', '!=', 1);

            // Exclude the current record from the check if updating
            if ($hid) {
                $existingEmailQuery->where('id', '!=', $hid);
            }

            if ($existingEmailQuery->exists()) {
                return response()->json([
                    'status' => 0,
                    'message' => 'The email address is already in use by another user.',
                ]);
            }

            $Staff_insert_data = [
                'name' => $post['name'] ?? "",
                'roles' => $post['roles'] ?? "",
                'date_of_birth' => $post['date_of_birth'] ?? "",
                'phone' => $post['phone'] ?? "",
                'email' => $post['email'] ?? "",
                'address' => $post['address'] ?? "",
                'country' => $post['country'] ?? "",
                'state' => $post['state'] ?? "",
                'city' => $post['city'] ?? "",
                'zip' => $post['zip'] ?? "",
            ];


            if (!$hid) {
                $Staff_insert_data['password'] = isset($post['password']) ? Hash::make($post['password']) : "";
            }

            if ($hid) {
                // Update existing record
                $Staff = Staff::where('id', $hid)->first();
                if ($Staff) {
                    // Avoid re-hashing the password if not changed
                    if (!empty($post['password'])) {
                        $Staff['password'] = Hash::make($post['password']);
                    }
                    $Staff->update($Staff_insert_data);
                    $response['status'] = 1;
                    $response['message'] = "Staff updated successfully!";
                } else {
                    $response['message'] = "Staff not found!";
                }
            } else {
                // Create new record
                if (Staff::create($Staff_insert_data)) {
                    $response['status'] = 1;
                    $response['message'] = "Staff added successfully!";
                } else {
                    $response['message'] = "Failed to add Staff!";
                }
            }
        } else {
            $response['message'] = $validator->errors()->first();
        }

        return response()->json($response);
        exit;
    }


    // // // List Show
    public function stafflist()
    {
        $staff_data = Staff::select('staff.*','countries.name as country_name', 'states.name as state_name','cities.name as city_name',
                     DB::raw("GROUP_CONCAT(roles.name SEPARATOR ', ') as role_names"))
            ->leftJoin('roles', DB::raw("JSON_CONTAINS(staff.roles, JSON_QUOTE(CAST(roles.id AS CHAR)) )"), '>', DB::raw('0'))
            ->leftJoin('countries', 'staff.country', '=', 'countries.id')
            ->leftJoin('states', 'staff.state', '=', 'states.id')
            ->leftJoin('cities', 'staff.city', '=', 'cities.id')
            ->where('staff.isdeleted', '!=', 1)
            ->groupBy('staff.id') // Group by staff ID to avoid duplicate rows
            ->get();


        return Datatables::of($staff_data)
            ->addIndexColumn()
            ->addColumn('address', function ($row) {
                $address = $row->address ?? 'N/A';
                $city = $row->city_name ?? 'N/A';
                $state = $row->state_name ?? 'N/A';
                $country = $row->country_name ?? 'N/A';
                return "$address, $city, $state, $country";
               // return $row->address . ', ' . $row->cities_name . ', ' . $row->states_name . ', ' . $row->country_name . ' - ' . $row->zip;
            })
            // Display role names as a comma-separated string
            ->addColumn('roles', function ($row) {
                return $row->role_names;
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
                                <a class="btn btn-sm bg-success-light" data-toggle="modal" href="javascript:void(0);" id="edit_staff" data-id="' . $row->id . '">
                                   <i class="fe fe-pencil"></i>
                               </a>
                               <a data-toggle="modal" class="btn btn-sm bg-danger-light" href="javascript:void(0);" id="delete_staff" data-id="' . $row->id . '">
                                   <i class="fe fe-trash"></i>
                               </a>
                           </div>';

                return $action;
            })
            ->rawColumns(['address', 'action', 'roles']) // Ensure HTML is not escaped
            ->make(true);
    }

    public function delete(Request $request)
    {
        $post = $request->post();
        $id = isset($post['id']) ? $post['id'] : "";
        $response['status']  = 0;
        $response['message']  = "Somthing Goes Wrong!";
        if (is_numeric($id)) {
            $delete_staff = Staff::where('id', $id)->update(['isdeleted' => 1]);
            if ($delete_staff) {
                $response['status'] = 1;
                $response['message'] = 'Staff deleted successfully.';
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
            $staffs_data = Staff::where('id', $id)->first();
            if ($staffs_data) {
                $response = [
                    'status' => 1,
                    'staffs_data' => $staffs_data
                ];
            }
        }

        return response()->json($response);
        exit; // Proper JSON response
    }
}
