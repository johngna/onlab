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

        Schema::create('actuator_readings', function (Blueprint $table) {
            $table->id();
            $table->string('equipment_code');
            $table->string('som')->nullable();
            $table->string('lamp_uv')->nullable();
            $table->string('b_pwm')->nullable();
            $table->string('coolers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actuator_readings');
    }
};
