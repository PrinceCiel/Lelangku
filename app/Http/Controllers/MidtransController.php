<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Struk;
use Midtrans\Notification;
use Midtrans\Config;

class MidtransController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Handle redirect dari Midtrans setelah pembayaran
     */
    public function handleRedirect(Request $request)
    {
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $transactionStatus = $request->transaction_status;
        
        $struk = Struk::where('order_id', $orderId)->first();
        
        if (!$struk) {
            return redirect()->route('home.user') // UBAH DI SINI
                ->with('error', 'Transaksi tidak ditemukan');
        }

        // Update status di database sesuai status transaksi
        if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
            $struk->status = 'berhasil';
            $message = 'Pembayaran berhasil!';
        } elseif ($transactionStatus === 'pending') {
            $struk->status = 'pending';
            $message = 'Pembayaran sedang diproses';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $struk->status = 'gagal';
            $message = 'Pembayaran gagal atau dibatalkan';
        } else {
            $struk->status = $transactionStatus;
            $message = 'Status pembayaran: ' . $transactionStatus;
        }
        
        $struk->save();

        return redirect()->route('frontend.struk.detail', ['kodestruk' => $struk->kode_struk])
            ->with('success', $message);
    }

    /**
     * Handle notification dari Midtrans (webhook)
     */
    public function notificationHandler(Request $request)
    {
        try {
            $notif = new Notification();
            
            $orderId = $notif->order_id;
            $status = $notif->transaction_status;
            $fraud = $notif->fraud_status ?? 'accept';

            $struk = Struk::where('order_id', $orderId)->first();

            if (!$struk) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Update status berdasarkan notification
            if ($status == 'capture') {
                if ($fraud == 'accept') {
                    $struk->status = 'berhasil';
                } else {
                    $struk->status = 'pending';
                }
            } elseif ($status == 'settlement') {
                $struk->status = 'berhasil';
            } elseif ($status == 'pending') {
                $struk->status = 'pending';
            } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
                $struk->status = 'gagal';
            }

            $struk->save();

            return response()->json(['status' => 'ok']);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}