<?php

//----------------------------------------------------------------------
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DataBuku\KategoriBukuController as KategoriBukuAdmin;
use App\Http\Controllers\Admin\DashboardController as DashboardAdmin;
use App\Http\Controllers\Admin\DataBuku\DdcBukuController as DdcBukuAdmin;
use App\Http\Controllers\Admin\DataBuku\BukuController as BukuAdmin;
use App\Http\Controllers\Admin\DataBuku\QrBukuController as QrBukuAdmin;
use App\Http\Controllers\Admin\DataBuku\KondisiBukuController as KondisiBukuAdmin;
use App\Http\Controllers\Admin\DataBuku\JenisBukuController as JenisBukuAdmin;
use App\Http\Controllers\Admin\ManageMember\KelasController as KelasAdmin;
use App\Http\Controllers\Admin\ManageMember\SiswaController as SiswaAdmin;
use App\Http\Controllers\Admin\ManageMember\StatusController as StatusSiswaAdmin;
use App\Http\Controllers\Admin\ManageMember\KenaikanKelasController as KenaikanKelasAdmin;
use App\Http\Controllers\Admin\Peminjaman\SettingPeminjamanController as SettingPeminjamanAdmin;
use App\Http\Controllers\Admin\Peminjaman\PeminjamanSiswaController as PeminjamanSiswaAdmin;
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

Route::get('/', [AuthController::class, 'index']);
// Login
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout']);
// Report

