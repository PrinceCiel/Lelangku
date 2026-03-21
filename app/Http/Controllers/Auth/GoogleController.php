<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $existingUser = User::where('email', $googleUser->getEmail())->first();

            if ($existingUser) {
                // Email ada tapi daftar manual (ga punya google_id)
                if (!$existingUser->google_id) {
                    return redirect()->route('login', ['toast' => 'google_email_exists']);
                }

                // Email ada dan sudah pernah login Google → login langsung
                Auth::login($existingUser, true);
                return redirect('/');
            }
            // Download foto dari Google ke storage lokal
            $fotoPath = null;
            if ($googleUser->getAvatar()) {
                $fotoContent = Http::get($googleUser->getAvatar())->body();
                $randomName = Str::random(20) . '.jpg';
                $fotoPath = 'profil/' . $randomName; // ✅ samain folder sama login biasa
                Storage::disk('public')->put($fotoPath, $fotoContent);
            }
            $user = User::updateOrCreate(
                ['google_id' => $googleUser->getId()],
                [
                    'nama_lengkap' => $googleUser->getName(), // ✅ sesuai kolom kamu
                    'email'        => $googleUser->getEmail(),
                    'foto'         => $fotoPath,
                    'google_id'    => $googleUser->getId(),
                    'password'     => null,
                    'email_verified_at' => now(), // ✅ bypass verifikasi email
                ]
            );

            Auth::login($user, true);
            return redirect('/'); // sesuaikan route dashboard kamu

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Login Google gagal: ' . $e->getMessage());
        }
    }
}
