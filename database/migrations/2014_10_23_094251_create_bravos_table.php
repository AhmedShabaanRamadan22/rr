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
        Schema::create('bravos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('number');
            $table->string('code');
            //$table->string('given_by');
            $table->unsignedBigInteger('given_id')->nullable();
            $table->foreign('given_id')->references('id')->on('users');
            $table->string('channel');
            $table->foreignId('organization_id')->nullable()
            ->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bravos');
    }
};
