<?php

namespace App\Http\Controllers;

use App\Models\Histori;
use App\Models\Tabungan;
use Illuminate\Http\Request;

class TabunganController extends Controller
{
    public function index(Request $request) {
        $user_id = $request->user()->id;
        if(!$user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'silahkan login dulu'
            ], 403);
        }

        $tabugans = Tabungan::where('user_id', $user_id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $tabugans
        ], 200);
    }

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

    public function update(Request $request, $id) {
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

    public function transaksi(Request $request, $id) {
        $request->validate([
            'nominal' => 'required|numeric',
            'type' => 'required|in:nabung,ambil'
        ]);

        $user_id = $request->user()->id;
        $tabungan = Tabungan::where('id', $id)->where('user_id', $user_id)->first();

        if(!$tabungan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tabungan tidak ditemukan'
            ], 404);
        }

        if($request->type == 'nabung') {
            $tabungan->increment('terkumpul', $request->nominal);
        }
        else {
            $tabungan->decrement('terkumpul', $request->nominal);
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
}
