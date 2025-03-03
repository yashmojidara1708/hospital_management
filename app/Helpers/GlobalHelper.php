<?php

namespace App\Helpers;

use App\Models\Patients;
use Illuminate\Support\Facades\DB;

class GlobalHelper
{
    /**
     * Get all countries from the 'countries' table.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllCountries()
    {
        return DB::table('countries')->select('code', 'name')->orderBy('name')->get();
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

    /**
     * Get all states from the 'roles' table.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllRoles()
    {
        return DB::table('roles')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get patient data by ID.
     *
     * @param int $patientId
     * @return Patients|null
     */
    function getPatientById($patientId)
    {
        return Patients::where('patient_id', $patientId)
            ->where('isdeleted', '!=', 1)
            ->first();
    }
}
