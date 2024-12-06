<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users_Password_Resets extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'email',
        'token'
    ];

    protected $table = 'users_password_resets';

}

