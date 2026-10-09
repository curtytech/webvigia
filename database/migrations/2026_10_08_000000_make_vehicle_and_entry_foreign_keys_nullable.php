<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->foreignId('person_id')->nullable()->change();
        });

        Schema::table('entries', function (Blueprint $table) {
            $table->foreignId('person_id')->nullable()->change();
            $table->foreignId('car_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // These relationships are optional in the intended schema.
    }
};
