<?php

namespace App\Helpers;

use App\Models\Patients;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        $setting = Setting::where('key', $key)->value('value');
        return $setting ?? $default;
    }
}
class GlobalHelper
{
    /**
     * Get all countries from the 'countries' table.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllCountries()
    {
        return DB::table('countries')->select('id', 'code', 'name')->orderBy('name')->get();
    }


    /**
     * Get all states from the 'states' table.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllStates()
    {
        return DB::table('states')
            ->select('id', 'name', 'country_id')
            ->orderBy('name')
            ->get();
    }
    public static function getAllCities()
    {
        return DB::table('cities')
            ->select('id', 'name', 'state_id', 'country_id')
            ->orderBy('name')
            ->get();
    }
    /**
     * Get all states from the 'roles' table.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllRoles()
    {
        return DB::table('roles')
            ->select('id', 'name')
            ->where('isdeleted', '!=', 1)
            ->orderBy('name')
            ->get();
    }
    /**
     * Get all specialities from the 'specilaites' table.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllSpecialities()
    {
        return DB::table('specialities')
            ->select('id', 'name')
            ->where('isdeleted', '!=', 1)
            ->orderBy('name')
            ->get();
    }

    public static function getAllDoctors()
    {
        return DB::table('doctors')
        ->select('doctors.*', 'specialities.name as specialization_name') // Add specialization name
        ->join('specialities', 'doctors.specialization', '=', 'specialities.id') // Join with specialities table
        ->where('doctors.isdeleted', '!=', 1)
        ->orderBy('doctors.name')
        ->get()
        ->map(function($doctor) {
            $imagePath = "assets/admin/theme/img/doctors/" . $doctor->image;
            $defaultImage = asset("assets/admin/theme/img/doctors/default.jpg");

            // Check if the file exists using the public path
            $imageUrl = (!empty($doctor->image) && file_exists(public_path($imagePath)))
                ? asset($imagePath)
                : $defaultImage;

            // Modify the doctor data to include the image HTML
            $doctor->avatar = '
                <h2 class="table-avatar">
                    <a href="#" class="avatar avatar-sm mr-2">
                        <img src="' . $imageUrl . '" width="50" height="50" class="rounded-circle" alt="User Image">
                    </a>
                    <a href="#">' . e($doctor->name) . '</a>
                </h2>';

            return $doctor;
        });
    }

    public static function getAllPatients()
    {
        return DB::table('patients')
            ->select('patients.*')
            ->orderBy('name')
            ->where('isdeleted', '!=', 1)
            ->get();
    }

    public static function getAllRooms()
    {
        return DB::table('rooms')
        ->join('rooms_category', 'rooms.category_id', '=', 'rooms_category.id')
        ->select('rooms.id', 'rooms.room_number', 'rooms_category.name as category_name')
        ->where('rooms.status', '!=', 0)
        ->orderBy('rooms.room_number')
        ->get();
    }

    public static function getAllRoomCategories()
    {
        return DB::table('rooms_category')
            ->select('id', 'name')
            ->where('isdeleted', '!=', 1)
            ->orderBy('name')
            ->get();
    }
    // all admited pation list
    public static function getAllAdmittedPatients()
    {
        return DB::table('admitted_patients')
            ->join('patients', 'admitted_patients.patient_id', '=', 'patients.patient_id')
            ->join('doctors', 'admitted_patients.doctor_id', '=', 'doctors.id')
            ->join('specialities', 'doctors.specialization', '=', 'specialities.id')
            ->join('rooms', 'admitted_patients.room_id', '=', 'rooms.id')
            ->join('rooms_category', 'rooms.category_id', '=', 'rooms_category.id') // Get room category name
            ->where('admitted_patients.isdeleted', 0)
            ->orderBy('admitted_patients.id', 'desc')
            ->get();
    }
    // now get all patients and getAllAdmittedPatients match if admitted not show in this patient list
    public static function getAllPatientsList()
    {
        $admittedPatientIds = DB::table('admitted_patients')
            ->where('isdeleted', 0)
            ->pluck('patient_id');

        return DB::table('patients')
            ->select('patient_id', 'name')
            ->where('isdeleted', '!=', 1)
            ->whereNotIn('patient_id', $admittedPatientIds)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get patient data by ID.
     *
     * @param int $patientId
     * @return Patients|null
     */
    public static  function getStatesByCountry($country_id)
    {
        return DB::table('states')
            ->where('country_id', $country_id)
            ->get();
    }
    public static function getCitiesByState($state_id)
    {
        return DB::table('cities')
            ->where('state_id', $state_id)
            ->get();
    }
    public static function getPatientById($patientId)
    {
        return Patients::select('patients.*', 'countries.name as country', 'states.name as state', 'cities.name as city')
            ->where('patient_id', $patientId)
            ->where('isdeleted', '!=', 1)
            ->leftJoin('countries', 'patients.country', '=', 'countries.id')
            ->leftJoin('states', 'patients.state', '=', 'states.id')
            ->leftJoin('cities', 'patients.city', '=', 'cities.id')
            ->first();
    }

    public static function getPatientPrescriptions($patientId, $doctorId)
    {
        return DB::table('prescriptions')
            ->join('patients', 'patients.patient_id', '=', 'prescriptions.patient_id')
            ->join('doctors', 'doctors.id', '=', 'prescriptions.doctor_id')
            ->join('prescriptions_item', 'prescriptions_item.prescription_id', '=', 'prescriptions.id')
            ->join('medicines', 'medicines.id', '=', 'prescriptions_item.medicine_name')
            ->where('prescriptions.patient_id', $patientId)
            ->where('prescriptions.doctor_id', $doctorId)
            ->where('prescriptions.isdeleted', 0)
            ->select(
                'prescriptions.id',
                'prescriptions.instructions',
                'prescriptions.created_at',
                'doctors.name as doctor_name',
                'patients.name as patient_name',
                DB::raw('GROUP_CONCAT(medicines.name SEPARATOR ", ") as medicine_names'),
                DB::raw('GROUP_CONCAT(prescriptions_item.quantity SEPARATOR ", ") as quantities'),
                DB::raw('GROUP_CONCAT(prescriptions_item.days SEPARATOR ", ") as days'),
                DB::raw('GROUP_CONCAT(prescriptions_item.time SEPARATOR ", ") as times')
            )
            ->groupBy(
                'prescriptions.id',
                'prescriptions.instructions',
                'prescriptions.created_at',
                'doctor_name',
                'patient_name'
            )
            ->get();
    }


    public static function formatPrescriptionData($prescriptions)
    {
        return $prescriptions->map(function ($prescription) {
            $prescription->medicine_names = explode(',', $prescription->medicine_names);
            $prescription->quantities = explode(',', $prescription->quantities);
            $prescription->days = explode(',', $prescription->days);
            $prescription->times = explode(',', $prescription->times);

            return $prescription;
        });
    }

    public static function getSetting($key, $default = null)
    {
        return Setting::where('key', $key)->value('value') ?? $default;
    }
}
