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
        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->string('type');
            $table->foreignId('reason_danger_id')
            ->constrained();
            $table->integer('has_enough')->enum(['0','1'])->default('0');
            $table->integer('has_enough_quantity')->nullable();
            $table->foreignId('user_id')
            ->constrained();
            $table->foreignId('status_id')
            ->constrained();
            $table->foreignId('period_id')
            ->constrained();
            $table->foreignId('order_sector_id')
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
        Schema::dropIfExists('supports');
    }
};