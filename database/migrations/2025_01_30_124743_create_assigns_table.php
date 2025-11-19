<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('assigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assignee_id')->constrained('users')->onDelete('cascade');
            $table->morphs('assignable'); // This creates `assignable_id` and `assignable_type`

            $table->softDeletes();
            $table->timestamps();

            // Add indexes for faster lookups
            $table->index(['assigner_id']);
            $table->index(['assignee_id']);
            $table->index(['assignable_id', 'assignable_type']); // Composite index for polymorphic queries
        });
    }

    public function down()
    {
        Schema::dropIfExists('assigns');
    }
};

