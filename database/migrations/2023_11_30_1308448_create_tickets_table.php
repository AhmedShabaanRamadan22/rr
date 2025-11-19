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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            // $table->string('type');
            $table->foreignId('reason_danger_id')
            ->constrained();
            $table->foreignId('user_id')
            ->constrained();
            $table->foreignId('status_id')
            ->constrained();
            $table->foreignId('order_sector_id')
            ->constrained();
            // $table->longText('notes')->nullable();
            // $table->string('code')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};