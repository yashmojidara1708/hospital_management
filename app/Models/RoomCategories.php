<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomCategories extends Model
{
    //
    protected $primaryKey = 'id';
    protected $table = 'room_categories';
    public $timestamps = false;

    // Fillable properties for mass assignment
    protected $fillable = [
        'id',
        'name',
    ];
}
