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
        Schema::create('track_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained();
            $table->unsignedBigInteger('track_locationable_id');
            $table->string('track_locationable_type');
            $table->string('longitude')->nullable();
            // $table->unsignedBigInteger('operation_type_id');
            // $table->foreign('operation_type_id')->references('id')->on('operation_types')->nullable();
            $table->string('latitude')->nullable();
            $table->string('device')->nullable();
            $table->string('details')->nullable();
            $table->string('action')->nullable();
            $table->json('device_info')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_locations');
    }
};
