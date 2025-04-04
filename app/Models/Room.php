<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $primaryKey = 'id';
    protected $fillable = ['category_id', 'room_number', 'beds', 'charges', 'status','isdeleted'];

    // Relationship: Each room belongs to one category
    public function category()
    {
        return $this->belongsTo(RoomCategory::class, 'category_id');
    }
}
