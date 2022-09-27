<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'crm',
        'specialization'
    ];

    public function details(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'vet_id', 'id');
    }
}
