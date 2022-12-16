<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'number',
        'user_id',
        'design_id',
        'title',
        'contents'
    ];

    protected $table = "pages";

    protected $datas = ['deleted_at'];
}
