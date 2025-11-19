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
        Schema::table('candidates', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->nullable();
            $table->string('national_id')->nullable();
            $table->longText('nationality')->nullable();
            $table->date('birthdate')->nullable();
            $table->date('birthdate_hj')->nullable();
            $table->longText('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->nullable();
            $table->string('national_id')->nullable();
            $table->string('nationality')->nullable();
            $table->date('birthdate')->nullable();
            $table->date('birthdate_hj')->nullable();
            $table->longText('address')->nullable();
        });
    }
};
