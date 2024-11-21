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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('cpfCnpj', 18)->nullable();
            $table->string('externalId')->nullable();
            $table->json('phoneNumber')->nullable();
            $table->json('email')->nullable();
            $table->string('manager')->nullable();
            $table->text('note')->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('maximumVisitTime')->nullable();
            $table->integer('unitMaximumTime')->nullable();
            $table->json('groupsId')->nullable();
            $table->json('managerTeamsId')->nullable();
            $table->json('managersId')->nullable();
            $table->json('uriAttachments')->nullable();
            $table->integer('segmentId')->nullable();
            $table->boolean('active')->default(true);
            $table->string('adressComplement')->nullable();
            $table->timestamp('dateLastUpdate')->nullable();
            $table->timestamp('creationDate')->nullable();
            $table->json('contacts')->nullable();
            $table->integer('auvoId')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
