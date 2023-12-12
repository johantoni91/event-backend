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
            $table->unsignedBigInteger('NIP');
            $table->unsignedBigInteger('events_id');
            $table->unsignedBigInteger('sessions_id');
            $table->foreign('NIP')->references('id')->on('participants');
            $table->foreign('events_id')->references('id')->on('events');
            $table->foreign('sessions_id')->references('id')->on('sessions');
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
