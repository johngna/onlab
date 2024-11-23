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
        Schema::create('reserv_readings', function (Blueprint $table) {
            $table->id();
            $table->string('equipment_code'); // código do equipamento
            $table->string('gal_0'); // nível do galão
            $table->string('gal_1'); // nível do galão
            $table->string('gal_2'); // nível do galão
            $table->string('gal_3'); // nível do galão
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserv_readings');
    }
};
