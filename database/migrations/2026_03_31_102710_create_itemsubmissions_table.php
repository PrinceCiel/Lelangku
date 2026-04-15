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
        Schema::create('item_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Info Barang
            $table->string('nama_barang');
            $table->text('deskripsi');
            $table->string('id_kategori'); // kategori dari user (wajib)
            $table->decimal('harga_ditawarkan', 15, 2); // harga dari user (wajib)
            $table->json('foto_barang'); // array of paths, max 5

            // Kontak User untuk Admin
            $table->string('nomor_whatsapp');
            $table->string('nomor_telepon');
            $table->text('alamat_lengkap'); // untuk cek fisik

            // Status Flow
            // pending → under_review → approved → purchased → rejected
            $table->enum('status', [
                'pending',
                'under_review',
                'approved',
                'rejected',
                'purchased',
            ])->default('pending');

            // Admin Review Fields
            $table->text('catatan_admin')->nullable();       // alasan reject / catatan review
            $table->decimal('harga_deal', 15, 2)->nullable(); // harga final yg disepakati admin
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            // Purchase / Pembelian oleh Platform
            $table->boolean('is_purchased')->default(false);
            $table->timestamp('paid_at')->nullable();

            // Relasi ke items (setelah di-convert jadi item lelang)
            $table->foreignId('converted_barang_id')->nullable()->constrained('barangs')->nullOnDelete();
            $table->foreign('id_kategori')->references('id')->on('kategoris');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_submissions');
    }
};
