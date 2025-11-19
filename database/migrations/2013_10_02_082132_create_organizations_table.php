<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();//->unique()->nullable();
            $table->string('name_ar');//->unique();
            $table->string('name_en');//->unique();
            $table->string('domain');//->unique();
            $table->longText('about_us')->nullable();
            $table->longText('contract')->nullable();
            $table->longText('policies')->nullable();
            $table->string('phone')->nullable();
            $table->enum('has_esnad', [0, 1])->default(0)->nullable();
            $table->enum('close_registeration', [0, 1])->default(0)->nullable();
            $table->enum('close_order', [0, 1])->default(0)->nullable();
            $table->string('primary_color')->nullable();
            $table->foreignId('sender_id')
            ->nullable()->constrained();
            $table->foreignId('city_id')
            ->nullable()->constrained('saudi_cities');
            $table->foreignId('district_id')
            ->nullable()->constrained();
            $table->integer('postal_code')->nullable();
            $table->integer('building_number')->nullable();
            $table->integer('sub_number')->nullable();
            $table->date('release_date')->nullable();
            $table->string('email')->nullable();
            $table->string('release_date_hj')->nullable();
            $table->string('street_name')->nullable();
            $table->bigInteger('registration_number')->nullable();
            $table->foreignId('registration_source')->nullable()
            ->constrained('saudi_cities');
            $table->string('support_phone')->nullable();


            $table->unique(['slug', 'deleted_at']);
            $table->unique(['name_ar', 'deleted_at']);
            $table->unique(['name_en', 'deleted_at']);
            $table->unique(['domain', 'deleted_at']);
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};