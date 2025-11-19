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
        Schema::create('fine_organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fine_bank_id')
            ->constrained();
            $table->foreignId('organization_id')
            ->constrained();
            $table->double('price');
            $table->string('description');
            $table->timestamps();
            $table->softDeletes();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fine_organizations');
    }
};
