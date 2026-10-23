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
        Schema::create('scheduling_places', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('scheduling_places_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheduling_places_id')->constrained('scheduling_places')->cascadeOnDelete();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('responsible_name');
            $table->string('responsible_email');
            $table->timestamps();
        });

        Schema::create('scheduling', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheduling_places_relations_id')->constrained('scheduling_places_relations')->cascadeOnDelete();
            $table->string('responsible_name');
            $table->string('responsible_email');
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
        Schema::dropIfExists('scheduling_places');
        Schema::dropIfExists('scheduling_places_relations');
        Schema::dropIfExists('scheduling');
    }
};
