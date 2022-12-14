<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerifications extends Model
{
    use HasFactory;

    protected $table = "user_verifications";

    protected $fillable = [
        'name',
        'email',
        'password',
        'code'
    ];

    protected $hidden = [
        'password',
    ];

}
