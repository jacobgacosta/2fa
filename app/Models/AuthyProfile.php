<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthyProfile extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_2fa_info';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'authy_id',
    ];
}
