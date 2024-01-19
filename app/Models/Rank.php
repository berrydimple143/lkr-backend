<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'alias',
    ];

    public function crews()
    {
      return $this->hasMany(Crew::class);
    }
}
