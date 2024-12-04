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

        Schema::create('valve_readings', function (Blueprint $table) {
            $table->id();
            $table->string('equipment_code');
            $table->string('vd')->nullable();
            $table->string('vl')->nullable();
            $table->string('ve')->nullable();
            $table->string('vr')->nullable();
            $table->string('v5')->nullable();
            $table->string('v6')->nullable();
            $table->string('v7')->nullable();
            $table->string('v8')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valve_readings');
    }
};
