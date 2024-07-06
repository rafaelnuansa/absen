<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cuty extends Model
{
    use HasFactory;

    protected $table = 'cuty';
    protected $primaryKey = 'cuty_id';
    protected $guarded = ['cuty_id'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }
}
