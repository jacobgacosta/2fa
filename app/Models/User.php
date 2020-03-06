<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
    ];

    /**
     * Define a many-to-many relationship with the role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function authyProfile(): HasOne
    {
        return $this->hasOne(AuthyProfile::class, 'user_id');
    }
}
