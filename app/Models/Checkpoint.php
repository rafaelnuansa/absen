<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Checkpoint extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

   
    public function patrols(): HasMany
    {
        return $this->hasMany(Patrol::class);
    }
  
    
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    // Relasi untuk mendapatkan karyawan melalui building
    public function employees()
    {
        // Menggunakan relasi hasManyThrough untuk mengakses employees melalui building
        return $this->building->employees();
    }


    protected $casts = [
        'id' => 'integer',
        'building_id' => 'integer',
    ];
}
