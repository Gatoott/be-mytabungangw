<?php

namespace App\Http\Controllers;

use App\Models\Histori;
use App\Models\Tabungan;
use Illuminate\Http\Request;

class TabunganController extends Controller
{
    // INDEX
    public function index(Request $request) {
        $user_id = $request->user()->id;
        if(!$user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'silahkan login dulu'
            ], 401);
        }

        $tabugans = Tabungan::where('user_id', $user_id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $tabugans
        ], 200);
    }

    // STORE
    public function store(Request $request) {
        $request->validate([
            'nama_tabungan' => 'required',
            'target' => 'required',
            'perhari' => 'required',
        ]);

        $user_id = $request->user()->id;

        Tabungan::create([
            'user_id' => $user_id,
            'nama_tabungan' => $request->nama_tabungan,
            'target' => $request->target,
            'perhari' => $request->perhari,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'berhasil membuat tabungan'
        ], 201);
    }

    // UPDATE
    public function update(Request $request, $id) {
        $user_id = $request->user()->id;
        if(!$user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'silahkan login dulu'
            ], 401);
        }

        $request->validate([
            'nama_tabungan' => 'required',
            'target' => 'required',
            'perhari' => 'required',
        ]);

        $user_id = $request->user()->id;
        $tabungan = Tabungan::where('id', $id)->where('user_id', $user_id)->first();

        if(!$tabungan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tabungan tidak ditemukan'
            ], 404);
        }

        $tabungan->update([
            'nama_tabungan' => $request->nama_tabungan,
            'target' => $request->target,
            'perhari' => $request->perhari,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'berhasil mengubah tabungan'
        ], 200);
    }

    // TRANSAKSI
    public function transaksi(Request $request, $id) {
        $user = $request->user();
        $user_id = $user->id;

        if(!$user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'silahkan login dulu'
            ], 401);
        }
        
        $request->validate([
            'nominal' => 'required|numeric',
            'type' => 'required|in:nabung,ambil'
        ]);

        $tabungan = Tabungan::where('id', $id)->where('user_id', $user_id)->first();

        if(!$tabungan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tabungan tidak ditemukan'
            ], 404);
        }

        if($request->type == 'ambil') {
            if($request->nominal > $tabungan->terkumpul) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'uang terkumpul kurang'
                ], 422);
            }
            else{
                $tabungan->decrement('terkumpul', $request->nominal);
                $user->decrement('jumlah_terkumpul', $request->nominal);
            }
        }
        else {
            if($tabungan->terkumpul >= $tabungan->target) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'tabungan telah mencapai target'
                ], 422);
            }
            $tabungan->increment('terkumpul', $request->nominal);
            $user->increment('jumlah_terkumpul', $request->nominal);
        }

        Histori::create([
            'user_id' => $user_id,
            'nominal' => $request->nominal,
            'type' => $request->type
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'transaksi berhasil'
        ]);
    }

    // DESTROY
    public function destroy(Request $request, $id) {
        $user = $request->user();
        $user_id = $user->id;

        if(!$user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'silahkan login dulu'
            ], 401);
        }
        
        $tabungan = Tabungan::where('id', $id)->where('user_id', $user_id)->first();

        if(!$tabungan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tabungan tidak ditemukan'
            ], 404);
        }
        
        $user->decrement('jumlah_terkumpul', $tabungan->terkumpul);

        $tabungan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'berhasil menghapus tabungan'
        ]);
    }
}
