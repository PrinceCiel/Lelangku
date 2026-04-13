<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrikeActivity extends Model
{
    protected $fillable = [
        'id_user',
        'id_lelang',
        'id_struk',
        'alasan',
        'strike_ke',
    ];

    protected $table = 'strike_activities';

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function lelang()
    {
        return $this->belongsTo(Lelang::class, 'id_lelang');
    }

    public function struk()
    {
        return $this->belongsTo(Struk::class, 'id_struk');
    }
}
