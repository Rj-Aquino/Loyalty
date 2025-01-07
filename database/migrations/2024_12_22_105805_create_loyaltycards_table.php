<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyCardsTable extends Migration
{
    public function up()
    {
        Schema::create('LoyaltyCards', function (Blueprint $table) {
            $table->id('LoyaltyCardID'); // Auto-incrementing primary key, explicitly named member_ID
            $table->string('FirstName'); // First name column
            $table->string('LastName'); // Last name column
            $table->char('MiddleInitial', 1)->nullable(); // Middle initial column, nullable, max length 1 character
            $table->char('Suffix', 10)->nullable(); // Suffix column, nullable, max length 10 characters
            $table->string('ContactNo'); // Contact number column
            $table->integer('Points')->default(0); // Points column, default value 0
            $table->timestamps(); // created_at and updated_at columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('LoyaltyCards'); // Drop the table if it exists
    }
}
