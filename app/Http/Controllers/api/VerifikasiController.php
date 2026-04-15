<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Datadiri;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VerifikasiController extends Controller
{
    public function index()
    {
        if (auth('sanctum')->check()) {
            $user = auth('sanctum')->user();

            if ($user->status == "Terverifikasi") {
                return response()->json([
                    'success' => true,
                    'status' => $user->status,
                    'message' => 'Akun sudah terverifikasi.'
                ], 200);
            }

            if ($user->status == "diajukan") {
                return response()->json([
                    'success' => false,
                    'status' => $user->status,
                    'error_code' => 'SHOW_STATUS_ONLY',
                    'message' => 'Data diri anda sedang dalam proses verifikasi. Mohon tunggu!'
                ], 403);
            }

            return response()->json([
                'success' => false,
                'status' => $user->status,
                'error_code' => 'REDIRECT_TO_FORM',
                'message' => 'Data diri belum lengkap. Silakan lengkapi data diri untuk proses verifikasi.'
            ], 403);

        } else {
            return response()->json([
                'success' => false,
                'error_code' => 'UNAUTHORIZED',
                'message' => 'Anda harus login terlebih dahulu untuk mengakses halaman verifikasi.'
            ], 401);
        }
    }
    public function storeVerifikasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tgl_lahir' => 'required|date',
            'foto'      => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'alamat'    => 'required|string',
            'no_telp'   => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal, cek inputan lo bro.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $user = auth('sanctum')->user();

        DB::beginTransaction();

        try {
            $verif = new Datadiri();
            $verif->id_user = $user->id;
            $verif->no_telp = $request->no_telp;
            $verif->tanggal_lahir = $request->tgl_lahir;
            $verif->alamat = $request->alamat;

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $randomName = Str::random(20) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('dokumen', $randomName, 'public');

                $verif->foto_dokumen = $path;
            }

            $verif->save();

            // Update status user
            $user = User::findOrFail($user->id);
            $user->status = 'diajukan';
            $user->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data diri berhasil diajukan.',
                'status'  => $user->status,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data : ' . $e->getMessage()
            ], 500);
        }
    }
}
