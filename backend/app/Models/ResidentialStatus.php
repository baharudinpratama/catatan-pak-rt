<?php

namespace App\Models;

use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResidentialStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    public function residentHouses(): HasMany
    {
        return $this->hasMany(Resident::class, 'residential_status');
    }
}
