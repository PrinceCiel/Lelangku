<?php

namespace App\Services;

use App\Models\Bid;
use App\Models\Deposit;
use App\Models\Lelang;
use App\Models\Pemenang;
use App\Models\StrikeActivity;
use App\Models\Struk;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class LelangService
{
    private const STRIKE_LIMIT = 3;

    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$clientKey    = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function handle(): void
    {
        $now = now();
        $this->updateStatusLelang($now);
        $this->generateKandidat($now);
        $this->handleStrukExpired($now);
        $this->handleKandidatGugur();
        $this->handleStandbyNaik();
        $this->generateStrike();
        $this->handleBlacklist();
        $this->cleanupStrukGagal();
        $this->prosesRefundDepositPesertaKalah();
    }

    // =========================================================================
    // 1. Update status lelang — skip draft
    // =========================================================================
    public function updateStatusLelang($now): void
    {
        Lelang::whereNotIn('status', ['draft'])->chunk(50, function ($lelangs) use ($now) {
            foreach ($lelangs as $lelang) {
                if ($now->lt($lelang->jadwal_mulai)) {
                    $status = 'ditutup';
                } elseif ($now->between($lelang->jadwal_mulai, $lelang->jadwal_berakhir)) {
                    $status = 'dibuka';
                } else {
                    $status = 'selesai';
                }

                if ($lelang->status !== $status) {
                    $lelang->update(['status' => $status]);
                }
            }
        });
    }

    // =========================================================================
    // 2. Generate kandidat 1 & 2 saat lelang selesai
    // =========================================================================
    public function generateKandidat($now): void
    {
        $lelangs = Lelang::where('status', 'selesai')
            ->whereDoesntHave('pemenang')
            ->get();

        foreach ($lelangs as $lelang) {
            $bids = $lelang->bid()
                ->orderByDesc('bid')
                ->orderByDesc('created_at')
                ->get();

            if ($bids->isEmpty()) continue;

            $bid1      = $bids->first();
            $kandidat1 = Pemenang::create([
                'id_lelang'       => $lelang->id,
                'id_user'         => $bid1->id_user,
                'bid'             => $bid1->bid,
                'urutan'          => 1,
                'status_kandidat' => 'aktif',
            ]);

            $bid2 = $bids->first(fn($b) => $b->id_user !== $bid1->id_user);
            if ($bid2) {
                Pemenang::create([
                    'id_lelang'       => $lelang->id,
                    'id_user'         => $bid2->id_user,
                    'bid'             => $bid2->bid,
                    'urutan'          => 2,
                    'status_kandidat' => 'standby',
                ]);
            }

            $this->buatStruk($lelang, $kandidat1);

            Log::info('Kandidat generated', [
                'lelang'     => $lelang->kode_lelang,
                'kandidat_1' => $bid1->id_user,
                'kandidat_2' => $bid2?->id_user ?? 'tidak ada',
            ]);
        }
    }

    // =========================================================================
    // 3. Struk expired → gagal + expire di Midtrans
    // =========================================================================
    public function handleStrukExpired($now): void
    {
        Struk::where('status', 'belum dibayar')
            ->where('tgl_trx', '<=', $now->copy()->subHour())
            ->chunk(50, function ($struks) {
                foreach ($struks as $struk) {
                    $this->expireOrder($struk);
                    $struk->update(['status' => 'gagal']);
                    Log::info('Struk belum dibayar → gagal', ['kode' => $struk->kode_struk]);
                }
            });

        Struk::where('status', 'pending')
            ->where('updated_at', '<=', $now->copy()->subHour())
            ->chunk(50, function ($struks) {
                foreach ($struks as $struk) {
                    $this->expireOrder($struk);
                    $struk->update(['status' => 'gagal']);
                    Log::info('Struk pending → gagal', ['kode' => $struk->kode_struk]);
                }
            });
    }

    // =========================================================================
    // 4. Struk gagal → kandidat jadi gugur
    // =========================================================================
    public function handleKandidatGugur(): void
    {
        Struk::where('status', 'gagal')
            ->whereHas('pemenang', fn($q) => $q->where('status_kandidat', 'aktif'))
            ->with('pemenang')
            ->get()
            ->each(function ($struk) {
                if (!$struk->pemenang) return;
                $struk->pemenang->update(['status_kandidat' => 'gugur']);
                Log::info('Kandidat gugur', [
                    'lelang'  => $struk->id_lelang,
                    'urutan'  => $struk->pemenang->urutan,
                    'id_user' => $struk->pemenang->id_user,
                ]);
            });
    }

    // =========================================================================
    // 5. Kandidat standby naik / lelang jadi draft
    // =========================================================================
    public function handleStandbyNaik(): void
    {
        $kandidat2List = Pemenang::where('urutan', 2)
            ->where('status_kandidat', 'standby')
            ->whereHas('lelang', fn($q) => $q->where('status', 'selesai'))
            ->whereHas('lelang.pemenang', fn($q) =>
                $q->where('urutan', 1)->where('status_kandidat', 'gugur')
            )
            ->get();

        $lelangNaikIds = [];

        foreach ($kandidat2List as $kandidat2) {
            $kandidat2->update(['status_kandidat' => 'aktif']);
            $lelang = Lelang::find($kandidat2->id_lelang);
            $this->buatStruk($lelang, $kandidat2);
            $lelangNaikIds[] = $lelang->id;

            Log::info('Kandidat 2 naik', [
                'lelang'  => $lelang->kode_lelang,
                'id_user' => $kandidat2->id_user,
            ]);
        }

        $gugurDua = Lelang::where('status', 'selesai')
            ->whereNotIn('id', $lelangNaikIds)
            ->whereHas('pemenang', fn($q) => $q->where('urutan', 1)->where('status_kandidat', 'gugur'))
            ->whereHas('pemenang', fn($q) => $q->where('urutan', 2)->where('status_kandidat', 'gugur'))
            ->get();

        $gugurSatu = Lelang::where('status', 'selesai')
            ->whereNotIn('id', $lelangNaikIds)
            ->whereHas('pemenang', fn($q) => $q->where('urutan', 1)->where('status_kandidat', 'gugur'))
            ->whereDoesntHave('pemenang', fn($q) => $q->where('urutan', 2))
            ->get();

        foreach ($gugurDua->merge($gugurSatu) as $lelang) {
            $lelang->update(['status' => 'draft']);
            Log::info('Lelang → draft', ['lelang' => $lelang->kode_lelang]);
        }
    }

    // =========================================================================
    // 6. Generate strike untuk kandidat yang gugur
    //    Pakai id_struk sebagai unique key biar tidak double strike
    // =========================================================================
    public function generateStrike(): void
    {
        $kandidatGugur = Pemenang::where('status_kandidat', 'gugur')
            ->whereHas('struk', fn($q) => $q->where('status', 'gagal'))
            ->with(['struk' => fn($q) => $q->where('status', 'gagal'), 'user'])
            ->get();

        foreach ($kandidatGugur as $kandidat) {
            $struk = $kandidat->struk;
            if (!$struk) continue;

            // Cek apakah strike untuk struk ini sudah ada — prevent double strike
            $alreadyStruck = StrikeActivity::where('id_user', $kandidat->id_user)
                ->where('id_struk', $struk->id)
                ->exists();

            if ($alreadyStruck) continue;

            $user     = User::find($kandidat->id_user);
            $strikeKe = $user->strike_count + 1;

            StrikeActivity::create([
                'id_user'   => $kandidat->id_user,
                'id_lelang' => $kandidat->id_lelang,
                'id_struk'  => $struk->id,
                'alasan'    => 'gagal_bayar',
                'strike_ke' => $strikeKe,
            ]);

            $user->increment('strike_count');

            Log::info('Strike generated', [
                'id_user'   => $kandidat->id_user,
                'strike_ke' => $strikeKe,
                'lelang'    => $kandidat->id_lelang,
            ]);
        }
    }

    // =========================================================================
    // 7. Handle blacklist — user yang strike >= STRIKE_LIMIT
    //    Flow:
    //    - Kandidat aktif → gugur + expire struk + naik kandidat 2
    //    - Kandidat standby → cari pengganti dari bid ke-3
    //    - Bid di lelang aktif → hapus
    //    - Blacklist user
    // =========================================================================
    public function handleBlacklist(): void
    {
        $usersKenaBlacklist = User::where('strike_count', '>=', self::STRIKE_LIMIT)
            ->where('is_banned', false)
            ->get();

        foreach ($usersKenaBlacklist as $user) {

            // ── Beresin kandidat aktif ────────────────────────────────────
            $kandidatAktif = Pemenang::where('id_user', $user->id)
                ->where('status_kandidat', 'aktif')
                ->get();

            foreach ($kandidatAktif as $kandidat) {
                $struk = Struk::where('id_pemenang', $kandidat->id)
                    ->whereIn('status', ['belum dibayar', 'pending'])
                    ->first();

                if ($struk) {
                    $this->expireOrder($struk);
                    $struk->update(['status' => 'gagal']);
                }

                $kandidat->update(['status_kandidat' => 'gugur']);

                // Naikan kandidat 2
                $kandidat2 = Pemenang::where('id_lelang', $kandidat->id_lelang)
                    ->where('urutan', 2)
                    ->where('status_kandidat', 'standby')
                    ->first();

                if ($kandidat2) {
                    $kandidat2->update(['status_kandidat' => 'aktif']);
                    $lelang = Lelang::find($kandidat->id_lelang);
                    $this->buatStruk($lelang, $kandidat2);
                    Log::info('Kandidat 2 naik karena K1 di-blacklist', [
                        'lelang'  => $lelang->kode_lelang,
                        'id_user' => $kandidat2->id_user,
                    ]);
                }

                Log::info('Kandidat aktif gugur karena blacklist', [
                    'id_user' => $user->id,
                    'lelang'  => $kandidat->id_lelang,
                ]);
            }

            // ── Beresin kandidat standby ──────────────────────────────────
            $kandidatStandby = Pemenang::where('id_user', $user->id)
                ->where('status_kandidat', 'standby')
                ->get();

            foreach ($kandidatStandby as $kandidat) {
                $lelang    = Lelang::find($kandidat->id_lelang);
                $kandidat1 = Pemenang::where('id_lelang', $kandidat->id_lelang)
                    ->where('urutan', 1)
                    ->first();

                // Cari bid pengganti — beda dari kandidat 1 dan user ini
                $bidPengganti = $lelang->bid()
                    ->orderByDesc('bid')
                    ->orderByDesc('created_at')
                    ->get()
                    ->first(fn($b) =>
                        $b->id_user !== $user->id &&
                        $b->id_user !== ($kandidat1->id_user ?? null)
                    );

                if ($bidPengganti) {
                    $kandidat->update([
                        'id_user'         => $bidPengganti->id_user,
                        'bid'             => $bidPengganti->bid,
                        'status_kandidat' => 'standby',
                    ]);
                    Log::info('Kandidat 2 diganti karena blacklist', [
                        'lelang'    => $lelang->kode_lelang,
                        'user_lama' => $user->id,
                        'user_baru' => $bidPengganti->id_user,
                    ]);
                } else {
                    $kandidat->delete();
                    Log::info('Kandidat 2 dihapus karena blacklist (tidak ada pengganti)', [
                        'lelang'  => $lelang->kode_lelang,
                        'id_user' => $user->id,
                    ]);
                }
            }

            // ── Hapus bid di lelang yang masih dibuka ─────────────────────
            $lelangAktifIds = Lelang::where('status', 'dibuka')->pluck('id');
            Bid::where('id_user', $user->id)
                ->whereIn('id_lelang', $lelangAktifIds)
                ->delete();
            Deposit::where('id_user', $user->id)
                ->whereIn('id_lelang', $lelangAktifIds)
                ->where('status', 'berhasil')
                ->whereNull('refunded_at')
                ->get()
                ->each(fn($deposit) => $this->prosesRefundDeposit($deposit, 'user_diblacklist'));
            // ── Blacklist ─────────────────────────────────────────────────
            $user->update([
                'is_banned'     => true,
                'banned_at'     => now(),
                'banned_reason' => 'Otomatis: Strike ' . self::STRIKE_LIMIT . 'x gagal bayar.',
            ]);

            Log::info('User di-blacklist otomatis', [
                'id_user' => $user->id,
                'email'   => $user->email,
            ]);
        }
    }

    // =========================================================================
    // Helper: Buat struk + snap token
    // =========================================================================
    public function buatStruk(Lelang $lelang, Pemenang $kandidat): void
    {
        $existing = Struk::where('id_pemenang', $kandidat->id)
            ->whereIn('status', ['belum dibayar', 'pending'])
            ->exists();

        if ($existing) return;

        $kodeStruk = 'STRL-' . Str::upper(Str::random(10));
        while (Struk::where('kode_struk', $kodeStruk)->exists()) {
            $kodeStruk = 'STRL-' . Str::upper(Str::random(10));
        }

        $orderId  = 'ORDER-' . time() . '-' . $kandidat->id;
        $bid      = $kandidat->bid;
        $adminfee = $bid * 0.05;
        $total    = $bid + $adminfee;

        $struk = Struk::create([
            'id_lelang'   => $lelang->id,
            'id_barang'   => $lelang->id_barang,
            'id_pemenang' => $kandidat->id,
            'total'       => $total,
            'status'      => 'belum dibayar',
            'kode_unik'   => null,
            'tgl_trx'     => now(),
            'kode_struk'  => $kodeStruk,
            'order_id'    => $orderId,
        ]);

        try {
            $snapToken = $this->generateSnapToken($struk, $kandidat, $lelang, $bid, $adminfee);
            $struk->update(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Gagal generate snap token', ['error' => $e->getMessage()]);
        }

        Log::info('Struk generated kandidat ' . $kandidat->urutan, [
            'kode_struk' => $kodeStruk,
            'id_user'    => $kandidat->id_user,
        ]);
    }

    // =========================================================================
    // Helper: Generate snap token
    // =========================================================================
    protected function generateSnapToken(Struk $struk, Pemenang $kandidat, Lelang $lelang, $bid, $adminfee): string
    {
        $params = [
            'transaction_details' => [
                'order_id'     => $struk->order_id,
                'gross_amount' => (int) ($bid + $adminfee),
            ],
            'customer_details' => [
                'first_name' => $kandidat->user->nama_lengkap ?? 'Customer',
                'email'      => $kandidat->user->email ?? 'customer@example.com',
                'phone'      => $kandidat->user->phone ?? '08123456789',
            ],
            'item_details' => [
                [
                    'id'       => $lelang->id_barang,
                    'price'    => (int) $bid,
                    'quantity' => 1,
                    'name'     => $lelang->barang->nama ?? 'Produk Lelang',
                ],
                [
                    'id'       => 'ADMIN_FEE',
                    'price'    => (int) $adminfee,
                    'quantity' => 1,
                    'name'     => 'Biaya Admin (5%)',
                ],
            ],
            'callbacks' => [
                'finish' => route('midtrans.finish'),
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit'       => 'hours',
                'duration'   => 24,
            ],
        ];

        return Snap::getSnapToken($params);
    }

    // =========================================================================
    // Helper: Expire order di Midtrans
    // =========================================================================
    public function expireOrder(Struk $struk): void
    {
        if (!$struk->order_id || !$struk->snap_token) return;

        try {
            Transaction::expire($struk->order_id);
            $struk->snap_token = null;
            $struk->save();
        } catch (\Exception $e) {
            Log::warning('Gagal expire Midtrans order', [
                'order_id' => $struk->order_id,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    public function cleanupStrukGagal(): void
    {
        // Hapus struk gagal yang kandidatnya sudah gugur
        // dan strike-nya sudah tercatat (ada di strike_activities)
        Struk::where('status', 'gagal')
            ->whereHas('pemenang', fn($q) => $q->where('status_kandidat', 'gugur'))
            ->whereHas('pemenang.user', fn($q) =>
                $q->whereExists(function ($query) {
                    $query->select('id')
                        ->from('strike_activities')
                        ->whereColumn('strike_activities.id_user', 'users.id')
                        ->whereColumn('strike_activities.id_struk', 'struks.id');
                })
            )
            ->delete();

        Log::info('Cleanup struk gagal selesai');
    }

    private const AUTO_REFUND_METHODS = ['gopay', 'shopeepay'];

    /**
     * Entry point — dipanggil scheduler setelah lelang selesai
     * untuk semua peserta yang tidak menang
     */
    public function prosesRefundDepositPesertaKalah(): void
    {
        // Ambil lelang yang sudah selesai & sudah ada pemenang final (berhasil bayar)
        $lelangSelesai = Lelang::where('status', 'selesai')
            ->whereHas('pemenang', fn($q) => $q->where('status_kandidat', 'aktif')
                ->whereHas('struk', fn($s) => $s->where('status', 'berhasil'))
            )
            ->pluck('id');

        // Deposit yang perlu di-refund: ikut lelang yang sudah ada pemenang,
        // berstatus berhasil, belum di-refund
        Deposit::whereIn('id_lelang', $lelangSelesai)
            ->where('status', 'berhasil')
            ->whereNull('refunded_at')
            // Exclude pemenang aktif yang berhasil bayar
            ->whereDoesntHave('user.pemenang', fn($q) =>
                $q->whereIn('id_lelang', $lelangSelesai)
                ->where('status_kandidat', 'aktif')
                ->whereHas('struk', fn($s) => $s->where('status', 'berhasil'))
            )
            // ← TAMBAH INI: Exclude yang gugur karena gagal bayar (deposit hangus)
            ->whereDoesntHave('user.pemenang', fn($q) =>
                $q->whereIn('id_lelang', $lelangSelesai)
                ->where('status_kandidat', 'gugur')
            );
    }

    private function prosesRefundDeposit(Deposit $deposit, string $konteks = 'kalah_lelang'): void
    {
        $isAutoMethod = in_array($deposit->payment_type, self::AUTO_REFUND_METHODS);
        if ($isAutoMethod) {
            $berhasil = $this->cobaRefundMidtrans($deposit);
            if ($berhasil) return;
            // Gagal → fallback manual
            $this->buatRefundManual($deposit, "refund_api_gagal_{$konteks}");
            return;
        }
        // VA → langsung manual
        $this->buatRefundManual($deposit, "payment_type_va_{$konteks}");
    }

    public function cobaRefundMidtrans(Deposit $deposit): bool
    {
        try {
            $response = \Midtrans\Transaction::refund($deposit->order_id, [
                'refund_key' => 'REFUND-' . $deposit->id . '-' . time(),
                'amount'     => (int) $deposit->jumlah,
                'reason'     => 'Deposit dikembalikan — tidak menang lelang',
            ]);
            $statusCode = $response->status_code ?? null;
            if (isset($statusCode) && $statusCode == 200) {
                $deposit->update(['refunded_at' => now(), 'status' => 'refunded']);
                Log::info('Refund otomatis berhasil', ['order_id' => $deposit->order_id]);
                return true;
            }
        } catch (\Exception $e) {
            Log::warning('Refund Midtrans gagal', [
                'order_id' => $deposit->order_id,
                'error'    => $e->getMessage(),
            ]);
        }
        return false;
    }

    public function buatRefundManual(Deposit $deposit, string $alasan): void
    {
        $sudahAda = \App\Models\RefundRequest::where('id_deposit', $deposit->id)->exists();
        if ($sudahAda) return;

        \App\Models\RefundRequest::create([
            'id_user'        => $deposit->id_user,
            'id_deposit'     => $deposit->id,
            'jumlah'         => $deposit->jumlah,
            'status'         => 'pending',
            'payment_type'   => $deposit->payment_type,
            'masked_account' => $deposit->masked_account,
            'bank'           => $deposit->bank,
            'alasan_manual'  => $alasan,
        ]);

        Log::info('Refund manual dibuat', [
            'id_deposit' => $deposit->id,
            'alasan'     => $alasan,
        ]);
    }
}
