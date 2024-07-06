<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject; // <-- import JWTSubject

use Illuminate\Foundation\Auth\User as Authenticatable;
class Employee extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $guard_name ='api';
    protected $guarded = ['id'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'building_id' => 'integer',
        'position_id' => 'integer',
        'is_active' => 'integer',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    // Relasi untuk mengambil building yang terkait dengan employee
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    // Method untuk mengambil checkpoints berdasarkan building_id employee
    public function checkpoints()
    {
        // Menggunakan relasi hasManyThrough untuk mengakses checkpoints melalui building
        return $this->building->checkpoints();
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function getAttendance($date)
    {
        return $this->presences()->whereDate('date', $date)->first();
    }
    
    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function patrols()
    {
        return $this->hasMany(Patrol::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function buildingCheckpoints()
    {
        return Checkpoint::where('building_id', $this->building_id)->get();
    }
        
    /**
     * getJWTCustomClaims
     *
     * @return void
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    
}
