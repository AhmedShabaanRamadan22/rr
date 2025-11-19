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
        Schema::create('sectors', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('sight');
            $table->integer('guest_quantity');
            $table->foreignId('classification_id')
            ->constrained();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            // $table->foreignId('organization_id')
            // ->constrained();
            $table->foreignId('nationality_organization_id')
            ->constrained();
            $table->string('manager_id')->nullable();
            $table->unsignedBigInteger('boss_id')->nullable();
            $table->foreign('boss_id')->references('id')->on('users');
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->foreign('supervisor_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sectors');
    }
};