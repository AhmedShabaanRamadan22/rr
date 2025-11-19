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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->integer('arrangement')->nullable();
            $table->enum('is_visible',[0,1,'default'])->default('default');
            $table->enum('is_required',[0,1,'default'])->default('default');
            $table->unsignedBigInteger('questionable_id');
            $table->string('questionable_type');
            $table->foreignId('question_bank_organization_id')
            ->constrained();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
