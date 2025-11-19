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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');//->unique();
            $table->string('phone_code');
            $table->foreignId('organization_id')->nullable()
            ->constrained();
            // $table->foreignId('bravo_id')->nullable()
            // ->constrained();
            $table->string('nationality');
            $table->string('national_id');//->unique();
            $table->double('salary')->default(5000);
            $table->enum('scrub_size', ['xs', 's', 'm', 'l', 'xl', 'xxl', 'xxxl'])->nullable();
            //$table->string('national_id_photo');
            $table->date('national_id_expired');
            $table->string('national_id_expired_hj')->nullable();
            $table->date('birthday')->nullable();
            $table->string('birthday_hj')->nullable();
            $table->string('address')->nullable();
            $table->string('email');//->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('national_source')->nullable();

            $table->unique(['phone', 'deleted_at']);
            $table->unique(['national_id', 'deleted_at']);
            $table->unique(['email', 'deleted_at']);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Unique
			// $table->unique(array('phone','organization_id'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};