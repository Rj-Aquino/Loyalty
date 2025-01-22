<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyCard extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional, as Laravel will guess it)
    protected $table = 'LoyaltyCards';

    // Specify the primary key (optional, as Laravel will guess it)
    protected $primaryKey = 'LoyaltyCardID';

    // Define the fillable fields that can be mass-assigned
    protected $fillable = [
        'FirstName', 
        'LastName', 
        'MiddleInitial', 
        'Suffix', 
        'ContactNo',
        'Points',
        'UniqueIdentifier'
    ];

    // If you don't want Laravel to automatically manage the timestamps, set this to false
    public $timestamps = true;
}
