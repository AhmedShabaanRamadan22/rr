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
        Schema::create('attachment_labels', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->integer('arrangement')->nullable();
            $table->string('placeholder_ar');
            $table->string('placeholder_en');
            $table->string('type');
            $table->json('extensions');
            $table->enum('is_required',[0,1]);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachment_labels');
    }
};
