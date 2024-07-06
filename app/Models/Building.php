<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function checkpoints()
    {
        return $this->hasMany(Checkpoint::class);
    }

    protected $casts = [
        'id' => 'integer',
    ];
}
