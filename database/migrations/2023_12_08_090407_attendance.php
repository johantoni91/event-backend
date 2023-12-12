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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('participants_id');
            $table->unsignedBigInteger('events_id');
            $table->unsignedBigInteger('sessions_id');
            $table->foreign('participants_id')->references('id')->on('participants')->onDelete('cascade');
            $table->foreign('events_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('sessions_id')->references('id')->on('sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
