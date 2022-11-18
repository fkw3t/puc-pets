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
            $table->enum('status', [
                'open',
                'pending',
                'confirmed',
                'canceled'
            ])->default('open');
            $table->unsignedBigInteger('pet_id')->nullable();
            $table->foreign('pet_id')
                ->references('id')
                ->on('pets');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')
                ->references('id')
                ->on('users');
            $table->foreignId('vet_id')
                ->constrained();
            $table->dateTime('date');
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
