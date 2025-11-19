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
            $table->string('previously_work_at_rakaya')->nullable();
            $table->string('has_relative')->nullable();
            $table->string('scrub_size')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('previously_work_at_rakaya')->nullable();
            $table->string('has_relative')->nullable();
            $table->string('scrub_size')->nullable();
        });
    }
};
