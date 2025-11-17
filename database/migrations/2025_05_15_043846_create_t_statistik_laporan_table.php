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
        Schema::create('t_statistik_laporan', function (Blueprint $table) {
            $table->id('statistik_laporan_id');
            $table->date('tanggal_laporan');
            $table->unsignedBigInteger('total_laporan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_statistik_laporan');
    }
};
