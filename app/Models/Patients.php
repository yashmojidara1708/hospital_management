<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    protected $primaryKey = 'patient_id';
    protected $table = 'Patients';
    public $timestamps = false;

    // Fillable properties for mass assignment
    protected $fillable = [
        'patient_id',
        'name',
        'age',
        'address',
        'country',
        'city',
        'state',
        'zip',
        'phone',
        'email',
        'last_visit',
        'status',
    ];
}
