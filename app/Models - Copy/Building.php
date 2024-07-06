<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model
{
    use HasFactory;

    protected $table = 'building';
    protected $primaryKey = 'building_id';
    protected $guarded = ['building_id'];


    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'building_id', 'building_id');
    }
}
