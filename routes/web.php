<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Media Management Routes
Route::get('/homepage', function () {
    return view('media.homepage');
})->name('homepage');
// Media Management Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/berita', function () {
    return view('media.berita');
})->name('berita');

Route::get('/jadwal', function () {
    return view('media.jadwal');
})->name('jadwal');

Route::get('/jurusan', function () {
    return view('media.jurusan');
})->name('jurusan');

Route::get('/biaya', function () {
    return view('media.biaya');
})->name('biaya');

Route::get('/pekerjaan-ortu', function () {
    return view('media.pekerjaan-ortu');
})->name('pekerjaan-ortu');

Route::get('/penghasilan-ortu', function () {
    return view('media.penghasilan-ortu');
})->name('penghasilan-ortu');

Route::get('/biodata-ortu', function () {
    return view('media.biodata-ortu');
})->name('biodata-ortu');

// Berkas Management Routes
Route::get('/ketentuan-berkas', function () {
    return view('berkas.ketentuan-berkas');
})->name('ketentuan-berkas');

Route::get('/berkas-peserta', function () {
    return view('berkas.berkas-peserta');
})->name('berkas-peserta');

// User Management Routes
Route::get('/detail-user', function () {
    return view('user.detail-user');
})->name('detail-user');

Route::get('/pesan', function () {
    return view('user.pesan');
})->name('pesan');

// Peserta Management Routes
Route::get('/detail-peserta', function () {
    return view('peserta.detail-peserta');
})->name('detail-peserta');

// Transaksi Management Routes
Route::get('/transaksi', function () {
    return view('transaksi.transaksi-user');
})->name('transaksi');

// Tagihan Management Routes
Route::get('/tagihan', function () {
    return view('tagihan.tagihan-user');
})->name('tagihan');

Route::get('/pengaturan-biaya', function () {
    return view('transaksi.pengaturan-biaya');
})->name('pengaturan-biaya');

// Peringkat routes
Route::get('/peringkat', function () {
    return view('transaksi.peringkat');
})->name('peringkat');
Route::get('/angkatan', function () {
    return view('peserta.angkatan');
})->name('angkatan');

// Logout Route (dummy)
Route::post('/logout', function () {
    // Implementasi logout
    return redirect('/login');
})->name('logout');