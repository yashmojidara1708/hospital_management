<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Models\Patients;
use App\Models\Specialities;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class PatientsController extends Controller
{
    public function index()
    {
        $countries = GlobalHelper::getAllCountries();
        $cities = GlobalHelper::getAllCities();
        $states = GlobalHelper::getAllStates();
        return view('admin.patients.index', compact('countries', 'states', 'cities'));
    }

    public function save(Request $request)
    {
        $post = $request->post();
        $hid = isset($post['hid']) ? intval($post['hid']) : null;
        $response['status'] = 0;
        $response['message'] = "Somthing Gose Wrong!";

        $feilds = [
            'name' => $request->name,
            'age' => $request->age,
            'address' => $request->address,
            'country' => $request->country,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'phone' => $request->phone,
            'email' => $request->email,
            'last_visit' => $request->last_visit,
            'paid' => $request->paid,
        ];

        // Validation rules
        $rules = [
            'name' => 'required',
            'age' => 'required|numeric',
            'address' => 'required',
            'country' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'phone' => 'required',
            'email' => [
                'required',
                'email',
            ],
            'last_visit' => 'required|date',
            'paid' => 'required|numeric',
        ];
        // Custom error messages
        $msg = [
            'name.required' => 'Please enter the patient name',
            'age.required' => 'Please enter the age',
            'age.numeric' => 'Age must be a number',
            'address.required' => 'Please enter the address',
            'country.required' => 'Please select a country',
            'city.required' => 'Please enter the city',
            'state.required' => 'Please enter the state',
            'zip.required' => 'Please enter the ZIP code',
            'phone.required' => 'Please enter the phone number',
            'email.required' => 'Please enter the email address',
            'email.email' => 'Please enter a valid email address',
            'last_visit.required' => 'Please select the last visit date',
            'last_visit.date' => 'Please enter a valid date',
            'paid.required' => 'Please enter the amount paid',
            'paid.numeric' => 'Paid amount must be a number',
        ];

        $validator = Validator::make(
            $feilds,
            $rules,
            $msg
        );

        if (!$validator->fails()) {
            // Check if email already exists for another patient and is not deleted
            $existingEmailQuery = Patients::where('email', $request->email)
                ->where('isdeleted', '!=', 1);

            // Exclude the current record from the check if updating
            if ($hid) {
                $existingEmailQuery->where('patient_id', '!=', $hid);
            }

            if ($existingEmailQuery->exists()) {
                return response()->json([
                    'status' => 0,
                    'message' => 'The email address is already in use by another patient.',
                ]);
            }

            $insert_team_data = [
                'name' => isset($post['name']) ? $post['name'] : "",
                'age' => isset($post['age']) ? $post['age'] : "",
                'address' => isset($post['address']) ? $post['address'] : "",
                'country' => isset($post['country']) ? $post['country'] : "",
                'city' => isset($post['city']) ? $post['city'] : "",
                'state' => isset($post['state']) ? $post['state'] : "",
                'zip' => isset($post['zip']) ? $post['zip'] : "",
                'phone' => isset($post['phone']) ? $post['phone'] : "",
                'email' => isset($post['email']) ? $post['email'] : "",
                'last_visit' => isset($post['last_visit']) ? $post['last_visit'] : "",
                'paid' => isset($post['paid']) ? $post['paid'] : "",
            ];

            if ($hid) {
                // Update existing record
                $Patients = Patients::where('patient_id', $hid)->first();
                if ($Patients) {
                    $Patients->update($insert_team_data);
                    $response['status'] = 1;
                    $response['message'] = "Patients updated successfully!";
                } else {
                    $response['message'] = "Patients not found!";
                }
            } else {
                // Create new record
                if (Patients::create($insert_team_data)) {
                    $response['status'] = 1;
                    $response['message'] = "Patients added successfully!";
                } else {
                    $response['message'] = "Failed to add Patients!";
                }
            }
        }
        return response()->json($response);
        exit;
    }

    // // List Show
    public function patientslist()
    {
        $patients_data = Patients::select('patients.*', 'countries.name as country', 'states.name as state', 'cities.name as city')
            ->where('isdeleted', '!=', 1)
            ->leftJoin('countries', 'patients.country', '=', 'countries.id')
            ->leftJoin('states', 'patients.state', '=', 'states.id')
            ->leftJoin('cities', 'patients.city', '=', 'cities.id')
            ->get();
        return Datatables::of($patients_data)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return '<a href="' . route('patients.details', $row->patient_id) . '" class="patient-link">' . $row->name . '</a>';
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
                                 <a class="teamroleDelete dropdown-item" href="javascript:void(0);" data-id="' . $row->patient_id . '">
                                     <i class="bx bx-trash me-1"></i> Delete
                                 </a>
                             </div>
                           </div>
                           <div class="actions text-center">
                           <a class="btn btn-sm bg-success-light" data-toggle="modal" href="javascript:void(0);" id="patientsEdit" data-id="' . $row->patient_id . '">
                                   <i class="fe fe-pencil"></i>
                               </a>
                               <a data-toggle="modal" class="btn btn-sm bg-danger-light" href="javascript:void(0);" id="delete_patients" data-id="' . $row->patient_id . '">
                                   <i class="fe fe-trash"></i>
                               </a>
                           </div>';

                return $action;
            })
            ->rawColumns(['address', 'action', 'name']) // Ensure HTML is not escaped
            ->make(true);
    }

    public function delete(Request $request)
    {
        $post = $request->post();
        $id = isset($post['id']) ? $post['id'] : "";
        $response['status']  = 0;
        $response['message']  = "Somthing Goes Wrong!";
        if (is_numeric($id)) {
            $delete_patients = Patients::where('patient_id', $id)->update(['isdeleted' => 1]);
            if ($delete_patients) {
                $response['status'] = 1;
                $response['message'] = 'Patient deleted successfully.';
            } else {
                $response['message'] = 'something went wrong.';
            }
        }
        echo json_encode($response);
        exit;
    }

    public function patientDetails($patientId)
    {
        $globalHelper = new GlobalHelper();
        $countries = GlobalHelper::getAllCountries();
        $patient = $globalHelper->getPatientById($patientId);
        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found or deleted.');
        }

        return view('admin.patients.PatientDetails', compact('patient', 'countries'));
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
            $patients_data = Patients::where('patient_id', $id)->first();
            if ($patients_data) {
                $response = [
                    'status' => 1,
                    'patients_data' => $patients_data
                ];
            }
        }

        return response()->json($response);
        exit; // Proper JSON response
    }
}
