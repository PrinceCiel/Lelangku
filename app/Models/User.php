<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_lengkap',
        'foto',
        'email',
        'password',
        'IsAdmin',
        'status',
        'google_id',
        'email_verified_at',
        'is_banned',
        'banned_at',
        'banned_reason',
        'strike_count',
        'last_login_at',
        'is_suspicious',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'banned_at'         => 'datetime',
            'last_login_at'     => 'datetime',
            'password' => 'hashed',
            'is_banned'         => 'boolean',
        ];
    }

    public function pemenang()
    {
        return $this->hasMany(Pemenang::class, 'id_user');
    }

    public function datadiri()
    {
        return $this->hasOne(Datadiri::class, 'id_user');
    }

    public function bid()
    {
        return $this->hasMany(Bid::class, 'id_user');
    }

    public function strikes()
    {
        return $this->hasMany(StrikeActivity::class, 'id_user');
    }
    public function kicks()
    {
        return $this->hasMany(KickActivity::class, 'id_user');
    }
    public function deposit()
    {
        return $this->hasMany(Deposit::class, 'id_user');
    }

}
