<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/siswa', [RoleController::class,'siswa'])->middleware('role:user');
Route::get('/admin',  [RoleController::class,'admin'])->middleware('role:admin');
Route::get('/redirect',  [RoleController::class,'index']);
Route::get('logout', [RoleController::class,'logout']);

//ADMIN
Route::get('/dashboard/peserta', [AdminController::class,'peserta'])->middleware('role:admin');
Route::get('/peserta/datatables',  [AdminController::class,'usersByGroupDatatables'])->middleware('role:admin');
Route::get('/dashboard/dashboard_soal',  [AdminController::class,'dashboard_soal'])->middleware('role:admin');
Route::get('/dashboard/paket_soal',  [AdminController::class,'paket_soal'])->middleware('role:admin');
Route::get('/dashboard/sesi_soal',  [AdminController::class,'sesi_soal'])->middleware('role:admin');
Route::get('/dashboard/list_soal/{sesi}/{paket}',  [AdminController::class,'list_soal'])->middleware('role:admin');
Route::get('/dashboard/input_soal/{sesi}/{paket}/{nomor}',  [AdminController::class,'input_soal'])->middleware('role:admin');
Route::get('/dashboard/import_soal/{sesi}/{paket}/{nomor}',  [AdminController::class,'import_soal'])->middleware('role:admin');
Route::get('/dashboard/nilai_siswa/',  [AdminController::class,'nilai_siswa'])->middleware('role:admin');
Route::get('/dashboard/nilai_siswa/detail/{id_siswa}',  [AdminController::class,'nilai_siswa_detail'])->middleware('role:admin');

Route::post('/dashboard/action/buat_kategori_soal', [AjaxController::class, 'kategori_paket'])->middleware('role:admin');
Route::post('/dashboard/action/delete_kategori_soal', [AjaxController::class, 'delete_kategori_paket'])->middleware('role:admin');
Route::post('/dashboard/action/update_kategori_soal', [AjaxController::class, 'update_kategori_paket'])->middleware('role:admin');
Route::post('/dashboard/action/update_paket_soal', [AjaxController::class, 'update_paket'])->middleware('role:admin');
Route::post('/dashboard/action/buat_paket_soal', [AjaxController::class, 'buat_paket'])->middleware('role:admin');
Route::post('/dashboard/action/buat_sesi_soal', [AjaxController::class, 'buat_sesi'])->middleware('role:admin');
Route::post('/dashboard/action/tambah_soal', [AjaxController::class, 'tambah_soal'])->middleware('role:admin');
Route::post('/dashboard/action/ubah_soal', [AjaxController::class, 'ubah_soal'])->middleware('role:admin');
Route::post('/dashboard/action/upload_image', [AjaxController::class, 'upload_image'])->middleware('role:admin');
Route::post('/dashboard/action/import_soal', [AjaxController::class, 'import_soal'])->middleware('role:admin');
Route::get('/dashboard/hapus_soal/{sesi}/{paket}', [AjaxController::class, 'kosongkan'])->middleware('role:admin');

//SISWA
Route::get('/siswa/payment/topup',[PaymentController::class,'topup'])->middleware('role:user');
Route::post('/siswa/payment/generate',[PaymentController::class,'generate'])->middleware('role:user');
Route::post('/siswa/payment/update',[PaymentController::class,'update'])->middleware('role:user');
Route::get('/siswa/payment/cara',[PaymentController::class,'cara'])->middleware('role:user');
Route::post('/siswa/action/daftar_to', [AjaxController::class, 'daftar_TO'])->middleware('role:user');
Route::post('/siswa/analisis', [ProfileController::class, 'analisis'])->middleware('role:user');

//SOAL
Route::get('/siswa/soal/launch/{id_paket}',[SoalController::class,'launch'])->middleware('role:user');
Route::post('/siswa/soal/mengerjakan',[SoalController::class,'mengerjakan'])->middleware('role:user');
Route::get('/siswa/soal/nav-soal/{id_siswa}/{id_sesi}/{nama}',[SoalController::class,'navsoal'])->middleware('role:user');
Route::get('/siswa/soal/soal/{id_sesi}/{nomor}/{nama}',[SoalController::class,'soal'])->middleware('role:user');
Route::post('/siswa/soal/jawab',[SoalController::class,'jawab'])->middleware('role:user');
Route::get('/siswa/soal/finish/{id}/{nomor}/{ujian}',[SoalController::class,'finish'])->middleware('role:user');
Route::post('/siswa/pembahasan',[ProfileController::class,'pembahasan'])->middleware('role:user');
Route::get('/siswa/soal/nav-bahas',[SoalController::class,'navbahas'])->middleware('role:user');
Route::get('/siswa/soal/soal-bahas',[SoalController::class,'soalbahas'])->middleware('role:user');
Route::post('/siswa/soal/bahas-launch',[SoalController::class,'bahaslaunch'])->middleware('role:user');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
