<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [        
        'description',
        'person_in_charge',       
        'percentage',
        'acknowledge_by',     
        'type',
        'amount',
        'transaction_date',
    ];
}
