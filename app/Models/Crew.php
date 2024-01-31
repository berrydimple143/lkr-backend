<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Crew extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'email',
        'birth_date',
        'age',
        'height',
        'weight',
        'rank_id',
        'user_id',
    ];

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
      return $this->hasMany(Document::class);
    }
}
