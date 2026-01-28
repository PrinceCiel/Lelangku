<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // $request->validate([
        //     'jumlah' => 'required|numeric|min:100000'
        // ]);
        dd($request);
        // Set Midtrans config
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Buat transaksi
        $params = [
            'transaction_details' => [
                'order_id' => 'DEP-' . time(),
                'gross_amount' => $request->jumlah,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->nama_lengkap,
                'email' => Auth::user()->email,
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return response()->json(['snap_token' => $snapToken]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
