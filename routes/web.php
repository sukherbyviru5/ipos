<?php

//----------------------------------------------------------------------
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\LogAktivitasGuru;

#manage member
use App\Models\LogAktivitasSiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

#manage data buku
use App\Http\Controllers\Admin\Laporan\LogSiswaController;
use App\Http\Controllers\Admin\Setting\FotoController as FotoAdmin;
use App\Http\Controllers\Admin\Setting\LinkController as LinkAdmin;
use App\Http\Controllers\Admin\DataBuku\BukuController as BukuAdmin;
use App\Http\Controllers\Admin\DashboardController as DashboardAdmin;
use App\Http\Controllers\Admin\Setting\VideoController as VideoAdmin;
use App\Http\Controllers\Siswa\DashboardController as DashboardSiswa;
use App\Http\Controllers\Guru\DashboardController as DashboardGuru;

#manage peminjaman
use App\Http\Controllers\Admin\Setting\AdminController as AccountAdmin;
use App\Http\Controllers\Admin\Setting\BannerController as BannerAdmin;
use App\Http\Controllers\Admin\DataBuku\QrBukuController as QrBukuAdmin;
use App\Http\Controllers\Admin\Laporan\PeminjamanPengembalianController;
use App\Http\Controllers\Admin\ManageMember\GuruController as GuruAdmin;
use App\Http\Controllers\Admin\DataBuku\DdcBukuController as DdcBukuAdmin;

#manage publikasi
use App\Http\Controllers\Admin\ManageMember\KelasController as KelasAdmin;

#manage setting
use App\Http\Controllers\Admin\ManageMember\SiswaController as SiswaAdmin;
use App\Http\Controllers\Admin\Publikasi\ArtikelController as ArtikelAdmin;
use App\Http\Controllers\Admin\DataBuku\JenisBukuController as JenisBukuAdmin;
use App\Http\Controllers\Admin\Laporan\BukuController as LaporanBukuController;
use App\Http\Controllers\Admin\ManageMember\StatusController as StatusSiswaAdmin;
use App\Http\Controllers\Admin\DataBuku\KondisiBukuController as KondisiBukuAdmin;
use App\Http\Controllers\Admin\DataBuku\KategoriBukuController as KategoriBukuAdmin;

#laporan
use App\Http\Controllers\Admin\Laporan\PengunjungController;
use App\Http\Controllers\Admin\Setting\SettingAplikasiController as SettingAplikasiApps;
use App\Http\Controllers\Admin\ManageMember\KenaikanKelasController as KenaikanKelasAdmin;

use App\Http\Controllers\Admin\Peminjaman\PeminjamanGuruController as PeminjamanGuruAdmin;
use App\Http\Controllers\Admin\Peminjaman\BukuRusakHilangController as BukuRusakHilangAdmin;
use App\Http\Controllers\Admin\Peminjaman\PeminjamanSiswaController as PeminjamanSiswaAdmin;
use App\Http\Controllers\Admin\Laporan\TransaksiKeuanganController as TransaksiKeuanganAdmin;
use App\Http\Controllers\Admin\Peminjaman\PengembalianGuruController as PengembalianGuruAdmin;
use App\Http\Controllers\Admin\Setting\ProfilPerpustakaanController as ProfilPerpustakaanAdmin;
use App\Http\Controllers\Admin\Peminjaman\PengembalianSiswaController as PengembalianSiswaAdmin;
use App\Http\Controllers\Admin\Peminjaman\SettingPeminjamanController as SettingPeminjamanAdmin;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PagesControler;
use App\Http\Middleware\Activitas;

