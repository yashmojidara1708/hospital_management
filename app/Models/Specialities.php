<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialities extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'specialities';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'status',
    ];
}
