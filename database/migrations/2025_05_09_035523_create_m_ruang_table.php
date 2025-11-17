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
        Schema::create('m_ruang', function (Blueprint $table) {
            $table->id('ruang_id');
            $table->unsignedBigInteger('lantai_id');
            $table->unsignedBigInteger('gedung_id'); // Tambahkan kolom gedung_id
            $table->string('ruang_nama', 50);
            $table->string('ruang_kode', 20)->unique();
            $table->string('ruang_tipe', 20)->comment('Tipe ruang (kelas, lab, kantor, dll)');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('lantai_id')->references('lantai_id')->on('m_lantai')->onDelete('cascade');
            $table->foreign('gedung_id')->references('gedung_id')->on('m_gedung')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_ruang');
    }
};
