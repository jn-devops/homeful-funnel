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
        Schema::create('social_media_campaigns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('project_code')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->unsignedInteger('author_id');
            $table->string('social_media_code')->nullable();
            $table->string('registration_logo')->nullable();
            $table->string('registration_background')->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('chat_url')->nullable();
            $table->string('sms_feedback')->nullable();
            $table->string('splash_image_url')->nullable();
            $table->string('trip_label')->nullable();
            $table->string('undecided_label')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_campaigns');
    }
};
