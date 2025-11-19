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
        Schema::create('meal_organization_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_id')
                ->constrained();
            $table->foreignId('organization_stage_id')
                ->default(Status::CLOSED_MEAL_STAGE)
                ->constrained();
            $table->foreignId('status_id')
                ->constrained();
            $table->integer('arrangement')->nullable();
            $table->integer('duration')->nullable();
            $table->foreignId('done_by')->nullable()->references('id')->on('users');
            $table->timestamp('done_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_organization_stages');
    }
};
