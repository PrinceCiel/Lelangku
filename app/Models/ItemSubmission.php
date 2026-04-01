<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nama_barang',
        'id_kategori',
        'deskripsi',
        'harga_ditawarkan',
        'foto_barang',
        'nomor_whatsapp',
        'nomor_telepon',
        'alamat_lengkap',
        'status',
        'catatan_admin',
        'harga_deal',
        'reviewed_by',
        'reviewed_at',
        'is_purchased',
        'paid_at',
        'converted_item_id',
    ];

    protected $casts = [
        'foto_barang'      => 'array',
        'harga_ditawarkan' => 'decimal:2',
        'harga_deal'       => 'decimal:2',
        'is_purchased'     => 'boolean',
        'reviewed_at'      => 'datetime',
        'paid_at'          => 'datetime',
    ];

    // ─── Status Constants ──────────────────────────────────────────────────────
    const STATUS_PENDING      = 'pending';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_APPROVED     = 'approved';
    const STATUS_REJECTED     = 'rejected';
    const STATUS_PURCHASED    = 'purchased';

    public static function statusList(): array
    {
        return [
            self::STATUS_PENDING      => 'Menunggu Review',
            self::STATUS_UNDER_REVIEW => 'Sedang Ditinjau',
            self::STATUS_APPROVED     => 'Disetujui',
            self::STATUS_REJECTED     => 'Ditolak',
            self::STATUS_PURCHASED    => 'Sudah Dibeli Platform',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusList()[$this->status] ?? $this->status;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING      => 'warning',
            self::STATUS_UNDER_REVIEW => 'info',
            self::STATUS_APPROVED     => 'success',
            self::STATUS_REJECTED     => 'danger',
            self::STATUS_PURCHASED    => 'primary',
            default                   => 'secondary',
        };
    }

    // ─── Relationships ─────────────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function convertedBarang() {
        return $this->belongsTo(Barang::class, 'converted_barang_id');
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', self::STATUS_UNDER_REVIEW);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopePurchased($query)
    {
        return $query->where('status', self::STATUS_PURCHASED);
    }
}
