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
        Schema::create('strike_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_lelang')->nullable();
            $table->unsignedBigInteger('id_struk')->nullable();
            $table->enum('alasan', ['gagal_bayar', 'suspicious_activity', 'manual_admin']);  // nanti bisa ditambah
            $table->integer('strike_ke'); // strike ini yang keberapa
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_lelang')->references('id')->on('lelangs');
            $table->foreign('id_struk')->references('id')->on('struks')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strike_activities');
    }
};
