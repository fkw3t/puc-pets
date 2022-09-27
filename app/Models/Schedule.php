<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'vet_id',
        'client_id',
        'date',
        'status'
    ];

    public function client(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'client_id');
    }

    public function vet(): BelongsTo
    {
        return $this->belongsTo(Vet::class);
    }
}