// Admin
Route::prefix('admin')->middleware('admin')->group(function () {
        // Dashboard
        Route::prefix('/')->group(function () {
            // Dashboard Index
            Route::get('/', [DashboardAdmin::class, 'index']);
        });
        // Manage Data Member/siswa
        Route::prefix('manage-member')->group(function () {
           
            // Kelas
            Route::prefix('kelas')->group(function () {
                // Index
                Route::get('/', [KelasAdmin::class, 'index']);
                // Create
                Route::post('/', [KelasAdmin::class, 'create']);
                // Get All
                Route::get('all', [KelasAdmin::class, 'getall']);
                // Get
                Route::post('get', [KelasAdmin::class, 'get']);
                // Update
                Route::post('update', [KelasAdmin::class, 'update']);
                // Delete
                Route::delete('/', [KelasAdmin::class, 'delete']);
            });
            
             // Siswa
            Route::prefix('siswa')->group(function () {
                // Index
                Route::get('/', [SiswaAdmin::class, 'index'])->name('admin.siswa.index');
                // Get Kelas
                Route::get('kelas', [SiswaAdmin::class, 'getKelas']);
                // Create
                Route::post('/', [SiswaAdmin::class, 'store']);
                // Get All
                Route::get('all', [SiswaAdmin::class, 'getall']);
                // Get
                Route::post('get', [SiswaAdmin::class, 'get']);
                // Update
                Route::put('/', [SiswaAdmin::class, 'update']);
                // Delete
                Route::delete('/', [SiswaAdmin::class, 'destroy']);
                // Import
                Route::post('import', [SiswaAdmin::class, 'import']);
            });

            // Kenaikan Kelas
            Route::prefix('kenaikan-kelas')->group(function () {
                // Index
                Route::get('/', [KenaikanKelasAdmin::class, 'index']);
                // Migrasi Siswa
                Route::post('migrasi-siswa', [KenaikanKelasAdmin::class, 'migrasiSiswa']);
            });
            
            // Status Siswa
            Route::prefix('status-siswa')->group(function () {
                // Index
                Route::get('/', [StatusSiswaAdmin::class, 'index'])->name('admin.status-siswa.index');
                // Update Status
                Route::post('update', [StatusSiswaAdmin::class, 'updateStatus'])->name('admin.status-siswa.update');
            });
        });

        // Manage Data Buku
        Route::prefix('data-buku')->group(function () {
             Route::prefix('/')->group(function () {
                 // Index
                Route::get('/', [BukuAdmin::class, 'index']);
                // Create
                Route::get('/create', [BukuAdmin::class, 'create']);
                // Create
                Route::post('/store', [BukuAdmin::class, 'store']);
                // Get All
                Route::get('all', [BukuAdmin::class, 'getall']);
                // detail
                Route::get('detail/{id}', [BukuAdmin::class, 'detail']);
                // edit
                Route::get('edit/{id}', [BukuAdmin::class, 'edit']);
                // Update 
                Route::post('update/{id}', [BukuAdmin::class, 'update']);
                // Delete
                Route::delete('/', [BukuAdmin::class, 'delete']);
                // Export Excel
                Route::get('/export', [BukuAdmin::class, 'export'])->name('admin.buku.export');
                // Import Excel
                Route::post('import', [BukuAdmin::class, 'import'])->name('admin.buku.import');
            });
            Route::prefix('kategori-buku')->group(function () {
                 // Index
                Route::get('/', [KategoriBukuAdmin::class, 'index']);
                // Create
                Route::post('/', [KategoriBukuAdmin::class, 'create']);
                // Get All
                Route::get('all', [KategoriBukuAdmin::class, 'getall']);
                // Get
                Route::post('get', [KategoriBukuAdmin::class, 'get']);
                // Update
                Route::post('update', [KategoriBukuAdmin::class, 'update']);
                // Delete
                Route::delete('/', [KategoriBukuAdmin::class, 'delete']);
                // Import Excel
                Route::post('import', [KategoriBukuAdmin::class, 'import'])->name('admin.kategori-buku.import');
            });
            Route::prefix('ddc-buku')->group(function () {
                 // Index
                Route::get('/', [DdcBukuAdmin::class, 'index']);
                // Create
                Route::post('/', [DdcBukuAdmin::class, 'create']);
                // Get All
                Route::get('all', [DdcBukuAdmin::class, 'getall']);
                // Get
                Route::post('get', [DdcBukuAdmin::class, 'get']);
                // Update
                Route::post('update', [DdcBukuAdmin::class, 'update']);
                // Delete
                Route::delete('/', [DdcBukuAdmin::class, 'delete']);
                // Import Excel
                Route::post('import', [DdcBukuAdmin::class, 'import'])->name('admin.ddc-buku.import');
            });
            Route::prefix('kondisi-buku')->group(function () {
                 // Index
                Route::get('/', [KondisiBukuAdmin::class, 'index']);
                // Create
                Route::post('/', [KondisiBukuAdmin::class, 'create']);
                // Get All
                Route::get('all', [KondisiBukuAdmin::class, 'getall']);
                // Get
                Route::post('get', [KondisiBukuAdmin::class, 'get']);
                // Update
                Route::post('update', [KondisiBukuAdmin::class, 'update']);
                // Delete
                Route::delete('/', [KondisiBukuAdmin::class, 'delete']);
                // Import Excel
                Route::post('import', [KondisiBukuAdmin::class, 'import'])->name('admin.kondisi-buku.import');
            });
            Route::prefix('jenis-buku')->group(function () {
                 // Index
                Route::get('/', [JenisBukuAdmin::class, 'index']);
                // Create
                Route::post('/', [JenisBukuAdmin::class, 'create']);
                // Get All
                Route::get('all', [JenisBukuAdmin::class, 'getall']);
                // Get
                Route::post('get', [JenisBukuAdmin::class, 'get']);
                // Update
                Route::post('update', [JenisBukuAdmin::class, 'update']);
                // Delete
                Route::delete('/', [JenisBukuAdmin::class, 'delete']);
                // Import Excel
                Route::post('import', [JenisBukuAdmin::class, 'import'])->name('admin.jenis-buku.import');
            });
            Route::prefix('qr-buku')->group(function () {
                 // Index
                Route::get('/', [QrBukuAdmin::class, 'index']);
                // Get All
                Route::get('all', [QrBukuAdmin::class, 'getall']);
                // Detail
                Route::get('detail/{id}', [QrBukuAdmin::class, 'detail']);
                // download selected QR codes
                Route::get('print', [QrBukuAdmin::class, 'print']);
            });
           
        });

        // Manage Peminjaman Buku
        Route::prefix('peminjaman')->group(function () {
             Route::prefix('settings')->group(function () {
                // Index
                Route::get('/', [SettingPeminjamanAdmin::class, 'index']);
                // Create
                Route::post('/', [SettingPeminjamanAdmin::class, 'create']);
                // Get All
                Route::get('all', [SettingPeminjamanAdmin::class, 'getall']);
                // Get
                Route::post('get', [SettingPeminjamanAdmin::class, 'get']);
                // Update
                Route::post('update', [SettingPeminjamanAdmin::class, 'update']);
                // Delete
                Route::delete('/', [SettingPeminjamanAdmin::class, 'delete']);
            });
            Route::prefix('peminjaman-siswa')->group(function () {
                // Index
                Route::get('/', [PeminjamanSiswaAdmin::class, 'index']);
                // Get All
                Route::get('all', [PeminjamanSiswaAdmin::class, 'getall']);
                // create
                Route::get('create', [PeminjamanSiswaAdmin::class, 'create']);
                // store
                Route::post('store', [PeminjamanSiswaAdmin::class, 'store']);
                // Delete
                Route::delete('/', [PeminjamanSiswaAdmin::class, 'delete']);
                // Check Scan Siswa
                Route::post('/check-siswa', [PeminjamanSiswaAdmin::class, 'checkSiswa']);
                // Check Scan Buku
                Route::post('/check-buku', [PeminjamanSiswaAdmin::class, 'checkBuku']);
            });
           
        });
   
        
});


