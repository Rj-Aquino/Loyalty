<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoyaltyCardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('LoyaltyCards')->insert([
            [
                'FirstName' => 'John',
                'LastName' => 'Doe',
                'MiddleInitial' => 'A',
                'Suffix' => null,
                'ContactNo' => '09123456789',
                'Points' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'FirstName' => 'Jane',
                'LastName' => 'Smith',
                'MiddleInitial' => 'B',
                'Suffix' => 'Jr',
                'ContactNo' => '09187654321',
                'Points' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'FirstName' => 'Alice',
                'LastName' => 'Brown',
                'MiddleInitial' => null,
                'Suffix' => null,
                'ContactNo' => '09234567890',
                'Points' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
