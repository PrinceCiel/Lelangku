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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lelang');
            $table->unsignedBigInteger('id_user');
            $table->integer('total');
            $table->enum('status', ['belum dibayar', 'pending', 'berhasil', 'gagal']);
            $table->string('kode_deposit')->unique();
            $table->string('snap_token')->nullable();
            $table->string('order_id')->unique()->nullable(); // format: DEP-{kode_deposit}
            $table->dateTime('tgl_trx');
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('id_lelang')->references('id')->on('lelangs');
            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
