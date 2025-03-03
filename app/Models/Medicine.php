<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'medicines';
    public $timestamps = false;

    // Fillable properties for mass assignment
    protected $fillable = [
        'name',
        'price',
        'stock',
        'expiry_date',
        'isdeleted',
    ];
}
