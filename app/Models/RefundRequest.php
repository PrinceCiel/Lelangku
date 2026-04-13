<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    protected $fillable = [
        'id_user', 'id_deposit', 'jumlah', 'status',
        'payment_type', 'masked_account', 'bank',
        'rekening_tujuan', 'nama_pemilik', 'bank_tujuan',
        'bukti_transfer', 'catatan_admin', 'processed_at',
        'alasan_manual',
    ];

    public function user() { return $this->belongsTo(User::class, 'id_user'); }
    public function deposit() { return $this->belongsTo(Deposit::class, 'id_deposit'); }
}
