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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained();
            $table->unsignedBigInteger('contractable_id');
            $table->string('contractable_type');
            // $table->foreignId('order_id')
            // ->constrained();
            $table->enum('is_approved',[0,1]);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            // $table->foreignId('order_sector_id')
            // ->constrained();
            $table->foreignId('contract_template_id')
            ->constrained();
            $table->date('sign_date');
            $table->timestamps(); 
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};