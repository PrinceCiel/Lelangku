@extends('layouts.public')

@section('content')

{{-- Midtrans Snap Script --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

{{-- ================= PAYMENT PAGE - PURE BLACK DARK MODE ================= --}}
<section class="min-h-screen bg-gradient-to-br from-white via-gray-50 to-gray-100 dark:from-black dark:via-gray-950 dark:to-black py-8 sm:py-12 md:py-16 lg:py-20 px-4 sm:px-6 lg:px-8">
    
    {{-- Premium Background Elements --}}
    <div class="absolute inset-0 opacity-30 dark:opacity-20">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-gray-200 dark:bg-gray-800/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-gray-300 dark:bg-gray-700/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s"></div>
    </div>

    {{-- Refined Dot Pattern --}}
    <div class="absolute inset-0 opacity-[0.02] dark:opacity-[0.03]">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="payment-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1" fill="currentColor" class="text-gray-400"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#payment-grid)"/>
        </svg>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto w-full">
        
        {{-- Progress Steps --}}
        <div class="mb-8 sm:mb-10 lg:mb-12">
            <div class="flex items-center justify-center gap-2 sm:gap-3 md:gap-4 mb-4 sm:mb-6">
                {{-- Step 1 - Completed --}}
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-full bg-emerald-500 text-white border-2 border-emerald-400 shadow-lg shadow-emerald-500/50">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-400 hidden sm:block">Paket Dipilih</span>
                </div>
                
                <div class="w-8 sm:w-12 md:w-16 h-0.5 bg-emerald-500"></div>
                
                {{-- Step 2 - Active - PURE BLACK --}}
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-full bg-gradient-to-br from-gray-800 to-gray-900 dark:from-gray-800 dark:to-gray-900 text-white border-2 border-gray-700 dark:border-gray-700 shadow-xl animate-pulse">
                        <span class="font-black text-sm sm:text-base">2</span>
                    </div>
                    <span class="text-xs sm:text-sm font-bold text-gray-900 dark:text-white hidden sm:block">Pembayaran</span>
                </div>
                
                <div class="w-8 sm:w-12 md:w-16 h-0.5 bg-gray-300 dark:bg-gray-800"></div>
                
                {{-- Step 3 - Pending --}}
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-full bg-gray-200 dark:bg-gray-900 text-gray-400 dark:text-gray-600 border-2 border-gray-300 dark:border-gray-800">
                        <span class="font-black text-sm sm:text-base">3</span>
                    </div>
                    <span class="text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-600 hidden sm:block">Selesai</span>
                </div>
            </div>
        </div>

        {{-- Main Card - PURE BLACK --}}
        <div class="bg-white/90 dark:bg-black/90 backdrop-blur-xl rounded-2xl sm:rounded-3xl border-2 border-gray-200 dark:border-gray-800 shadow-2xl shadow-gray-300/50 dark:shadow-black/50 overflow-hidden">
            
            {{-- Header Section - PURE BLACK --}}
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 dark:from-black dark:to-gray-950 p-6 sm:p-8 md:p-10 text-center relative overflow-hidden">
                {{-- Animated Background Pattern --}}
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 50%, white 1px, transparent 1px); background-size: 30px 30px;"></div>
                </div>
                
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 sm:w-18 sm:h-18 md:w-20 md:h-20 bg-white/10 backdrop-blur-sm rounded-xl sm:rounded-2xl border-2 border-white/20 mb-4 sm:mb-5 md:mb-6 shadow-2xl">
                        <svg class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-black text-white mb-2 sm:mb-3 tracking-tight">
                        Selesaikan Pembayaran
                    </h1>
                    <p class="text-sm sm:text-base text-white/80 font-medium">
                        Proses pembayaran aman dengan Midtrans
                    </p>
                </div>
            </div>

            {{-- Content Section --}}
            <div class="p-6 sm:p-8 md:p-10">
                
                {{-- Order Summary Card - PURE BLACK --}}
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-black dark:to-gray-950 rounded-xl sm:rounded-2xl p-5 sm:p-6 md:p-8 mb-6 sm:mb-8 border-2 border-gray-200 dark:border-gray-800 shadow-lg">
                    <h2 class="text-lg sm:text-xl font-black text-gray-900 dark:text-white mb-4 sm:mb-6 tracking-tight flex items-center gap-2 sm:gap-3">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Ringkasan Pesanan
                    </h2>
                    
                    <div class="space-y-4">
                        {{-- Plan Details --}}
                        <div class="flex flex-col sm:flex-row items-start justify-between pb-4 border-b-2 border-gray-300 dark:border-gray-800 gap-4 sm:gap-0">
                            <div class="flex-1">
                                <p class="text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-500 uppercase tracking-wider mb-1">
                                    Paket Berlangganan
                                </p>
                                <p class="text-lg sm:text-xl font-black text-gray-900 dark:text-white">
                                    Premium Plan
                                </p>
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium mt-1">
                                    Pembayaran per bulan
                                </p>
                            </div>
                            <div class="text-left sm:text-right w-full sm:w-auto">
                                <p class="text-xl sm:text-2xl font-black bg-gradient-to-br from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                                    Rp 99.000
                                </p>
                            </div>
                        </div>

                        {{-- Features Included --}}
                        <div class="pt-2">
                            <p class="text-xs sm:text-sm font-black text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-3">
                                Termasuk:
                            </p>
                            <div class="grid grid-cols-1 gap-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 font-semibold">Download tanpa watermark</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 font-semibold">Resolusi 4K & RAW</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 font-semibold">Download unlimited</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 font-semibold">Lisensi komersial</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Information Alert - GRAY INSTEAD OF BLUE --}}
                <div class="bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl sm:rounded-2xl p-4 sm:p-5 md:p-6 mb-6 sm:mb-8 shadow-lg">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm sm:text-base font-black text-gray-900 dark:text-white mb-1 sm:mb-2">
                                Pembayaran Aman & Terpercaya
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-400 font-medium leading-relaxed">
                                Pembayaran Anda diproses melalui Midtrans dengan enkripsi SSL 256-bit. Kami tidak menyimpan informasi kartu kredit Anda.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Payment Button --}}
                <div class="space-y-3 sm:space-y-4">
                    <button 
                        id="pay-button"
                        class="w-full flex items-center justify-center gap-2 sm:gap-3 md:gap-4 px-6 sm:px-8 py-4 sm:py-5 md:py-6 rounded-xl sm:rounded-2xl
                               bg-gradient-to-r from-gray-800 to-gray-900 dark:from-gray-800 dark:to-gray-900
                               text-white
                               font-black text-sm sm:text-base uppercase tracking-wide
                               hover:from-gray-900 hover:to-black dark:hover:from-gray-700 dark:hover:to-gray-800
                               hover:shadow-2xl hover:shadow-gray-900/30 dark:hover:shadow-black/50
                               hover:scale-[1.02]
                               active:scale-95
                               transition-all duration-300 
                               group
                               disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span id="button-text" class="text-sm sm:text-base">Lanjutkan ke Pembayaran</span>
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>

                    <a href="{{ route('user.dashboard') }}" 
                       class="block w-full text-center px-6 sm:px-8 py-3 sm:py-4 rounded-xl
                              bg-gray-100 dark:bg-gray-900
                              border-2 border-gray-200 dark:border-gray-800
                              text-gray-700 dark:text-gray-400
                              font-bold text-sm sm:text-base uppercase tracking-wide
                              hover:bg-gray-200 dark:hover:bg-gray-800
                              hover:border-gray-300 dark:hover:border-gray-700
                              transition-all duration-300">
                        Batalkan
                    </a>
                </div>

                {{-- Payment Methods Info --}}
                <div class="mt-8 sm:mt-10 pt-6 sm:pt-8 border-t-2 border-gray-200 dark:border-gray-800">
                    <p class="text-center text-xs sm:text-sm font-bold text-gray-600 dark:text-gray-500 uppercase tracking-wider mb-4 sm:mb-6">
                        Metode Pembayaran yang Tersedia
                    </p>
                    <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center justify-center gap-3 sm:gap-4 md:gap-6">
                        {{-- Payment Method Icons --}}
                        <div class="flex flex-col sm:flex-row items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 sm:py-2 bg-white dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-800 rounded-lg sm:rounded-xl">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-gray-700 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm0 2v2h16V6H4zm0 4v8h16v-8H4z"/>
                            </svg>
                            <span class="text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 text-center">Kartu Kredit</span>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 sm:py-2 bg-white dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-800 rounded-lg sm:rounded-xl">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-gray-700 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 14V6c0-1.1-.9-2-2-2H3c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zm-9-1c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm13-6v11c0 1.1-.9 2-2 2H4v-2h17V7h2z"/>
                            </svg>
                            <span class="text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 text-center">Transfer Bank</span>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 sm:py-2 bg-white dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-800 rounded-lg sm:rounded-xl">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-gray-700 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                            </svg>
                            <span class="text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 text-center">E-Wallet</span>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 sm:py-2 bg-white dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-800 rounded-lg sm:rounded-xl">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-gray-700 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            <span class="text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 text-center">QRIS</span>
                        </div>
                    </div>
                </div>

                {{-- Security Badges --}}
                <div class="mt-6 sm:mt-8 pt-6 sm:pt-8 border-t-2 border-gray-200 dark:border-gray-800">
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-6 md:gap-8 text-gray-500 dark:text-gray-600 text-[10px] sm:text-xs font-semibold uppercase tracking-wider">
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            SSL Secure
                        </div>
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Midtrans Verified
                        </div>
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            PCI DSS Compliant
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{-- Help Section --}}
        <div class="mt-6 sm:mt-8 text-center px-4">
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-500 font-medium">
                Butuh bantuan? 
                <a href="#" class="text-gray-900 dark:text-white font-bold hover:underline">Hubungi Support</a>
            </p>
        </div>

    </div>
