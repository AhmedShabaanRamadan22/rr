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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('display', ['ALL', 'WEB', 'APP'])->default('ALL');
            $table->enum('submissions_times', ['SINGLE', 'SINGLE_PER_DAY', 'MULTIPLE'])->default('SINGLE');
            $table->enum('submissions_by', ['USER', 'USERS'])->default('USER');
            $table->integer('submissions_limit')->nullable();
            $table->string('code')->nullable();
            $table->string('description');
            $table->foreignId('organization_id')->nullable()
            ->constrained();
            $table->foreignId('organization_service_id')
            ->constrained();
            $table->foreignId('organization_category_id')
            ->constrained();
            $table->enum('is_visible',[0,1]);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
