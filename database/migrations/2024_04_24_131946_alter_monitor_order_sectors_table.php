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
        Schema::table('monitor_order_sectors', function (Blueprint $table) {
            $table->archivedAt(); // Macro
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitor_order_sectors', function (Blueprint $table) {
            $table->archivedAt(); // Macro
        });
    }
};
