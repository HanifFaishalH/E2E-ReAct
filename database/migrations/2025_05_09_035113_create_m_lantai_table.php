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
        Schema::create('m_lantai', function (Blueprint $table) {
            $table->id('lantai_id');
            $table->unsignedBigInteger('gedung_id');
            $table->string('lantai_nama', 50);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('gedung_id')->references('gedung_id')->on('m_gedung')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_lantai');
    }
};
