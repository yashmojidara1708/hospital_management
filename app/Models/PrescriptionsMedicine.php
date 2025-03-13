<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionsMedicine extends Model
{
    //
    protected $primaryKey = 'id';
    protected $table = 'prescriptions_medicines';
    public $timestamps = false;

    // Fillable properties for mass assignment
    protected $fillable = [
        'id',
       'prescription',
       'medicine',
       'quantity',
       'days',
       'time',
       'isdeleted',
    ];
}
