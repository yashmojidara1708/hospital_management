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
        return DB::table('countries')->select('id','code', 'name')->orderBy('name')->get();
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
        ->select('id', 'name','state_id','country_id')
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
            ->where('isdeleted', '!=',1)
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
            ->where('isdeleted', '!=',1)
               ->orderBy('name')
            ->get();
    }

    public static function getAllDoctors()
    {
        return DB::table('doctors')
            ->select('id', 'name')
            ->where('isdeleted', '!=', 1)
            ->orderBy('name')
            ->get();
    }

    public static function getAllPatients()
    {
        return DB::table('patients')
            ->select('patient_id', 'name')
            ->orderBy('name')
            ->where('isdeleted', '!=', 1)
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
        return Patients::select('patients.*','countries.name as country', 'states.name as state', 'cities.name as city')
        ->where('patient_id', $patientId)
            ->where('isdeleted', '!=', 1)
            ->leftJoin('countries', 'patients.country', '=', 'countries.id')
            ->leftJoin('states', 'patients.state', '=', 'states.id')
            ->leftJoin('cities', 'patients.city', '=', 'cities.id')
            ->first();
    }
}
