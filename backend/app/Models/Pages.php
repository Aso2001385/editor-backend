<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pages extends Model
{
    use HasFactory,softDeletes;

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'number',
        'user_id',
        'design_id',
        'title',
        'contents'
    ];

    protected $datas = ['deleted_at'];
}
