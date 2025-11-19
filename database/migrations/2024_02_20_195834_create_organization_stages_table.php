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
        Schema::create('organization_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_bank_id')
                ->constrained();
            $table->foreignId('organization_id')
                ->constrained();
            $table->integer('duration');
            $table->integer('arrangement');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_stages');
    }
};
