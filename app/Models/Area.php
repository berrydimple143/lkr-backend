<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [        
        'name',
        'status',       
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
