<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $table = 'deposits';

    protected $fillable = [
        'id_lelang',
        'id_user',
        'total',
        'status',
        'kode_deposit',
        'snap_token',
        'order_id',
        'tgl_trx',
        'paid_at',
    ];

    protected $casts = [
        'tgl_trx' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function lelang()
    {
        return $this->belongsTo(Lelang::class, 'id_lelang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Helper: cek apakah deposit sudah lunas
    public function sudahBayar(): bool
    {
        return $this->status === 'berhasil' && !is_null($this->paid_at);
    }
}
