<?php

namespace App\Http\Controllers;

use App\Models\Histori;
use Illuminate\Http\Request;

class HistoriController extends Controller
{
    public function index(Request $request) {
        $user_id = $request->user()->id;
        if(!$user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'silahkan login dulu'
            ], 403);
        }

        $histori = Histori::where('user_id', $user_id)->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $histori
        ], 200);
    }
}
