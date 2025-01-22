<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyCard extends Model
{
    use HasFactory;

    protected $primaryKey = 'LoyaltyCardID'; // Default primary key

    protected $fillable = [
        'FirstName',
        'LastName',
        'MiddleInitial',
        'Suffix',
        'ContactNo',
        'Points',
        'UniqueIdentifier', // You can keep UniqueIdentifier here, but remove logic for generation
    ];
}
