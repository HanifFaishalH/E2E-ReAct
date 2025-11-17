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
        Schema::create('t_riwayat_perbaikan', function (Blueprint $table) {
            $table->id('riwayat_id');
            $table->unsignedBigInteger('laporan_id');
            $table->unsignedBigInteger('teknisi_id');

            //detail perbaikan
            $table->text('tindakan');
            $table->text('bahan')->nullable();
            $table->decimal('biaya', 12, 2)->nullable();
            
            // Status
            $table->enum('status', ['dikerjakan', 'selesai', 'batal']);
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();           
            $table->timestamps();

            // Foreign keys
            $table->foreign('laporan_id')->references('laporan_id')->on('t_laporan_kerusakan');
            $table->foreign('teknisi_id')->references('user_id')->on('m_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_riwayat_perbaikan');
    }
};
