<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\File;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialities;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorsController extends Controller
{
    //
    public function index()
    {
        $countries = GlobalHelper::getAllCountries();
        $specializations = GlobalHelper::getAllSpecialities();
        return view('admin.doctor.index', compact('specializations', 'countries'));
    }
    public function save(Request $request)
    {
        $post = $request->post();
        $hid = isset($post['hid']) ? intval($post['hid']) : null;
        $Doctors = null; // Initialize to avoid "Undefined variable" error

        $response = ['status' => 0, 'message' => 'Something went wrong!'];

        // Validation rules
        $rules = [
            'name' => 'required',
            'specialization' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'experience' => 'required|numeric',
            'qualification' => 'required',
            'address' => 'required',
            'country' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'image' => 'nullable|mimes:jpeg,png|max:5120',
        ];
        if (!$hid) {
            $rules['password'] = 'required|min:8';
        }
        // Custom error messages
        $msg = [
            'name.required' => 'Please enter the doctor name',
            'email.email' => 'Please enter a valid email address',
            'email.required' => 'Please enter the email address',
            'password.required' => 'Please enter the password',
            'experience.numeric' => 'Experience must be a number',
            'image.mimes' => 'Only jpeg and png images are allowed',
            'image.max' => 'Image size should not exceed 5MB',
        ];

        $validator = Validator::make($post, $rules, $msg);

        if (!$validator->fails()) {
            // Check if email already exists for another doctor (exclude the current one if updating)
            $existingEmailQuery = Doctor::where('email', $request->email)
                ->where('isdeleted', '!=', 1);

            if ($hid) {
                $existingEmailQuery->where('id', '!=', $hid);
            }

            if ($existingEmailQuery->exists()) {
                return response()->json([
                    'status' => 0,
                    'message' => 'The email address is already in use by another doctor.',
                ]);
            }

            $imagePath = 'assets/admin/theme/img/doctors/';
            if (!File::exists(public_path($imagePath))) {
                File::makeDirectory(public_path($imagePath), 0755, true);
            }

            $imageName = null;

            if ($hid) {
                // If updating, get the old image
                $Doctors = Doctor::find($hid);
                $imageName = $Doctors ? $Doctors->image : null;
            }

            // Handle image upload if a new image is provided
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                // Delete old image if exists and a new image is uploaded
                if ($Doctors && $Doctors->image && File::exists(public_path($Doctors->image))) {
                    File::delete(public_path($Doctors->image));
                }

                $image->move(public_path($imagePath), $imageName);
                $imageName = $imageName; // Store full path
            }

            // Data array for insertion/updation
            $insert_doctor_data = [
                'name' => isset($request->name) ? $request->name : "",
                'specialization' => isset($request->specialization) ? $request->specialization : "",
                'phone' => isset($request->phone) ? $request->phone : "",
                'email' => isset($request->email) ? $request->email : "",
                'role' => 'doctor',
                'experience' => isset($request->experience) ? $request->experience : "",
                'qualification' => isset($request->qualification) ? $request->qualification : "",
                'address' => isset($request->address) ? $request->address : "",
                'country' => isset($request->country) ? $request->country : "",
                'city' => isset($request->city) ? $request->city : "",
                'state' => isset($request->state) ? $request->state : "",
                'zip' => isset($request->zip) ? $request->zip : "",
                'image' => $imageName,
            ];

            if (!$hid) {
                $insert_doctor_data['password'] = isset($post['password']) ? Hash::make($post['password']) : "";
            }

            if ($hid) {
                // Update existing record
                if (isset($Doctors)) {
                    if (!empty($post['password'])) {
                        $Doctors['password'] = Hash::make($post['password']);
                    }
                    $Doctors->update($insert_doctor_data);
                    $response = ['status' => 1, 'message' => 'Doctor updated successfully!'];
                } else {
                    $response['message'] = 'Doctor not found!';
                }
            } else {
                // Create new record
                if (Doctor::create($insert_doctor_data)) {
                    $response = ['status' => 1, 'message' => 'Doctor added successfully!'];
                } else {
                    $response['message'] = 'Failed to add Doctor!';
                }
            }
        } else {
            $response['message'] = $validator->errors()->first();
        }

        return response()->json($response);
    }

    public function doctorslist()
    {
        $doctors_data = Doctor::select('doctors.*', 'specialities.name as specialization_name', 'countries.name as country', 'states.name as state', 'cities.name as city')
            ->where('doctors.isdeleted', '!=', 1)
            ->leftJoin('specialities', 'specialities.id', '=', 'doctors.specialization')
            ->leftJoin('countries', 'doctors.country', '=', 'countries.id')
            ->leftJoin('states', 'doctors.state', '=', 'states.id')
            ->leftJoin('cities', 'doctors.city', '=', 'cities.id')
            ->get();
        return Datatables::of($doctors_data)
            ->addIndexColumn()
            ->addColumn('name', function ($doctor) {
                $imagePath = "assets/admin/theme/img/doctors/" . $doctor->image;
                $defaultImage = asset("assets/admin/theme/img/doctors/default.jpg");

                // Check if the file exists using the public path
                $imageUrl = (!empty($doctor->image) && file_exists(public_path($imagePath)))
                    ? asset($imagePath)
                    : $defaultImage;

                return '
                    <h2 class="table-avatar">
                        <a href="profile.html" class="avatar avatar-sm mr-2">
                            <img src="' . $imageUrl . '" width="50" height="50" class="rounded-circle" alt="User Image">
                        </a>
                        <a href="profile.html">' . e($doctor->name) . '</a>
                    </h2>';
            })
            ->addColumn('specialization', function ($doctor) {
                return e($doctor->specialization_name);
            })
            ->addColumn('address', function ($row) {
                return $row->address . ', ' . $row->city . ', ' . $row->state . ', ' . $row->country . ' - ' . $row->zip;
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
                                   <a class="btn btn-sm bg-success-light" data-toggle="modal" href="javascript:void(0);" id="edit_doctors" data-id="' . $row->id . '">
                                       <i class="fe fe-pencil"></i>
                                   </a>
                                   <a data-toggle="modal" class="btn btn-sm bg-danger-light" href="javascript:void(0);" id="delete_doctors" data-id="' . $row->id . '">
                                       <i class="fe fe-trash"></i>
                                   </a>
                               </div>';

                return $action;
            })

            ->rawColumns(['name', 'address', 'action']) // Ensure HTML is not escaped
            ->make(true);
    }
    public function delete(Request $request)
    {
        $post = $request->post();
        $id = isset($post['id']) ? $post['id'] : "";
        $response['status']  = 0;
        $response['message']  = "Somthing Goes Wrong!";
        if (is_numeric($id)) {
            $doctor_data = Doctor::where('id', $id)->get()->toArray();

            $img_for_delete = $doctor_data[0]['image'] != "" ? $doctor_data[0]['image'] : "";
            // Only try to delete if image exists
            if (!empty($img_for_delete)) {
                $delete_img_path = public_path("assets/admin/theme/img/doctors/") . $img_for_delete;
                if (file_exists($delete_img_path) && is_file($delete_img_path)) {
                    unlink($delete_img_path);
                }
            }

            $delete_patients = Doctor::where('id', $id)->update(['isdeleted' => 1]);
            if ($delete_patients) {
                $response['status'] = 1;
                $response['message'] = 'Doctor deleted successfully.';
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
        $response['status'] = 0;

        $doctor = Doctor::where("id", $id)->get();

        if (!empty($doctor[0])) {
            $imagePath = public_path("assets/admin/theme/img/doctors") . "/" . $doctor[0]['image'];
            $defaultImage = asset("assets/admin/theme/img/doctors/default.jpg"); // Path to the default image

            if (!empty($doctor[0]['image']) && file_exists($imagePath)) {
                $doctor[0]['image'] = "<img src='" . asset("assets/admin/theme/img/doctors/" . $doctor[0]['image']) . "' alt='Doctor Image' height='100px' width='auto'>";
            } else {
                $doctor[0]['image'] = "<img src='" . $defaultImage . "' alt='Default Image' height='100px' width='auto'>";
            }
            $response['doctor_data'] = $doctor[0];
            $response['status'] = 1;
        }
        return response()->json($response);
        exit;
    }
}
