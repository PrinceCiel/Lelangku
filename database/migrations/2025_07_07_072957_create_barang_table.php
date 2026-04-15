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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('harga');
            $table->text('deskripsi');
            $table->enum('kondisi', ['Baru', 'Rusak', 'Bekas']);
            $table->string('foto');
            $table->unsignedBigInteger('id_kategori');
            $table->integer('jumlah');
            $table->string('slug');
            $table->unsignedBigInteger('source_submission_id')->nullable();
            $table->string('owner_type')->default('platform');
            $table->json('foto_barang')->nullable();
            $table->foreign('id_kategori')->references('id')->on('kategoris');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
