<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    //
    protected $primaryKey = 'id';
    protected $table = 'doctors';
    public $timestamps = false;

    // Fillable properties for mass assignment
    protected $fillable = [
        'id',
        'name',
        'role',
        'specialization',
        'phone',
        'email',
        'password',
        'experience',
        'qualification',
        'address',
        'country',
        'city',
        'state',
        'zip',
        'image',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];


    /**
     * Automatically hash the password when setting it.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
