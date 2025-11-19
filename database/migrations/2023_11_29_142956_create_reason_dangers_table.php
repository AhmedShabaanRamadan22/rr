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
        Schema::create('reason_dangers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('danger_id')
            ->constrained();
            $table->foreignId('reason_id')
            ->constrained();
            $table->foreignId('organization_id')
            ->constrained();
            $table->foreignId('operation_type_id')
            ->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reason_dangers');
    }
};
