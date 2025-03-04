<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    //
    protected $primaryKey = 'id';
    protected $table = 'Doctors';
    public $timestamps = false;

    // Fillable properties for mass assignment
    protected $fillable = [
        'id',
        'name',
        'specialization',
        'phone',
        'email',
        'experience',
        'qualification',
        'address',
        'country',
        'city',
        'state',
        'zip',
        'image',
    ];
}


