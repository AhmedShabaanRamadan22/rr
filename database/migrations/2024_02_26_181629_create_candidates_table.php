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
        Schema::create( 'candidates', function ( Blueprint $table ) {
            $table->id();
            // $table->uuid('uuid')->unique()->nullable();
            $table->string( 'name' );
            $table->string( 'email' );
            $table->string( 'phone' );
            $table->string( 'phone_code' );
            $table->string( 'qualification' );
            $table->foreignId( 'department_id' )
            ->constrained();
            $table->foreignId( 'status_id' )
            ->constrained();
            $table->longText( 'self_description' );
            $table->string( 'gender' );
            $table->string( 'resident_status' );
            $table->string( 'job_category' );
            $table->string( 'marital_status' );
            $table->integer( 'salary_expectation' );
            $table->string( 'availability_to_start' );
            // $table->string('years_of_experience');
            // $table->string('national_id')->nullable();
            // $table->date('birthdate')->nullable();
            // $table->date('birthdate_hj')->nullable();
            // $table->longText('address')->nullable();
            $table->softDeletes();
            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists( 'candidates' );
    }
};
