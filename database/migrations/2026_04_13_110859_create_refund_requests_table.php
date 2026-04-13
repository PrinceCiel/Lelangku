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
        Schema::create('refund_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users');
            $table->foreignId('id_deposit')->constrained('deposits');
            $table->bigInteger('jumlah');
            $table->enum('status', ['pending', 'diproses', 'selesai', 'gagal'])->default('pending');

            // Info asal transfer (dari deposit & webhook)
            $table->string('payment_type');
            $table->string('masked_account')->nullable();
            $table->string('bank')->nullable();

            // Diisi user — rekening tujuan refund manual
            $table->string('rekening_tujuan')->nullable();
            $table->string('nama_pemilik')->nullable();
            $table->string('bank_tujuan')->nullable();

            // Diisi admin setelah transfer
            $table->string('bukti_transfer')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamp('processed_at')->nullable();

            $table->enum('alasan_manual', ['payment_type_va', 'refund_api_gagal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_requests');
    }
};
