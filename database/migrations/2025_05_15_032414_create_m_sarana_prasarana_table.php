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
        Schema::create('m_saranaPrasarana', function (Blueprint $table) {
            $table->id('sarPras_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('level_id')->references('level_id')->on('m_level')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('m_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_saranaPrasarana');
    }
};
