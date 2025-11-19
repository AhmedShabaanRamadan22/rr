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
        Schema::create('ibans', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->string('iban');
            $table->foreignId('bank_id')
            ->constrained();
            $table->unsignedBigInteger('ibanable_id');
            $table->string('ibanable_type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibans');
    }
};
