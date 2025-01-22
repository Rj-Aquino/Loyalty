<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoyaltyCard;

class LoyaltyCardSeeder extends Seeder
{
    public function run()
    {
        LoyaltyCard::create([
            'FirstName' => 'John',
            'LastName' => 'Doe',
            'MiddleInitial' => 'A',
            'Suffix' => '',
            'ContactNo' => '1234567890',
            'Points' => 100,
            'UniqueIdentifier' => $this->generateUniqueIdentifier(),
        ]);

        LoyaltyCard::create([
            'FirstName' => 'Jane',
            'LastName' => 'Smith',
            'MiddleInitial' => 'B',
            'Suffix' => 'Jr',
            'ContactNo' => '0987654321',
            'Points' => 200,
            'UniqueIdentifier' => $this->generateUniqueIdentifier(),
        ]);
    }

    private function generateUniqueIdentifier()
    {
        $randomLetter = chr(rand(65, 90)); // Generate a random uppercase letter
        $randomNumber = rand(1000, 9999); // Generate a random number
        return 'LID-' . $randomLetter . $randomNumber;
    }
}
