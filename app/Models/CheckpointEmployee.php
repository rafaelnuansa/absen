<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckpointEmployee extends Model
{
    use HasFactory;
    protected $guarded = ['id'];



    protected $casts = [
        'employee_id' => 'integer',
        'checkpoint_id' => 'integer',
];
}
