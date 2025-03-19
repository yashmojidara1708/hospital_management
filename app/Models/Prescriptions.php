<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescriptions extends Model
{
    //
    protected $primaryKey = 'id';
    protected $table = 'prescriptions';
    public $timestamps = true;

    // Fillable properties for mass assignment
    protected $fillable = [
        'id',
        'doctor_id',
        'patient_id',
        'instructions',
        'isdeleted',
    ];
}
