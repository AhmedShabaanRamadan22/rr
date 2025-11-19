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
        Schema::create('assists', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->bigInteger('assist_sector_id');
            $table->foreignId('support_id')
            ->constrained();
            $table->unsignedBigInteger('assigner_id');
            $table->foreign('assigner_id')->references('id')->on('users');
            $table->unsignedBigInteger('assistant_id');
            $table->foreign('assistant_id')->references('id')->on('users');
            $table->foreignId('status_id')
            ->constrained();
            // $table->enum('is_submitted',[0,1]);
            // $table->enum('is_canceled',[0,1])->default('0');
            // $table->string('sign')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assists');
    }
};