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
        Schema::create('pemenangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lelang');
            $table->unsignedBigInteger('id_user');
            $table->integer('bid');
            $table->integer('urutan')->default(1); // 1 = utama, 2 = cadangan
            $table->enum('status_kandidat', ['aktif', 'menang', 'gugur'])->default('aktif');
            $table->foreign('id_lelang')->references('id')->on('lelangs');
            $table->foreign('id_user')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemenang');
    }
};
