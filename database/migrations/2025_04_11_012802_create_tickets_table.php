<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('user_email');
            $table->string('user_phone')->nullable();
            $table->text('user_description');
            $table->string('ticket_number')->unique();
            $table->timestamps();
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
