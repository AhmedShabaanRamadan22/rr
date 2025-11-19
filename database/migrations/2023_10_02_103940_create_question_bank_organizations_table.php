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
        Schema::create('question_bank_organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_bank_id')
            ->constrained();
            $table->foreignId('organization_id')
            ->constrained();
            $table->enum('is_visible',[0,1])->default(1);
            $table->enum('is_required',[0,1])->default(1);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_bank_organizations');
    }
};
