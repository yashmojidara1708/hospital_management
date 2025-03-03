<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    public $table="roles";
    public $timestamps=false;
    protected $guarded=[];
    public function users()
    {
        return $this->hasMany(User::class,"role_id");
    }

}
