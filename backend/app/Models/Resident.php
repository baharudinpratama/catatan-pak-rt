<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'id_card_number',
        'id_card_image',
        'phone_number',
        'marital_status',
        'active',
    ];

    protected $appends = ['marital_status_text', 'is_resident', 'is_resident_text'];

    protected function idCardImage(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value == null) {
                    return url('/storage/images/id-cards/default.jpg');
                } else {
                    return url('/storage/images/houses/' . $value);
                }
            }
        );
    }

    protected function maritalStatusText(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->marital_status == 1 ?
                    'Terikat Pernikahan' : 'Tidak Terikat Pernikahan';
            },
        );
    }

    protected function isResident(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->residentHouse ? true : false;
            }
        );
    }

    protected function isResidentText(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->residentHouse ? 'Penghuni' : 'Bukan Penghuni';
            }
        );
    }

    public function residentHouse(): HasOne
    {
        return $this->hasOne(ResidentHouse::class, 'resident_id');
    }
}
