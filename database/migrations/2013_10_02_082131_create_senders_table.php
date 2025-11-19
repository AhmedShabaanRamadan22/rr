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
        Schema::create('senders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('whatsapp_instance_id')->nullable();
            $table->string('whatsapp_token')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_app_sid')->nullable();
            $table->string('phone_sender_id')->nullable();
            $table->enum('send_sms',[0,1])->default(0);
            // $table->foreignId('organization_id')
            //     ->constrained()->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('senders');
    }
};
