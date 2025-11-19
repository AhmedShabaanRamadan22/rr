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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');//->unique();
            $table->bigInteger('registration_number');//->unique();
            $table->date('version_date');
            $table->string('version_date_hj');
            $table->date('end_date');
            $table->string('end_date_hj');
            $table->foreignId('registration_source')
            ->constrained('saudi_cities');
            $table->bigInteger('license');//->unique();
            $table->date('license_expired');
            $table->string('license_expired_hj');
            $table->integer('capacity');
            // $table->string('address');
            $table->bigInteger('tax_certificate');
            $table->integer('employee_number');
            $table->integer('chefs_number')->nullable();
            $table->bigInteger('kitchen_space')->nullable();
            $table->foreignId('user_id')
            ->constrained();
            $table->string('street_name');
            $table->foreignId('district_id')->constrained('districts');
            $table->foreignId('city_id')->constrained('saudi_cities');
            $table->integer('building_number');
            $table->integer('postal_code');
            $table->integer('sub_number')->nullable();

            $table->unique(['name', 'deleted_at']);
            $table->unique(['registration_number', 'deleted_at']);
            $table->unique(['license', 'deleted_at']);
            $table->unique(['tax_certificate', 'deleted_at']);
            $table->timestamps();
            $table->softDeletes();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
