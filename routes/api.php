<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Peringkat routes
Route::get('/admin/peringkat', function (Request $request) {
    $jurusan_id = $request->jurusan_id ? intval($request->jurusan_id) : null;
    
    // Return empty result if no jurusan_id is provided
    if (!$jurusan_id) {
        return response()->json([
            'meta' => [
                'code' => 400,
                'message' => 'Silahkan pilih jurusan terlebih dahulu'
            ],
            'data' => []
        ]);
    }
    
    // Dummy data for testing
    $data = [
        'current_page' => 1,
        'last_page' => 1,
        'total' => 3,
        'data' => [
            [
                'nama_peserta' => 'Peserta 1',
                'score' => 95,
                'status' => 'Lulus'
            ],
            [
                'nama_peserta' => 'Peserta 2',
                'score' => 85,
                'status' => 'Lulus'
            ],
            [
                'nama_peserta' => 'Peserta 3',
                'score' => 75,
                'status' => 'Lulus'
            ]
        ]
    ];
    
    return response()->json([
        'meta' => [
            'code' => 200,
            'message' => 'Data peringkat berhasil diambil'
        ],
        'data' => $data
    ]);
});

// Jurusan routes
Route::get('/admin/jurusan', function () {
    // Dummy data for testing
    $data = [
        [
            'id' => 1,
            'jurusan' => 'IPA',
            'jenjang_sekolah' => 'SMA'
        ],
        [
            'id' => 2,
            'jurusan' => 'IPS',
            'jenjang_sekolah' => 'SMA'
        ],
        [
            'id' => 3,
            'jurusan' => 'Keagamaan',
            'jenjang_sekolah' => 'SD'
        ]
    ];
    
    return response()->json([
        'meta' => [
            'code' => 200,
            'message' => 'Daftar jurusan berhasil diambil'
        ],
        'data' => $data
    ]);
});
