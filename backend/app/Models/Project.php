<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name'
    ];
    protected $datas = ['deleted_at'];

    public function designs()
    {
    return $this->belongsToMany(Design::class,ProjectDesign::class,'project_id','design_id')
    ->withTimestamps();
}
}
