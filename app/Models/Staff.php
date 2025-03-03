<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'staff';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'roles',
        'date_of_birth',
        'phone',
        'email',
        'password',
        'address',
        'country',
        'state',
        'city',
        'zip'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'roles' => 'array', // Automatically cast roles as an array
    ];
    /**
     * Automatically hash the password when setting it.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
