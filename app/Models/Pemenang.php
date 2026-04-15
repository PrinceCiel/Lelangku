<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemenang extends Model
{
    public $fillable = ['id_lelang','id_user', 'bid', 'urutan', 'status_kandidat'];

    public function struk()
    {
        return $this->hasOne(Struk::class, 'id_pemenang');
    }

    public function lelang()
    {
        return $this->belongsTo(Lelang::class, 'id_lelang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    // public function lelangRelation()
    // {
    //     return $this->belongsTo(Lelang::class, 'id_lelang');
    // }

}
