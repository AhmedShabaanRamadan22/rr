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
        Schema::create('submitted_forms', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('monitor_id')->nullable()
            // ->constrained();
            $table->foreignId('order_sector_id')->nullable()
            ->constrained();
            $table->foreignId('user_id')->nullable()
            ->constrained();
            $table->foreignId('form_id')
            ->constrained();
            $table->string('submitted_sections')->default(json_encode([]));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submitted_forms');
    }
};
