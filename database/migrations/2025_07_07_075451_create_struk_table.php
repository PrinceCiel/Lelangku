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
        Schema::create('struks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lelang');
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_pemenang');
            $table->integer('total');
            $table->enum('status', ['belum dibayar', 'pending', 'berhasil', 'gagal']);
            $table->string('kode_unik')->nullable()->unique();
            $table->dateTime('tgl_trx');
            $table->string('kode_struk')->unique();
            $table->string('order_id')->unique();
            $table->string('snap_token')->nullable();
            $table->foreign('id_lelang')->references('id')->on('lelangs');
            $table->foreign('id_barang')->references('id')->on('barangs');
            $table->foreign('id_pemenang')->references('id')->on('pemenangs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('struk');
    }
};
