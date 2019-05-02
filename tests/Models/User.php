<?php

namespace henry\sw1\Tests\Models;

use henry\sw1\Traits\sw1Trait;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use sw1Trait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}