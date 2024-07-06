<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatrolPhoto extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function patrol()
    {
        return $this->belongsTo(Patrol::class);
    }
}
