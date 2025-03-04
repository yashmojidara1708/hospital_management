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
class DoctorsController extends Controller
{
    //
    public function index()
    {
        $countries = GlobalHelper::getAllCountries();
        $specializations = GlobalHelper::getAllSpecialities();
        return view('admin.Doctor.index', compact('specializations','countries'));
    }
    public function save(Request $request)
    {
        $post = $request->post();
        $hid = isset($post['hid']) ? intval($post['hid']) : null;
        $response['status'] = 0;
        $response['message'] = "Somthing Goes Wrong!";

        $feilds = [
            'name' => $request->name,
            'specialization' => $request->specialization,
            'phone' => $request->phone,
            'email' => $request->email,
            'experience' => $request->experience,
            'qualification' => $request->qualification,
            'address' => $request->address,
            'country' => $request->country,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'image' => $request->image,
        ];

        // Validation rules
        $rules = [
            'name' => 'required',
            'specialization' => 'required',
            'phone' => 'required',
            'email' => [
                'required',
                'email',
            ],
            'experience' => 'required|numeric',
            'qualification' => 'required',
            'address' => 'required',
            'country' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'image' => 'nullable|mimes:jpeg,png|max:5120',
        ];
        // Custom error messages
        $msg = [
            'name.required' => 'Please enter the doctor name',
            'specialization.required' => 'Please select the specialization',
            'phone.required' => 'Please enter the phone number',
            'email.required' => 'Please enter the email address',
            'email.email' => 'Please enter a valid email address',
            'experience.required' => 'Please enter the experience',
            'experience.numeric' => 'Experience must be a number',
            'qualification.required' => 'Please enter the qualification',
            'address.required' => 'Please enter the address',
            'country.required' => 'Please select a country',
            'city.required' => 'Please enter the city',
            'state.required' => 'Please enter the state',
            'zip.required' => 'Please enter the ZIP code',
            'image.mimes'=>'Only jpeg and png images are allowed',
            'image.max'=>'Image size should not exceed 5MB',
              
        ];

        $validator = Validator::make(
            $feilds,
            $rules,
            $msg
        );

        if (!$validator->fails()) {
            // Check if email already exists for another patient and is not deleted
            $existingEmailQuery = Doctor::where('email', $request->email)
                ->where('isdeleted', '!=', 1);

            // Exclude the current record from the check if updating
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
    
            // Create directory if it doesn't exist
            if (!File::exists(public_path($imagePath))) {
                File::makeDirectory(public_path($imagePath), 0755, true);
            }
        
            $imageName = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($imagePath), $imageName);
            }
        
            $insert_doctor_data = [
                'name' => isset($post['name']) ? $post['name'] : "",
                'specialization' => isset($post['specialization']) ? $post['specialization'] : "",
                'phone' => isset($post['phone']) ? $post['phone'] : "",
                'email' => isset($post['email']) ? $post['email'] : "",
                'experience' => isset($post['experience']) ? $post['experience'] : "",
                'qualification' => isset($post['qualification']) ? $post['qualification'] : "",
                'address' => isset($post['address']) ? $post['address'] : "",
               'country' => isset($post['country']) ? $post['country'] : "",
                'city' => isset($post['city']) ? $post['city'] : "",
                'state' => isset($post['state']) ? $post['state'] : "",
                'zip' => isset($post['zip']) ? $post['zip'] : "",
                'image'=>$imageName,        
            ];

            if ($hid) {
                // Update existing record
                $Doctors =Doctor::where('id', $hid)->first();
                $imageName = $Doctors ? $Doctors->image : null; // Keep old image if updating

                if ($request->hasFile('image')) {
                    // Delete old image if exists
                    if ($Doctors && $Doctors->image && File::exists(public_path($Doctors->image))) {
                        File::delete(public_path($Doctors->image));
                    }
                    $image = $request->file('image');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path($imagePath), $imageName);
                    $imageName = $imagePath . $imageName; // Store full path
                }
            
            
                if ($Doctors) {
                    $Doctors->update($insert_doctor_data);
                    $response['status'] = 1;
                    $response['message'] = "Doctor updated successfully!";
                } else {
                    $response['message'] = "Doctor not found!";
                }
            } else {
                // Create new record
                if (Doctor::create($insert_doctor_data)) {
                    $response['status'] = 1;
                    $response['message'] = "Doctor added successfully!";
                } else {
                    $response['message'] = "Failed to add Doctor!";
                }
            }
        }
        return response()->json($response);
        exit;
    }
    public function doctorslist()
    {
        $doctors_data = Doctor::select('*')->where('isdeleted', '!=', 1)->get();
        return Datatables::of($doctors_data)
            ->addIndexColumn()
            ->addColumn('image', function ($doctor) {
                $imagePath = asset("assets/admin/theme/img/doctors/" . $doctor->image);
                return $doctor->image ? '<img src="'.$imagePath.'" width="50" height="50" class="rounded-circle"/>' : '<img src="'.asset("assets/admin/theme/img/default.png").'" width="50" height="50" class="rounded-circle"/>
                
                ';
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
                                   <a class="btn btn-sm bg-success-light" data-toggle="modal" href="javascript:void(0);" id="doctorsEdit" data-id="' . $row->id . '">
                                       <i class="fe fe-pencil"></i>
                                   </a>
                                   <a data-toggle="modal" class="btn btn-sm bg-danger-light" href="javascript:void(0);" id="delete_doctors" data-id="' . $row->id . '">
                                       <i class="fe fe-trash"></i>
                                   </a>
                               </div>';
    
                    return $action;
                })
               
            ->rawColumns(['image','address', 'action']) // Ensure HTML is not escaped
            ->make(true);
    }
}
