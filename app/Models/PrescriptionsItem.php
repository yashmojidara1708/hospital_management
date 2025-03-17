<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionsItem extends Model
{
    //
    protected $primaryKey = 'id';
    protected $table = 'prescriptions_item';
    public $timestamps = false;

    // Fillable properties for mass assignment
    protected $fillable = [
        'id',
        'prescription_id',
        'medicine_name',
        'quantity',
        'days',
        'time',
        'isdeleted',
    ];
}
