<?php

namespace App\Models;

use Attla\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'created_at',
        'updated_at',
    ];

    /**
     * Indicates if the model should be timestamped
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    protected $hidden = [
        // 'password',
    ];

    /**
     * The attributes that should be cast to native types
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
