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
        Schema::create('facility_employees', function (Blueprint $table) {
            $table->id();
            $table->string('national_id');
            $table->string('name');
            // $table->string('position');
            $table->foreignId('facility_id')
            ->constrained();
            $table->foreignId('facility_employee_position_id')
            ->constrained();
            
            $table->unique(['national_id', 'facility_id', 'deleted_at']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_employees');
    }
};