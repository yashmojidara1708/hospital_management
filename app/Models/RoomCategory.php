<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomCategory extends Model
{
    use HasFactory;
    protected $table = 'rooms_category';
    protected $primaryKey = 'id';
    protected $fillable = ['name'];
}
