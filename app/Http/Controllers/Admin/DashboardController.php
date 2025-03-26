<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Mail\AppointmentNotification;
use App\Models\Appointments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        $tables = ['doctors', 'staff', 'patients'];
        $counts = [];

        foreach ($tables as $table) {
            $counts[$table] = DB::table($table)
                ->where('isdeleted', '!=', 1)
                ->count();
        }

        return view('admin.dashboard.index', compact('counts'));
        // return view('admin.dashboard.index');
    }

    public function fetchUpdatedAppointments()
    {
        $DoctorData = session('doctors_data');
        $DoctorEmail = isset($DoctorData['email']) ? $DoctorData['email'] : '';

        $appointments = Appointments::join('Patients', 'appointments.patient', '=', 'Patients.patient_id')
            ->join('doctors', 'appointments.doctor', '=', 'doctors.id')
            ->select(
                'appointments.id',
                'appointments.doctor',
                'appointments.specialization',
                'appointments.date',
                'appointments.time',
                'appointments.status',
                'Patients.name as patient_name',
                'Patients.phone as phone',
                'Patients.last_visit as last_visit',
                'Patients.patient_id',
                'Patients.phone',
                'Patients.email',
                'doctors.name as doctor_name',
                'doctors.email as doctor_email'
            )
            ->where('doctors.email', $DoctorEmail)
            ->where('appointments.status', '1')
            ->where('appointments.is_completed', '0')
            ->orderBy('appointments.date', 'asc')
            ->get();

        return response()->json(['status' => 'success', 'appointments' => $appointments]);
    }

    public function updateAppointmentStatus(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['appointmentId'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid request'], 400);
        }

        // Find the appointment with patient details
        $appointment = Appointments::select('appointments.*', 'patients.email as patient_email', 'patients.name as patient_name')
            ->leftJoin('patients', 'appointments.patient', '=', 'patients.patient_id')
            ->where('appointments.id', $data['appointmentId'])
            ->first();

        if (!$appointment) {
            return response()->json(['status' => 'error', 'message' => 'Appointment not found'], 404);
        }

        // Update status
        $appointment->is_completed = $data['is_completed'];
        $appointment->save();

        // If appointment is rejected (-1), send email
        if ($data['is_completed'] == -1) {
            $reason = $data['reason'] ?? "No reason provided";
            Mail::to($appointment->patient_email)->send(new AppointmentNotification($appointment, "rejected", $reason));
        }

        return response()->json(['status' => 'success', 'message' => 'Appointment status updated' . ($data['is_completed'] == -1 ? ' & email sent' : '')]);
    }

    public function updateAllAppointmentsStatus(Request $request)
    {
        $data = json_decode($request->getContent(), true);
  
        // Validate input
        $validator = Validator::make($data, [
            'appointmentIds' => 'required|array',
            'appointmentIds.*' => 'required|integer|exists:appointments,id',
            'status' => 'required|integer|in:1,-1', // 1 for approve, -1 for reject
            'reason' => 'nullable|string|max:500' // Optional for rejections
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $status = $data['status'];
        $appointmentIds = $data['appointmentIds'];
        $type = $status == 1 ? 'approved' : 'rejected';
        $reason = $data['reason'] ?? null;

        try {
            // 1. Update all appointments status
            $updatedCount = Appointments::whereIn('id', $appointmentIds)
                ->update(['is_completed' => $status]);

            // 2. Get all updated appointments with patient emails
            $appointments = Appointments::select(
                'appointments.*',
                'patients.email as patient_email'
            )
                ->leftJoin('patients', 'appointments.patient', '=', 'patients.patient_id')
                ->whereIn('appointments.id', $appointmentIds)
                ->get();
           
            // 3. Send email notifications
            foreach ($appointments as $appointment) {
                if ($appointment->patient_email) {
                    Mail::to($appointment->patient_email)->send(new AppointmentNotification($appointment, "rejected", $reason));
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => "Successfully {$type} {$updatedCount} appointment(s)",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update appointments',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
