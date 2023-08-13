<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('my_parents', function (Blueprint $table) {
            $table->id();

            $table->string('Email')->unique();
            $table->string('Password');

            // Father info
            $table->string('Name_Father');
            $table->string('National_ID_Father');
            $table->string('Passport_ID_Father');
            $table->string('Phone_Father');
            $table->string('Job_Father');
            $table->foreignId('Religion_Father_id')->constrained('religions')->cascadeOnDelete();
            $table->foreignId('Nationality_Father_id')->constrained('nationalities')->cascadeOnDelete();
            $table->foreignId('Blood_Type_Father_id')->constrained('bloods')->cascadeOnDelete();
            $table->string('Address_Father');

            // Mother info
            $table->string('Name_Mother');
            $table->string('National_ID_Mother');
            $table->string('Passport_ID_Mother');
            $table->string('Phone_Mother');
            $table->string('Job_Mother');
            $table->foreignId('Religion_Mother_id')->constrained('religions')->cascadeOnDelete();
            $table->foreignId('Nationality_Mother_id')->constrained('nationalities')->cascadeOnDelete();
            $table->foreignId('Blood_Type_Mother_id')->constrained('bloods')->cascadeOnDelete();
            $table->string('Address_Mother');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_parents');
    }
};
