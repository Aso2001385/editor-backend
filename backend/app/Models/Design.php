<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Design extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'point',
        'contents'
    ];

    protected $datas = ['deleted_at'];

    protected $hidden = [
        'id',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class,ProjectDesign::class,'project_id','design_id')
        ->as('buy')
        ->withTimestamps();
    }
}
