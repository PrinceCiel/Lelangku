<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Datadiri;
use App\Models\User;
use Illuminate\Http\Request;

class DatadiriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('datadiri')->where("status", "diajukan")->get();
        return view('verifikasi.index', compact('users'));
        // dd($users);
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'Terverifikasi'; // atau 'Verified' sesuai keinginanmu
        $user->save();

        toast('User berhasil diverifikasi', 'success');
        return redirect()->back();
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'Belum Verifikasi'; // Balikin biar bisa upload ulang
        $user->save();

        Datadiri::where('id_user', $id)->delete();

        toast('Verifikasi ditolak', 'warning');
        return redirect()->back();
    }
}
