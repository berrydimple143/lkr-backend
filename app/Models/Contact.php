<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'mobile',
        'address',
        'price',
        'measure',
        'date_bought',
        'fax',
        'phase',
        'block',
        'lot',
        'barangay',
        'district',
        'building_number',
        'house_number',
        'unit_number',
        'street',
        'city',
        'municipality',
        'province',
        'region',
        'area_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
