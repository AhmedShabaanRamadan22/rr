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
        Schema::create('monitor_order_sectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitor_id')
            ->constrained();
            $table->foreignId('order_sector_id')
            ->constrained();
            // $table->enum('is_active', [0,1]);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitor_order_sectors');
    }
};
