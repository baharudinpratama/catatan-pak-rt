<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class House extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'house_number',
        'address',
        'description',
        'house_image',
        'active',
    ];

    protected $appends = ['has_resident', 'has_resident_text'];

    protected function houseImage(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value == null) {
                    return url('/images/houses/default.jpg');
                } else {
                    return url('/storage/images/houses/' . $value);
                }
            }
        );
    }

    protected function hasResident(): Attribute
    {
        return Attribute::make(
            get: function () {
                return sizeof($this->residentHouses) > 0 ?
                    true : false;
            },
        );
    }

    protected function hasResidentText(): Attribute
    {
        return Attribute::make(
            get: function () {
                return sizeof($this->residentHouses) > 0 ?
                    'Dihuni' : 'Tidak Dihuni';
            },
        );
    }

    public function residentHouses(): HasMany
    {
        return $this->hasMany(ResidentHouse::class, 'house_id');
    }
}
