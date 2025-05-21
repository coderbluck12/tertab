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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('verification_request_id')->nullable();
            $table->unsignedBigInteger('institution_attended_id')->nullable();
            $table->string('type')->nullable();
            $table->string('path');
            $table->string('name')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();
        });

        // Add foreign key constraints after all tables are created
        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('verification_request_id')->references('id')->on('verification_requests')->onDelete('cascade');
            $table->foreign('institution_attended_id')->references('id')->on('institution_attendeds')->onDelete('cascade');
            $table->foreign('reference_id')->references('id')->on('references')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
