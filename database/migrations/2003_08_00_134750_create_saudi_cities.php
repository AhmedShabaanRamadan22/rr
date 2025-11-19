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
        Schema::create('saudi_cities', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en');
            // $table->integer('region');
            // $table->string('name');
            // $table->double('lat');
            // $table->double('lng');
            // $table->string('country');
            // $table->string('iso2');
            // $table->string('admin_name');
            // $table->string('capital')->nullable();
            // $table->double('population');
            // $table->double('population_proper');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saudi_cities');
    }
};
