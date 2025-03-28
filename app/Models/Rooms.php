<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    //
    protected $primaryKey = 'id';
    protected $table = 'rooms';
    public $timestamps = false;

    // Fillable properties for mass assignment
    protected $fillable = [
        'id',
        'category_id',
        'total_rooms',
        'beds_per_room',
        'charges_per_bed',
        'status',
    ];
}
