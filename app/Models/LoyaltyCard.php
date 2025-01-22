<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LoyaltyCard extends Model
{
    use HasFactory;

    protected $primaryKey = 'LoyaltyCardID'; // Explicitly defining the primary key
    protected $fillable = [
        'FirstName',
        'LastName',
        'MiddleInitial',
        'Suffix',
        'ContactNo',
        'Points',
        'UniqueIdentifier',
    ];

    // Automatically generate UniqueIdentifier when creating the loyalty card
    public static function boot()
    {
        parent::boot();

        static::creating(function ($loyaltyCard) {
            // Generate UniqueIdentifier in the format 'LID-Random'
            if (!$loyaltyCard->UniqueIdentifier) {
                $loyaltyCard->UniqueIdentifier = 'LID-' . strtoupper(Str::random(6));
            }
        });
    }
}
