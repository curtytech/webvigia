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
        Schema::create('scheduling', function (Blueprint $table) {
            $table->id();
            $table->string('responsible_name');
            $table->string('phone_number');
            $table->mediumText('motivation');
            $table->integer('people_quantity');
            $table->dateTime('scheduled_at');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduling');
    }
};
