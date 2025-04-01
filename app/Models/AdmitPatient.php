<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmitPatient extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'admitted_patients';
    public $timestamps = false;

    // Fillable properties for mass assignment
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'room_id',
        'admit_date',
        'discharge_date',
        'admission_reason',
        'status',
    ];
}
