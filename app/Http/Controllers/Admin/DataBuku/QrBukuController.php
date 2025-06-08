<?php

namespace App\Http\Controllers\Admin\DataBuku;

use ZipArchive;
use App\Models\Buku;
use App\Models\QrBuku;
use App\Models\DdcBuku;
use App\Models\JenisBuku;
use App\Models\DriveGoogle;
use App\Models\KondisiBuku;
use App\Exports\BooksExport;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use App\Helpers\QrCodeHelper;
use App\Imports\DdcBukuImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class QrBukuController extends Controller
{
    public function index()
    {
        return view('admin.data_buku.qr_buku.index')->with('sb', 'QR Code Buku');
    }

   public function getall(Request $request)
    {
        $query = Buku::select('id', 'judul_buku', 'stok_buku', 'cover_buku')
            ->orderBy('judul_buku', "ASC")
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('cover_buku', function (Buku $buku) {
                if ($buku->cover_buku) {
                    return '<img src="' . asset($buku->cover_buku) . '" alt="Cover ' . e($buku->judul_buku) . '" style="max-width: 100px; height: auto;">';
                }
                return 'Tidak ada cover';
            })
            ->addColumn('action', function (Buku $buku) {
                return '
                    <div class="dropdown d-inline dropleft">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                            Action
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/admin/data-buku/qr-buku/detail/' . $buku->id . '">Detail Qr Code</a></li>
                        </ul>
                    </div>
                ';
            })
            ->rawColumns(['cover_buku', 'action'])
            ->make(true);
    }

    public function detail(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        if(!$buku) {
            abort(404, 'Buku tidak ditemukan');
        }
        $qrBuku = QrBuku::where('id_buku', $buku->id)->get();

        return view('admin.data_buku.qr_buku.detail', [
            'buku' => $buku,
            'qrBuku' => $qrBuku,
        ])->with('sb', 'QR Code Buku');
    }

    public function print(Request $request)
    {
        $buku = Buku::findOrFail($request->id);
        if(!$buku) {
            abort(404, 'Buku tidak ditemukan');
        }
        $qrIds = $request->input('qr_ids', []);
        
        $qrBuku = QrBuku::where('id_buku', $buku->id)->whereIn('id', $qrIds)->get();

        return view('admin.data_buku.qr_buku.print', [
            'buku' => $buku,
            'qrBuku' => $qrIds ? $qrBuku : $buku->qrBuku, 
        ]);
    }
}
