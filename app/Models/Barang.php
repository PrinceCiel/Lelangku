<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    public $fillable = ['nama','jenis_barang','harga','deskripsi','kondisi','foto','id_kategori','jumlah', 'slug'];

    public function lelang()
    {
        return $this->hasMany(Lelang::class, 'id_barang');
    }

    public function struk()
    {
        return $this->hasMany(Struk::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

}
