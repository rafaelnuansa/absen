<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Employee extends Authenticatable
{
    use Notifiable, HasFactory;
    protected $guarded = ['id'];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'shift_id');
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id', 'building_id');
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class, 'employee_id', 'id');
    }

    public function getAuthIdentifierName()
    {
        return 'id'; // Kolom yang berisi identifier pengguna (biasanya 'id')
    }

    public function getAuthPassword()
    {
        return $this->employees_password; // Kolom yang berisi password pengguna
    }

}
