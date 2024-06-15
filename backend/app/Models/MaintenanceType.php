<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'fee',
    ];

    public function maintenancePayments(): HasMany
    {
        return $this->hasMany(MaintenancePayment::class, 'maintenance_type_id');
    }
}