/*
|--------------------------------------------------------------------------
| Web Routes develop by kuli it tecno
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

#Login
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout']);

#pages
Route::controller(GuestController::class)->group(function () {
    #Home
    Route::get('/', 'home')->name('home');
    #profil
    Route::get('/profil', 'profil')->name('profil');
    #foto
    Route::get('/foto', 'foto')->name('foto');

    Route::middleware(Activitas::class)->group(function () {
        #Books
        Route::get('/buku', 'buku')->name('buku.index');
        Route::get('/buku/{singkatan_buku}', 'bukuDetail')->name('buku.show');

        #Categories
        Route::get('/kategori', 'kategori')->name('kategori.index');
        Route::get('/kategori/{id}', 'kategoriDetail')->name('kategori.show');

        #Articles
        Route::get('/artikel', 'artikel')->name('artikel.index');
        Route::get('/artikel/{slug}', 'artikelDetail')->name('artikel.show');
        #Contributors
        Route::get('/kontributor/{nik}', 'kontributor')->name('kontributor.show');
    });
});

#pages
Route::controller(PagesControler::class)->group(function () {
    #pengunjung
    Route::get('/buku-tamu', 'bukuTamu');
    #bebas perpus
    Route::get('/bebas-perpus', 'bebasPerpus');
    #informasi
    Route::get('/informasi', 'informasi');
    #check
    Route::post('/check-member', 'checkMember');
    #check loan
    Route::post('/check-pinjaman', 'checkPinjaman');
    #Save
    Route::post('/save-visit', 'saveVisit');
    #visitor
    Route::post('/get-visitor-count', 'getVisitorCount');
    #cetak
    Route::post('/print-receipt', 'printReceipt');
});

#Admin
Route::prefix('admin')->middleware('admin')->group(function () {
    #Dashboard
    Route::prefix('/')->group(function () {
        #Dashboard Index
        Route::get('/', [DashboardAdmin::class, 'index']);
    });

    #Manage Data Member/siswa
    Route::prefix('manage-member')->group(function () {
        
        #Kelas
        Route::prefix('kelas')->group(function () {
            #Index
            Route::get('/', [KelasAdmin::class, 'index']);
            #Create
            Route::post('/', [KelasAdmin::class, 'create']);
            #Get All
            Route::get('all', [KelasAdmin::class, 'getall']);
            #Get
            Route::post('get', [KelasAdmin::class, 'get']);
            #Update
            Route::post('update', [KelasAdmin::class, 'update']);
            #Delete
            Route::delete('/', [KelasAdmin::class, 'delete']);
        });
        
        #Siswa
        Route::prefix('guru')->group(function () {
            #Index
            Route::get('/', [GuruAdmin::class, 'index'])->name('admin.guru.index');
            #Get Kelas
            Route::get('kelas', [GuruAdmin::class, 'getKelas']);
            #Create
            Route::post('/', [GuruAdmin::class, 'store']);
            #Get All
            Route::get('all', [GuruAdmin::class, 'getall']);
            #Get
            Route::post('get', [GuruAdmin::class, 'get']);
            #Update
            Route::put('/', [GuruAdmin::class, 'update']);
            #Delete
            Route::delete('/', [GuruAdmin::class, 'destroy']);
            #Import
            Route::post('import', [GuruAdmin::class, 'import']);
        });

        #Siswa
        Route::prefix('siswa')->group(function () {
            #Index
            Route::get('/', [SiswaAdmin::class, 'index'])->name('admin.siswa.index');
            #Get Kelas
            Route::get('kelas', [SiswaAdmin::class, 'getKelas']);
            #Create
            Route::post('/', [SiswaAdmin::class, 'store']);
            #Get All
            Route::get('all', [SiswaAdmin::class, 'getall']);
            #Get
            Route::post('get', [SiswaAdmin::class, 'get']);
            #Update
            Route::put('/', [SiswaAdmin::class, 'update']);
            #Delete
            Route::delete('/', [SiswaAdmin::class, 'destroy']);
            #Import
            Route::post('import', [SiswaAdmin::class, 'import']);
        });

        #Kenaikan Kelas
        Route::prefix('kenaikan-kelas')->group(function () {
            #Index
            Route::get('/', [KenaikanKelasAdmin::class, 'index']);
            #Migrasi Siswa
            Route::post('migrasi-siswa', [KenaikanKelasAdmin::class, 'migrasiSiswa']);
        });
        
        #Status Siswa
        Route::prefix('status-siswa')->group(function () {
            #Index
            Route::get('/', [StatusSiswaAdmin::class, 'index'])->name('admin.status-siswa.index');
            #Update Status
            Route::post('update', [StatusSiswaAdmin::class, 'updateStatus'])->name('admin.status-siswa.update');
        });
    });

    #Manage Data Buku
    Route::prefix('data-buku')->group(function () {
            Route::prefix('/')->group(function () {
            #Index
            Route::get('/', [BukuAdmin::class, 'index']);
            #Create
            Route::get('/create', [BukuAdmin::class, 'create']);
            #Create
            Route::post('/store', [BukuAdmin::class, 'store']);
            #Get All
            Route::get('all', [BukuAdmin::class, 'getall']);
            #detail
            Route::get('detail/{id}', [BukuAdmin::class, 'detail']);
            #edit
            Route::get('edit/{id}', [BukuAdmin::class, 'edit']);
            #Update 
            Route::post('update/{id}', [BukuAdmin::class, 'update']);
            #Delete
            Route::delete('/', [BukuAdmin::class, 'delete']);
            #Export Excel
            Route::get('/export', [BukuAdmin::class, 'export'])->name('admin.buku.export');
            #Import Excel
            Route::post('import', [BukuAdmin::class, 'import'])->name('admin.buku.import');
        });
        Route::prefix('kategori-buku')->group(function () {
            #Index
            Route::get('/', [KategoriBukuAdmin::class, 'index']);
            #Create
            Route::post('/', [KategoriBukuAdmin::class, 'create']);
            #Get All
            Route::get('all', [KategoriBukuAdmin::class, 'getall']);
            #Get
            Route::post('get', [KategoriBukuAdmin::class, 'get']);
            #Update
            Route::post('update', [KategoriBukuAdmin::class, 'update']);
            #Delete
            Route::delete('/', [KategoriBukuAdmin::class, 'delete']);
            #Import Excel
            Route::post('import', [KategoriBukuAdmin::class, 'import'])->name('admin.kategori-buku.import');
        });
        Route::prefix('ddc-buku')->group(function () {
            #Index
            Route::get('/', [DdcBukuAdmin::class, 'index']);
            #Create
            Route::post('/', [DdcBukuAdmin::class, 'create']);
            #Get All
            Route::get('all', [DdcBukuAdmin::class, 'getall']);
            #Get
            Route::post('get', [DdcBukuAdmin::class, 'get']);
            #Update
            Route::post('update', [DdcBukuAdmin::class, 'update']);
            #Delete
            Route::delete('/', [DdcBukuAdmin::class, 'delete']);
            #Import Excel
            Route::post('import', [DdcBukuAdmin::class, 'import'])->name('admin.ddc-buku.import');
        });
        Route::prefix('kondisi-buku')->group(function () {
            #Index
            Route::get('/', [KondisiBukuAdmin::class, 'index']);
            #Create
            Route::post('/', [KondisiBukuAdmin::class, 'create']);
            #Get All
            Route::get('all', [KondisiBukuAdmin::class, 'getall']);
            #Get
            Route::post('get', [KondisiBukuAdmin::class, 'get']);
            #Update
            Route::post('update', [KondisiBukuAdmin::class, 'update']);
            #Delete
            Route::delete('/', [KondisiBukuAdmin::class, 'delete']);
            #Import Excel
            Route::post('import', [KondisiBukuAdmin::class, 'import'])->name('admin.kondisi-buku.import');
        });
        Route::prefix('jenis-buku')->group(function () {
            #Index
            Route::get('/', [JenisBukuAdmin::class, 'index']);
            #Create
            Route::post('/', [JenisBukuAdmin::class, 'create']);
            #Get All
            Route::get('all', [JenisBukuAdmin::class, 'getall']);
            #Get
            Route::post('get', [JenisBukuAdmin::class, 'get']);
            #Update
            Route::post('update', [JenisBukuAdmin::class, 'update']);
            #Delete
            Route::delete('/', [JenisBukuAdmin::class, 'delete']);
            #Import Excel
            Route::post('import', [JenisBukuAdmin::class, 'import'])->name('admin.jenis-buku.import');
        });
        Route::prefix('qr-buku')->group(function () {
            #Index
            Route::get('/', [QrBukuAdmin::class, 'index']);
            #Get All
            Route::get('all', [QrBukuAdmin::class, 'getall']);
            #Detail
            Route::get('detail/{id}', [QrBukuAdmin::class, 'detail']);
            #download selected QR codes
            Route::get('print', [QrBukuAdmin::class, 'print']);
        });
        
    });

    #Manage Peminjaman Buku
    Route::prefix('peminjaman')->group(function () {
        Route::prefix('settings')->group(function () {
            Route::get('/', [SettingPeminjamanAdmin::class, 'index'])->name('admin.peminjaman.settings.index');
            Route::post('/store', [SettingPeminjamanAdmin::class, 'store'])->name('admin.peminjaman.settings.store');
            Route::put('/update/{id}', [SettingPeminjamanAdmin::class, 'update'])->name('admin.peminjaman.settings.update');
        });

        #Peminjaman Siswa
        Route::prefix('peminjaman-siswa')->group(function () {
            #Index
            Route::get('/', [PeminjamanSiswaAdmin::class, 'index']);
            #Get All
            Route::get('all', [PeminjamanSiswaAdmin::class, 'getall']);
            #create
            Route::get('create', [PeminjamanSiswaAdmin::class, 'create']);
            #detail
            Route::get('detail/{id}', [PeminjamanSiswaAdmin::class, 'detail']);
            #store
            Route::post('store', [PeminjamanSiswaAdmin::class, 'store']);
            #Delete
            Route::delete('/', [PeminjamanSiswaAdmin::class, 'delete']);
            #Check Scan Siswa
            Route::post('/check-siswa', [PeminjamanSiswaAdmin::class, 'checkSiswa']);
            #Check Scan Buku
            Route::post('/check-buku', [PeminjamanSiswaAdmin::class, 'checkBuku']);
            #result termal
            Route::get('/result', [PeminjamanSiswaAdmin::class, 'result']);
        });

        #Pengembalian Siswa
        Route::prefix('pengembalian-siswa')->group(function () {
            #Index
            Route::get('/', [PengembalianSiswaAdmin::class, 'index']);
            #Get All
            Route::get('all', [PengembalianSiswaAdmin::class, 'getall']);
            #create
            Route::get('detail/{nik}', [PengembalianSiswaAdmin::class, 'detailPeminjamanSiswa']);
            #update
            Route::post('update', [PengembalianSiswaAdmin::class, 'kembalikanBuku']);
            #cek book
            Route::post('check-buku', [PengembalianSiswaAdmin::class, 'checkBuku']);
        });
        
        #rusak atau hilang
        Route::prefix('buku-rusak-hilang')->group(function () {
                #Index
            Route::get('/', [BukuRusakHilangAdmin::class, 'index']);
            #Create
            Route::get('/create', [BukuRusakHilangAdmin::class, 'create']);
            #Search
            Route::get('/search', [BukuRusakHilangAdmin::class, 'search']);
            #store
            Route::post('/', [BukuRusakHilangAdmin::class, 'store']);
            #Get All
            Route::get('all', [BukuRusakHilangAdmin::class, 'getall']);
            #Get
            Route::get('edit/{id}', [BukuRusakHilangAdmin::class, 'edit']);
            #Update
            Route::put('update', [BukuRusakHilangAdmin::class, 'update']);
            #Check Scan
            Route::post('/check', [BukuRusakHilangAdmin::class, 'check']);
            #Delete
            Route::delete('/', [BukuRusakHilangAdmin::class, 'delete']);
        });

        #Peminjaman Guru
        Route::prefix('peminjaman-guru')->group(function () {
            #Index
            Route::get('/', [PeminjamanGuruAdmin::class, 'index']);
            #Get All
            Route::get('all', [PeminjamanGuruAdmin::class, 'getall']);
            #create
            Route::get('create', [PeminjamanGuruAdmin::class, 'create']);
            #detail
            Route::get('detail/{id}', [PeminjamanGuruAdmin::class, 'detail']);
            #store
            Route::post('store', [PeminjamanGuruAdmin::class, 'store']);
            #Delete
            Route::delete('/', [PeminjamanGuruAdmin::class, 'delete']);
            #Check Scan Guru
            Route::post('/check-guru', [PeminjamanGuruAdmin::class, 'checkGuru']);
            #Check Scan Buku
            Route::post('/check-buku', [PeminjamanGuruAdmin::class, 'checkBuku']);
            #result termal
            Route::get('/result', [PeminjamanGuruAdmin::class, 'result']);
        });

        #Pengembalian Guru
        Route::prefix('pengembalian-guru')->group(function () {
            #Index
            Route::get('/', [PengembalianGuruAdmin::class, 'index']);
            #Get All
            Route::get('all', [PengembalianGuruAdmin::class, 'getall']);
            #create
            Route::get('detail/{nik}', [PengembalianGuruAdmin::class, 'detailPeminjamanGuru']);
            #update
            Route::post('update', [PengembalianGuruAdmin::class, 'kembalikanBuku']);
            #cek book
            Route::post('check-buku', [PengembalianGuruAdmin::class, 'checkBuku']);
        });

    });

    #laporan
    Route::prefix('laporan')->group(function () {
       #Keuangan
        Route::prefix('transaksi-keuangan')->group(function () {
            #Index
            Route::get('/', [TransaksiKeuanganAdmin::class, 'index']);
            #Create
            Route::post('/', [TransaksiKeuanganAdmin::class, 'create']);
            #Get All
            Route::get('all', [TransaksiKeuanganAdmin::class, 'getall']);
            #Get
            Route::post('get', [TransaksiKeuanganAdmin::class, 'get']);
            #Update
            Route::post('update', [TransaksiKeuanganAdmin::class, 'update']);
            #Delete
            Route::delete('/', [TransaksiKeuanganAdmin::class, 'delete']);
            #pdf
            Route::get('export/pdf', [TransaksiKeuanganAdmin::class, 'exportPdf']);
            #excel
            Route::get('export/excel', [TransaksiKeuanganAdmin::class, 'exportExcel']);
        });

       #buku
        Route::prefix('buku')->group(function () {
            #Index
            Route::get('/', [LaporanBukuController::class, 'index']);
            #pdf
            Route::get('export/pdf', [LaporanBukuController::class, 'exportPdf']);
            #excel
            Route::get('export/excel', [LaporanBukuController::class, 'exportExcel']);
        });

       #peminjaman pengembalian
        Route::prefix('peminjaman-pengembalian')->group(function () {
            #Index
            Route::get('/', [PeminjamanPengembalianController::class, 'index']);
            #pdf
            Route::get('export/pdf', [PeminjamanPengembalianController::class, 'exportPdf']);
            #excel
            Route::get('export/excel', [PeminjamanPengembalianController::class, 'exportExcel']);
        });
       #log siswa
        Route::prefix('log-siswa')->group(function () {
            #Index
            Route::get('/', [LogSiswaController::class, 'index']);
            #getall
            Route::get('/all', [LogSiswaController::class, 'getall']);
            #pdf
            Route::get('/export/pdf', [LogSiswaController::class, 'exportPdf']);
            #excel
            Route::get('/export/excel', [LogSiswaController::class, 'exportExcel']);
        });
       #buku kunjungn
        Route::prefix('pengunjung')->group(function () {
            #Index
            Route::get('/', [PengunjungController::class, 'index']);
            #cetak
            Route::get('/cetak', [PengunjungController::class, 'cetak'])->name('pengunjung.cetak');;
        });
         
    });
    #publikasi
    Route::prefix('publikasi')->group(function () {
        Route::prefix('artikel')->group(function () {
                #Index
            Route::get('/', [ArtikelAdmin::class, 'index']);
            #Create
            Route::post('/', [ArtikelAdmin::class, 'create']);
            #Get All
            Route::get('all', [ArtikelAdmin::class, 'getall']);
            #Get
            Route::post('get', [ArtikelAdmin::class, 'get']);
            #Update
            Route::post('update', [ArtikelAdmin::class, 'update']);
            #Delete
            Route::delete('/', [ArtikelAdmin::class, 'delete']);
        });
    });

    #Setting
    Route::prefix('setting')->group(function () {
        #Banner
        Route::prefix('banner')->group(function () {
            #Index
            Route::get('/', [BannerAdmin::class, 'index']);
            #Create
            Route::post('/', [BannerAdmin::class, 'create']);
            #Get All
            Route::get('all', [BannerAdmin::class, 'getall']);
            #Get
            Route::post('get', [BannerAdmin::class, 'get']);
            #Update
            Route::post('update', [BannerAdmin::class, 'update']);
            #Delete
            Route::delete('/', [BannerAdmin::class, 'delete']);
        });
        #Video
        Route::prefix('video')->group(function () {
            #Index
            Route::get('/', [VideoAdmin::class, 'index']);
            #Create
            Route::post('/', [VideoAdmin::class, 'create']);
            #Get All
            Route::get('all', [VideoAdmin::class, 'getall']);
            #Get
            Route::post('get', [VideoAdmin::class, 'get']);
            #Update
            Route::post('update', [VideoAdmin::class, 'update']);
            #Delete
            Route::delete('/', [VideoAdmin::class, 'delete']);
        });
        #foto
        Route::prefix('foto')->group(function () {
            #Index
            Route::get('/', [FotoAdmin::class, 'index']);
            #Create
            Route::post('/', [FotoAdmin::class, 'create']);
            #Get All
            Route::get('all', [FotoAdmin::class, 'getall']);
            #Get
            Route::post('get', [FotoAdmin::class, 'get']);
            #Update
            Route::post('update', [FotoAdmin::class, 'update']);
            #Delete
            Route::delete('/', [FotoAdmin::class, 'delete']);
        });
        #Link
        Route::prefix('link')->group(function () {
            #Index
            Route::get('/', [LinkAdmin::class, 'index']);
            #Create
            Route::post('/', [LinkAdmin::class, 'create']);
            #Get All
            Route::get('all', [LinkAdmin::class, 'getall']);
            #Get
            Route::post('get', [LinkAdmin::class, 'get']);
            #Update
            Route::post('update', [LinkAdmin::class, 'update']);
            #Delete
            Route::delete('/', [LinkAdmin::class, 'delete']);
        });
        #Profil Perpustakaan
        Route::prefix('profil_perpustakaan')->group(function () {
            Route::get('/', [ProfilPerpustakaanAdmin::class, 'index'])->name('admin.setting.profil_perpustakaan.index');
            Route::post('/store', [ProfilPerpustakaanAdmin::class, 'store'])->name('admin.setting.profil_perpustakaan.store');
            Route::put('/update/{id}', [ProfilPerpustakaanAdmin::class, 'update'])->name('admin.setting.profil_perpustakaan.update');
        });
        #Setting Aplikasi
        Route::prefix('apps')->group(function () {
            Route::get('/', [SettingAplikasiApps::class, 'index'])->name('admin.setting.apps.index');
            Route::post('/store', [SettingAplikasiApps::class, 'store'])->name('admin.setting.apps.store');
            Route::put('/update/{id}', [SettingAplikasiApps::class, 'update'])->name('admin.setting.apps.update');
        });
        Route::prefix('admin')->group(function () {
                #Index
            Route::get('/', [AccountAdmin::class, 'index']);
            #Create
            Route::post('/', [AccountAdmin::class, 'create']);
            #Get All
            Route::get('all', [AccountAdmin::class, 'getall']);
            #Get
            Route::post('get', [AccountAdmin::class, 'get']);
            #Update
            Route::post('update', [AccountAdmin::class, 'update']);
            #Delete
            Route::delete('/', [AccountAdmin::class, 'delete']);
        });

        
    });
        
});

#Siswa
Route::prefix('siswa')->middleware('siswa')->group(function () {
    #Dashboard
    Route::prefix('/')->group(function () {
        #Dashboard Index
        Route::get('/', [DashboardSiswa::class, 'index']);
        #profil
        Route::get('/profil', [DashboardSiswa::class, 'profil']);
        #profil edit
        Route::get('/profil/edit', [DashboardSiswa::class, 'edit']);
        #profil update
        Route::put('/profil', [DashboardSiswa::class, 'update']);
        #Publikasi
        Route::get('/publikasi', [DashboardSiswa::class, 'publikasi'])->name('siswa.publikasi');
        Route::post('/publikasi', [DashboardSiswa::class, 'publikasiStore'])->name('siswa.publikasi.store');
        Route::put('/publikasi/{id}', [DashboardSiswa::class, 'publikasiUpdate'])->name('siswa.publikasi.update');
        Route::delete('/publikasi/{id}', [DashboardSiswa::class, 'publikasiDelete'])->name('siswa.publikasi.delete');
        #Pinjaman Buku
        Route::get('/peminjaman', [DashboardSiswa::class, 'peminjaman'])->name('siswa.peminjaman');
    });
});

#Guru
Route::prefix('guru')->middleware('guru')->group(function () {
    #Dashboard
    Route::prefix('/')->group(function () {
        #Dashboard Index
        Route::get('/', [DashboardGuru::class, 'index']);
        #profil
        Route::get('/profil', [DashboardGuru::class, 'profil']);
        #Publikasi
        Route::get('/publikasi', [DashboardGuru::class, 'publikasi'])->name('guru.publikasi');
        Route::post('/publikasi', [DashboardGuru::class, 'publikasiStore'])->name('guru.publikasi.store');
        Route::put('/publikasi/{id}', [DashboardGuru::class, 'publikasiUpdate'])->name('guru.publikasi.update');
        Route::delete('/publikasi/{id}', [DashboardGuru::class, 'publikasiDelete'])->name('guru.publikasi.delete');
        #Pinjaman Buku
        Route::get('/peminjaman', [DashboardGuru::class, 'peminjaman'])->name('guru.peminjaman');
    });
});