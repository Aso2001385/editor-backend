<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectDesign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'design_id',
        'class'
    ];
    protected $datas = ['deleted_at'];

    protected $hidden = ['class'];
}
