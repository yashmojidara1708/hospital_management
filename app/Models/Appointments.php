<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointments extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'appointments';
    public $timestamps = false;

    // Fillable properties for mass assignment
    protected $fillable = [
        'id',
        'doctor',
        'specialization',
        'patient',
        'date',
        'time',
        'status',
        'is_completed',
    ];
}