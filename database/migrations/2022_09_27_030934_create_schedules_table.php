<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->enum('service', [
                'veterinary',
                'aesthetic',
            ]);
            $table->enum('status', [
                'pending',
                'confirmed',
            ])->default('pending');
            $table->unsignedBigInteger('pet_id')->nullable();
            $table->foreign('pet_id')
                ->references('id')
                ->on('pets');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')
                ->references('id')
                ->on('users');
            $table->unsignedBigInteger('vet_id')->nullable();
            $table->foreign('vet_id')
                ->references('id')
                ->on('vets');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};
