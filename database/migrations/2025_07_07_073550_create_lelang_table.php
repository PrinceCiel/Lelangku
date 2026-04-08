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
        Schema::create('lelangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barang');
            $table->dateTime('jadwal_mulai');
            $table->dateTime('jadwal_berakhir');
            $table->enum('status', ['draft','dibuka', 'ditutup', 'selesai'])->default('ditutup');
            $table->string('kode_lelang')->unique();
            $table->foreign('id_barang')->references('id')->on('barangs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lelang');
    }
};
