<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\AdmitPatient;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class AdmitPatientController extends Controller
{
    public function index()
    {
        return view('admin.admit_patients.index');
    }

    public function fetchRoomAvailability($room_id)
{
    // Fetch only bookings for the selected room

    $roomsWithPatients = DB::table('rooms')
    ->leftJoin('admitted_patients', function ($join) {
        $join->on('rooms.id', '=', 'admitted_patients.room_id')
            ->where(function ($query) {
                $query->whereNull('admitted_patients.discharge_date') // Active Patients
                      ->orWhere('admitted_patients.discharge_date', '>=', now()); // Future Discharges
            });
    })
    ->leftJoin('rooms_category', 'rooms.category_id', '=', 'rooms_category.id') 
    ->select(
        'rooms_category.name as category_name', 
        'rooms.room_number',
        DB::raw('COUNT(admitted_patients.id) as occupied_beds'), // Count active patients
        'rooms.beds',
        DB::raw('(rooms.beds - COUNT(admitted_patients.id)) as remaining_beds') // Calculate free beds
    )
    ->where('rooms.id', $room_id)
    ->groupBy('rooms.id', 'rooms.room_number', 'rooms.beds','rooms_category.name')
    ->get();
   return response()->json($roomsWithPatients);
}

    public function save(Request $request)
    {
        $post = $request->post();
        $id = isset($post['hidden_id']) ? intval($post['hidden_id']) : null;
        $patientid = isset($post['patient_id']) ? $post['patient_id'] : "";
        if (!$id) {
            $admit_patient = AdmitPatient::where(function ($query) {
                $query->whereNull('discharge_date')
                      ->orWhere('discharge_date', '>', today());
            })
            ->where('isdeleted', 0)
            ->where('patient_id', $patientid)
            ->first();

            if ($admit_patient) {
                $response['status'] = 0;
                $response['message'] = "This patient is already admitted!";
                return response()->json($response);
            }
        }

        $response['status'] = 0;
        $response['message'] = "Somthing Gose Wrong!";

        // Validate request data
        $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'doctor_id' => 'required|exists:doctors,id',
            'room_id' => 'required|exists:rooms,id',
            'admission_date' => 'required|date',
            'discharge_date' => 'nullable|date|after_or_equal:admission_date',
            'admit_reason' => 'required|string|max:255',
            'status' => 'required',
        ]);


        $discharge_date = !empty($post['discharge_date']) ? $post['discharge_date'] : null;
        // Prepare data for insertion
        $insert_admit_data = [
            'doctor_id' => isset($post['doctor_id']) ? $post['doctor_id'] : "",
            'patient_id' => isset($post['patient_id']) ? $post['patient_id'] : "",
            'room_id' => isset($post['room_id']) ? $post['room_id'] : "",
            'admit_date' => isset($post['admission_date']) ? $post['admission_date'] : "",
            'discharge_date' => $discharge_date,
            'admission_reason' => isset($post['admit_reason']) ? $post['admit_reason'] : "",
            'status' => isset($post['status']) ? $post['status'] : "",
        ];

        // Check if hidden_id exists (for update)

        if ($id) {
            // Update existing record
            $appointment = AdmitPatient::find($id);
            if ($appointment) {
                $appointment->update($insert_admit_data);
                $response['status'] = 1;
                $response['message'] = "Patient admitted Details updated successfully!";
            } else {
                $response['message'] = "Patient admitted Details not found!";
            }
        } else {
            // Create new record
            if (AdmitPatient::create($insert_admit_data)) {
                $response['status'] = 1;
                $response['message'] = "Patient admitted Details added successfully!";
            } else {
                $response['message'] = "Failed to add Patient admitted Details!";
            }
        }
        return response()->json($response);
        exit;
    }

    public function generateBill($id)
    {
        $patientdetail=AdmitPatient::select(
            'admitted_patients.*',
            'patients.name as patient_name',
            'patients.phone as patient_phone',
            'patients.address as patient_address',
            'doctors.name as doctor_name',
            'cities.name as city_name',
            'states.name as state_name',
            'specialities.name as specialization_name',
            'rooms.room_number as room_number',
            'rooms.charges as charges',
            'rooms_category.name as room_category',
            DB::raw("IF(admitted_patients.discharge_date IS NOT NULL,
             DATEDIFF(admitted_patients.discharge_date, admitted_patients.admit_date), NULL) AS days_admitted")
        )->join('patients', 'admitted_patients.patient_id', '=', 'patients.patient_id')
        ->join('doctors', 'admitted_patients.doctor_id', '=', 'doctors.id')
        ->join('cities', 'patients.city', '=', 'cities.id')
        ->join('states', 'patients.state', '=', 'states.id')
        ->join('specialities', 'doctors.specialization', '=', 'specialities.id')
        ->join('rooms', 'admitted_patients.room_id', '=', 'rooms.id')
        ->join('rooms_category', 'rooms.category_id', '=', 'rooms_category.id') // Get room category name
        ->where('admitted_patients.id','=',$id)
        ->first();
        // return $patientdetail;
       return view('admin.Bill.index',compact('patientdetail'));
    }
    public function list()
    {
        $admit_patients = AdmitPatient::select(
            'admitted_patients.*',
            'patients.name as patient_name',
            'doctors.name as doctor_name',
            'doctors.image as doctor_image',
            'specialities.name as specialization_name',
            'rooms.room_number as room_number',
            'rooms_category.name as room_category'
        )->join('patients', 'admitted_patients.patient_id', '=', 'patients.patient_id')
            ->join('doctors', 'admitted_patients.doctor_id', '=', 'doctors.id')
            ->join('specialities', 'doctors.specialization', '=', 'specialities.id')
            ->join('rooms', 'admitted_patients.room_id', '=', 'rooms.id')
            ->join('rooms_category', 'rooms.category_id', '=', 'rooms_category.id') // Get room category name
            ->where('admitted_patients.isdeleted', 0)
            ->orderBy('admitted_patients.id','desc')
            ->get();
          //  dd($admit_patients);
        return Datatables::of($admit_patients)
            ->addIndexColumn()
            ->addColumn('patient', function ($appointment) {
                return ($appointment->patient_name);
            })
            ->addColumn('doctor', function ($appointment) {
                return ($appointment->doctor_name);
            })
            ->addColumn('rooms', function ($admit_patients) {
                $room_number = $admit_patients->room_number ?? '-';
                $room_category = $admit_patients->room_category ?? '-';

                return "{$room_number} - {$room_category}";
            })
            ->addColumn('discharge_date', function ($admit_patients) {
                return $admit_patients->discharge_date ? date('d-m-Y', strtotime($admit_patients->discharge_date)) : '-';
            })
            ->addColumn('status', function ($admit_patients) {
                $statusLabels = [
                    1 => 'Admitted',
                    2 => 'Discharged',
                    3 => 'Transferred'
                ];
                $statusText = $statusLabels[$admit_patients->status] ?? 'Unknown';

                if($admit_patients->status==2)
                {
                    return '<a href="'.route('admin.generatebill',['id'=>$admit_patients->id]).'" class="btn btn-sm btn-primary">Generate Bill</a>';
                   
                }
                else if($admit_patients->status==1)
                {
                    return '<button class="btn btn-sm btn-secondary" disabled>Admitted</button>';
                }
                else
                {
                    return '<button class="btn btn-sm btn-warning" disabled>Admitted</button>';
                }
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
                             <a class="btn btn-sm bg-success-light" data-toggle="modal" href="javascript:void(0);" id="edit_admitdetails" data-id="' . $row->id . '">
                                   <i class="fe fe-pencil"></i>
                               </a>
                               <a data-toggle="modal" class="btn btn-sm bg-danger-light" href="javascript:void(0);" id="delete_admitdetails" data-id="' . $row->id . '">
                                   <i class="fe fe-trash"></i>
                               </a>
                           </div>';

                return $action;
            })
            ->rawColumns(['action', 'patient', 'doctor', 'rooms', 'status', 'discharge_date'])
            ->make(true);
    }

    public function delete(Request $request)
    {
        $post = $request->post();
        $id = isset($post['id']) ? $post['id'] : "";
        $response['status']  = 0;
        $response['message']  = "Somthing Goes Wrong!";
        if (is_numeric($id)) {
            $delete_medicines = AdmitPatient::where('id', $id)->update(['isdeleted' => 1]);
            if ($delete_medicines) {
                $response['status'] = 1;
                $response['message'] = 'Admitted Patient deleted successfully.';
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
        // $id = isset($post['id']) ? $post['id'] : "";
        $response['status']  = 0;
        $response['message']  = "Somthing Goes Wrong!";
        if (is_numeric($id)) {
            $admit_patient = AdmitPatient::where('id', $id)->first();
            if ($admit_patient) {
                $response['status'] = 1;
                $response['data'] = $admit_patient;
            } else {
                $response['message'] = 'Admitted Patient not found.';
            }
        }
        echo json_encode($response);
        exit;
    }
}
