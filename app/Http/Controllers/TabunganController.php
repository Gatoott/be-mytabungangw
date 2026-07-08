<?php

namespace App\Http\Controllers;

use App\Models\Histori;
use App\Models\tabungan;
use Illuminate\Http\Request;

class TabunganController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'nama_tabungan' => 'required',
            'target' => 'required',
            'perhari' => 'required',
        ]);

        $user_id = $request->user()->id;

        tabungan::create([
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
        $tabungan = tabungan::where('id', $id)->where('user_id', $user_id)->first();

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
            'nominal' => 'required',
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
            $tabungan->increment('nominal', $request->nominal);
        }
        else {
            $tabungan->decrement('nominal', $request->nominal);
        }

        Histori::create([
            'nominal' => $request->nominal,
            'type' => $request->type
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'transaksi berhasil'
        ]);
    }
}
