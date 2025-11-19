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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // $table->string('notes')->nullable();
            $table->foreignId('status_id')
                ->constrained();
            $table->foreignId('facility_id')
                ->nullable()->constrained()->nullOnDelete();
            $table->foreignId('organization_service_id')
                ->constrained();
            $table->foreignId('user_id')
                ->constrained();
            $table->enum('pass_interview', [0, 1])->nullable();
            $table->enum('is_sign', [0, 1])->nullable();
            $table->json('country_ids')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
