<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'ui'
    ];
    protected $datas = ['deleted_at'];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'ui'  => 'json'
    ];

    public function designs()
    {
        return $this->belongsToMany(Design::class,ProjectDesign::class,'project_id','design_id')->withTimestamps();
    }

    public function pages(){
        return $this->hasMany(Page::class);
    }
}
