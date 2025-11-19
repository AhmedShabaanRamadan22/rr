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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->longText('value');
            $table->foreignId('user_id')
            ->constrained();
            // $table->foreignId('order_id')
            // ->constrained();
            $table->foreignId('question_id')
            ->constrained();
            $table->unsignedBigInteger('answerable_id');
            $table->string('answerable_type');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
