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
        Schema::create('sendings', function (Blueprint $table) {
            $table->id();
            $table->enum('VIA',['SMS','WHATSAPP']);
            $table->string('whatsapp_token')->nullable();
            $table->string('instance_id')->nullable();
            $table->string('phone_app_sid')->nullable();
            $table->string('phone_sender_id')->nullable();
            $table->string('sender_name')->nullable();
            $table->string('receiver_phone');
            $table->longText('message');
            $table->enum('has_sent',[0,1])->default(0);
            $table->unsignedBigInteger('actioner_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sending');
    }
};
