<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Design extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'point'
    ];

    protected $datas = ['deleted_at'];

    public function projects()
    {
        return $this->belongsToMany(Project::class)
            ->as('buy')
            ->withTimestamps();
    }
}
