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
        Schema::create('t_laporan_barang', function (Blueprint $table) {
            $table->id('laporan_barang_id');
            $table->unsignedBigInteger('barang_id');
            $table->unsignedInteger('jumlah_laporan')->default(0);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('barang_id')->references('barang_id')->on('m_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_laporan_barang');
    }
};
