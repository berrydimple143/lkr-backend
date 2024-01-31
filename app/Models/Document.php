<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Document extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'code',
        'document_name',
        'document_number',
        'filename',
        'issue_date',
        'expiry_date',
        'crew_id',
        'user_id',
    ];

    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
