<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'date_started',
        'status',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
