<?php

use App\Models\Status;
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
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_id')
            ->constrained();
            $table->foreignId('period_id')
            ->constrained();
            $table->foreignId('status_id')->default(Status::CLOSED_MEAL)
            ->constrained();
            $table->time('start_time');
            $table->time('end_time');
            $table->date('day_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
