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


        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->string('equipment_code'); // código do equipamento
            $table->float('cd_ou'); // condutivímetro
            $table->float('cd_md'); // condutivímetro
            $table->float('cd_in'); // condutivímetro
            $table->float('fx_md'); // fluxo
            $table->float('fx_in'); // fluxo
            $table->float('temp1'); // temperatura
            $table->float('tplc1'); // temperatura
            $table->integer('t_pre'); // pressão
            $table->integer('gal_0'); // nível do galão
            $table->integer('gal_1'); // nível do galão
            $table->integer('gal_2'); // nível do galão
            $table->integer('gal_3'); // nível do galão
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_readings');
    }
};