</section>

{{-- Loading Overlay - PURE BLACK --}}
<div id="loading-overlay" class="hidden fixed inset-0 bg-gray-900/80 dark:bg-black/90 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-black rounded-xl sm:rounded-2xl p-6 sm:p-8 text-center max-w-sm sm:max-w-md w-full mx-4 border-2 border-gray-200 dark:border-gray-800 shadow-2xl">
        <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-gray-800 to-gray-900 dark:from-gray-800 dark:to-gray-900 rounded-full mb-3 sm:mb-4 animate-pulse">
            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </div>
        <h3 class="text-base sm:text-lg font-black text-gray-900 dark:text-white mb-2">Memproses Pembayaran...</h3>
        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Mohon tunggu sebentar</p>
    </div>
</div>

{{-- Midtrans Payment Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const payButton = document.getElementById('pay-button');
    const buttonText = document.getElementById('button-text');
    const loadingOverlay = document.getElementById('loading-overlay');
    
    payButton.addEventListener('click', function () {
        payButton.disabled = true;
        buttonText.textContent = 'Memproses...';
        loadingOverlay.classList.remove('hidden');
        
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                showSuccessAnimation();
                setTimeout(() => window.location.href = '/user/dashboard?payment=success', 2000);
            },
            onPending: function(result) {
                showPendingAnimation();
                setTimeout(() => window.location.href = '/user/dashboard?payment=pending', 2000);
            },
            onError: function(result) {
                loadingOverlay.classList.add('hidden');
                payButton.disabled = false;
                buttonText.textContent = 'Lanjutkan ke Pembayaran';
                showErrorAlert();
            },
            onClose: function() {
                loadingOverlay.classList.add('hidden');
                payButton.disabled = false;
                buttonText.textContent = 'Lanjutkan ke Pembayaran';
            }
        });
    });
    
    function showSuccessAnimation() {
        loadingOverlay.innerHTML = `
            <div class="bg-white dark:bg-black rounded-xl sm:rounded-2xl p-6 sm:p-8 text-center max-w-sm sm:max-w-md w-full mx-4 border-2 border-emerald-500 shadow-2xl animate-scale-in">
                <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-emerald-500 rounded-full mb-3 sm:mb-4 animate-bounce">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-black text-emerald-600 mb-2">Pembayaran Berhasil!</h3>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Mengalihkan ke dashboard...</p>
            </div>
        `;
    }
    
    function showPendingAnimation() {
        loadingOverlay.innerHTML = `
            <div class="bg-white dark:bg-black rounded-xl sm:rounded-2xl p-6 sm:p-8 text-center max-w-sm sm:max-w-md w-full mx-4 border-2 border-amber-500 shadow-2xl animate-scale-in">
                <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-amber-500 rounded-full mb-3 sm:mb-4 animate-pulse">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-black text-amber-600 mb-2">Pembayaran Tertunda</h3>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Menunggu konfirmasi...</p>
            </div>
        `;
    }
    
    function showErrorAlert() {
        const alert = document.createElement('div');
        alert.className = 'fixed top-4 sm:top-6 right-4 sm:right-6 z-50 bg-red-50 dark:bg-red-900/20 border-2 border-red-500 rounded-xl p-4 sm:p-6 shadow-2xl animate-slide-in-right max-w-xs sm:max-w-md w-full';
        alert.innerHTML = `
            <div class="flex items-start gap-3 sm:gap-4">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm sm:text-base font-black text-red-900 dark:text-red-100 mb-1">Pembayaran Gagal</h4>
                    <p class="text-xs sm:text-sm text-red-700 dark:text-red-300 font-medium">Silakan coba lagi.</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-red-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 5000);
    }
});
</script>

<style>
@keyframes scale-in { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
@keyframes slide-in-right { from { opacity: 0; transform: translateX(100%); } to { opacity: 1; transform: translateX(0); } }
.animate-scale-in { animation: scale-in 0.3s ease-out; }
.animate-slide-in-right { animation: slide-in-right 0.3s ease-out; }
</style>

@endsection