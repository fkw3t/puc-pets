<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'vet_id',
        'pet_id',
        'client_id',
        'date',
        'service',
        'status',
    ];

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value->format('d-m-Y H:i')
        );
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function client(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'client_id');
    }

    public function vet(): BelongsTo
    {
        return $this->belongsTo(Vet::class);
    }
}
