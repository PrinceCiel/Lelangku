<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KickActivity extends Model
{
    protected $fillable = ['id_user', 'id_lelang', 'alasan'];

    public function lelang()
    {
        return $this->belongsTo(\App\Models\Lelang::class, 'id_lelang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
