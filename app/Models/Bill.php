<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    //
    protected $primaryKey = 'id';
    protected $table = 'bill';
    public $timestamps = false;

    // Fillable properties for mass assignment
    protected $fillable = [
        'id',
        'admitted_id',
        'doctor_id',
        'patient_id',
        'room_number',
        'admission_date',
        'discharge_date',
        'total_days',
        'room_charge',
        'doctor_fees',
        'discount',
        'discount_amount',
        'total_amount',
        'status',
        'generated_by',
        'generated_at',
    ];
}
