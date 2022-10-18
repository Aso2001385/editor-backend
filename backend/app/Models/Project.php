<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'name'
    ];
    protected $datas = ['deleted_at'];

}
