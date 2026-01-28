<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Struk extends Model
{
    public $fillable = ['id_lelang','id_barang', 'id_pemenang', 'total', 'status', 'kode_unik','kode_struk', 'tgl_trx', 'snap_token', 'order_id'];

    protected $casts = [
        'tgl_trx' => 'datetime',
    ];
    public function lelang()
    {
        return $this->belongsTo(Lelang::class, 'id_lelang');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function pemenang()
    {
        return $this->belongsTo(Pemenang::class, 'id_pemenang');
    }
}
