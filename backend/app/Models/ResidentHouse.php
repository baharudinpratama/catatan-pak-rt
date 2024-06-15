<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResidentHouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'resident_house';

    protected $fillable = [
        'house_id',
        'resident_id',
        'residential_status_id',
    ];

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class, 'house_id');
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }

    public function residentialStatus(): BelongsTo
    {
        return $this->belongsTo(ResidentialStatus::class, 'residential_status_id');
    }

    public function maintenancePayments(): HasMany
    {
        return $this->hasMany(MaintenancePayment::class, 'resident_house_id');
    }
}
