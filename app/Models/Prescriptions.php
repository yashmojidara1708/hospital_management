<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescriptions extends Model
{
    //
    protected $primaryKey = 'id';
    protected $table = 'prescriptions';
    public $timestamps = false;

    // Fillable properties for mass assignment
    protected $fillable = [
        'id',
       'doctor',
       'appointment',
       'patient',
       'date',
       'instructions',
       'status',
       'isdeleted',
    ];
}
