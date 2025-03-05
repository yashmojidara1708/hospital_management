<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\File;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Appointments;
use App\Models\Specialities;
use App\Models\Patients;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 

class AppointmentsController extends Controller
{
    //
    public function index()
    {
        $doctors= GlobalHelper::getAllDoctors();
        $patients= GlobalHelper::getAllPatients();
        $specializations = GlobalHelper::getAllSpecialities();
        return view('admin.Appointments.index', compact('specializations', 'doctors','patients'));
    }
    public function toggleStatus(Request $request)
    {
        $appointments =appointments::find($request->id);
        if ($appointments) {
            $appointments->status = $request->status;
            $appointments->save();
            return response()->json(['message' => 'Status updated successfully.']);
        }
        return response()->json(['message' => 'Appointment not found!'], 404);
    }
    public function save(Request $request)
    {
        $post = $request->post();
        $response['status'] = 0;
        $response['message'] = "Somthing Gose Wrong!";
        $feilds =   [
            'doctor' => $request->doctor,
            'specialization' => $request->specialization,
            'patient' => $request->patient,
            'date' => $request->date,
            'time' => $request->time,
            'status' => $request->status,
        ];

        $rules = [
           'doctor' => 'required',
            'specialization' => 'required',
            'patient' => 'required',
            'date' => 'required',
            'time' => 'required',
            'status' => 'required',
        ];

        $msg = [
            'doctor.required' => 'Please select doctor name',
            'specialization.required' => 'Please select specialities name',
            'patient.required' => 'Please select patient name',
            'date.required' => 'Please select date',
            'time.required' => 'Please select time',
            'status.required' => 'Please select status',
        ];

        $validator = Validator::make(
            $feilds,
            $rules,
            $msg
        );
        if (!$validator->fails()) {
            $insert_team_data = [
                'doctor' => isset($post['doctor']) ? $post['doctor'] : "",
                'specialization' => isset($post['specialization']) ? $post['specialization'] : "",
                'patient' => isset($post['patient']) ? $post['patient'] : "",
                'date' => isset($post['date']) ? $post['date'] : "",
                'time' => isset($post['time']) ? $post['time'] : "",
                'status' => isset($post['status']) ? $post['status'] : "",
            ];
            $id = isset($post['hid']) ? intval($post['hid']) : null;
            if ($id) {
                // Update existing record
                $appointment = appointments::find($id);
                if ($appointment) {
                    $appointment->update($insert_team_data);
                    $response['status'] = 1;
                    $response['message'] = "Appointment updated successfully!";
                } else {
                    $response['message'] = "Appointment not found!";
                }
            } else {
                // Create new record
                if (appointments::create($insert_team_data)) {
                    $response['status'] = 1;
                    $response['message'] = "Appointments added successfully!";
                } else {
                    $response['message'] = "Failed to add Appointment!";
                }
            }
        }
        return response()->json($response);
        exit;
    }
public function appointmentslist()
{
    $appointment_data = appointments::select('appointments.*','doctors.name as doctor_name','doctors.image as doctor_image','patients.name as patient_name','specialities.name as specialization_name')
        ->leftjoin('doctors', 'doctors.id', '=', 'appointments.doctor')
        ->leftjoin('patients', 'patients.patient_id', '=', 'appointments.patient')
        ->leftJoin('specialities', 'specialities.id', '=', 'appointments.specialization')
        ->get();
        return Datatables::of($appointment_data)
            ->addIndexColumn()
        ->addColumn('doctor', function ($appointment) {
            $imagePath = $appointment->doctor_image
                    ? asset("assets/admin/theme/img/doctors/" . $appointment->doctor_image)
                    : asset("assets/admin/theme/img/doctors/defult.jpg");
                    return '
                    <h2 class="table-avatar">
                        <a href="profile.html" class="avatar avatar-sm mr-2">
                            <img src="' . $imagePath . '" width="50" height="50" class="rounded-circle" alt="User Image">
                        </a>
                        <a>' . e($appointment->doctor_name) . '</a>
                    </h2>'; 
           // return ($appointment->doctor_name);
        })
        ->addColumn('specialization', function ($appointment) {
            return ($appointment->specialization_name);
        })
        ->addColumn('patient', function ($appointment) {
            return ($appointment->patient_name);
        })
        ->addColumn('status', function ($row) {
            $checked = $row->status ? 'checked' : '';
            return '
            <div class="status-toggle">
                <input type="checkbox" id="status_' . $row->id . '" class="check toggle-status" data-id="' . $row->id . '" ' . $checked . '>
                <label for="status_' . $row->id . '" class="checktoggle">checkbox</label>
            </div>';
   
        })
        ->addColumn('appointment_time', function ($row) {
            $formattedDate = Carbon::parse($row->date)->format('j M Y');
            $formattedTime = Carbon::parse($row->time)->format('h A');
            return $formattedDate . "<br>" . $formattedTime; 
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
                               <a class="btn btn-sm bg-success-light" data-toggle="modal" href="javascript:void(0);" id="edit_appointment" data-id="' . $row->id . '">
                                   <i class="fe fe-pencil"></i>
                               </a>
                               <a data-toggle="modal" class="btn btn-sm bg-danger-light" href="javascript:void(0);" id="delete_appointment" data-id="' . $row->id . '">
                                   <i class="fe fe-trash"></i>
                               </a>
                           </div>';

            return $action;
        })

        ->rawColumns(['doctor', 'specialization','patient','appointment_time','status','action']) // Ensure HTML is not escaped
        ->make(true);
}

public function delete(Request $request)
{
    $post = $request->post();
    $id = isset($post['id']) ? $post['id'] : "";
    $response['status']  = 0;
    $response['message']  = "Somthing Goes Wrong!";
    if (is_numeric($id)) {
        $delete_appointment = appointments::where('id', $id)->delete();
        if ($delete_appointment) {
            $response['status'] = 1;
            $response['message'] = 'Appointment deleted successfully.';
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
        $appointments_data = appointments::find($id); // Use `find($id)` instead of `where()->get()->first()`

        if ($appointments_data) {
            $response = [
                'status' => 1,
                'appointments_data' => $appointments_data
            ];
        }
    }

    return response()->json($response);
    exit; // Proper JSON response
}
}

