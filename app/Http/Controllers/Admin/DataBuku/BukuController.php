<?php

namespace App\Http\Controllers\Admin\DataBuku;

use Exception;
use App\Models\Buku;
use App\Models\QrBuku;
use App\Models\DdcBuku;
use App\Models\JenisBuku;
use App\Models\LembarBuku;
use App\Imports\BukuImport;
use App\Models\DriveGoogle;
use App\Models\KondisiBuku;
use App\Exports\BooksExport;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use App\Helpers\QrCodeHelper;
use App\Imports\DdcBukuImport;
use App\Helpers\PdfToImageHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class BukuController extends Controller
{
    public function index()
    {
        return view('admin.data_buku.buku.index')->with('sb', 'Data Buku');
    }

   public function create()
    {
        $data = [
            'ddcs' => DdcBuku::orderBy('no_klasifikasi', "ASC")->get(),
            'kategoris' => KategoriBuku::orderBy('no_urut', "ASC")->get(),
            'kondisis' => KondisiBuku::orderBy('nama_kondisi', "ASC")->get(),
            'jeniss' => JenisBuku::orderBy('nama_jenis', "ASC")->get(),
        ];
        return view('admin.data_buku.buku.create', $data)->with('sb', 'Data Buku');
    }

    public function getall(Request $request)
    {
        $query = Buku::select('id', 'judul_buku', 'cover_buku')
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
            ->addColumn('action', function (Buku $k) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/admin/data-buku/detail/' . $k->id . '">Detail</a></li>
                        <li><a class="dropdown-item" href="/admin/data-buku/edit/' . $k->id . '">Edit</a></li>
                        <li><a data-id="' . $k->id . '" class="dropdown-item hapus" href="#">Hapus</a></li>
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
        return view('admin.data_buku.buku.detail', [
            'buku' => $buku,
        ])->with('sb', 'Data Buku');
    }

    public function edit(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        if(!$buku) {
            abort(404, 'Buku tidak ditemukan');
        }
        return view('admin.data_buku.buku.edit', [
            'buku' => $buku,
            'ddcs' => DdcBuku::orderBy('no_klasifikasi', "ASC")->get(),
            'kategoris' => KategoriBuku::orderBy('no_urut', "ASC")->get(),
            'kondisis' => KondisiBuku::orderBy('nama_kondisi', "ASC")->get(),
            'jeniss' => JenisBuku::orderBy('nama_jenis', "ASC")->get(),
        ])->with('sb', 'Data Buku');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_ddc' => 'required|exists:ddc_buku,id',
            'id_kategori' => 'required|exists:kategori_buku,id',
            'id_jenis' => 'required|exists:jenis_buku,id',
            'id_kondisi' => 'required|exists:kondisi_buku,id',
            'judul_buku' => 'required|string|max:255',
            'singkatan_buku' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'penulis_buku' => 'nullable|string|max:255',
            'penerbit_buku' => 'nullable|string|max:255',
            'tempat_terbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer',
            'asal_buku' => 'nullable|string|max:255',
            'sinopsis' => 'nullable|string',
            'harga_buku' => 'nullable|numeric|min:0',
            'stok_buku' => 'required|integer|min:0',
            'lokasi_lemari' => 'nullable|string|max:255',
            'lokasi_rak' => 'nullable|string|max:255',
            'cover_buku' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            'ebook_tersedia' => 'boolean',
            'ebook_file' => 'nullable|file|mimes:pdf|max:10000',
        ]);

        $data = $validated;

        $data['created_by'] = auth()->user()->nip_nik_nisn;

        $data['kode_buku'] = Buku::generateKodeBuku(
            $request->id_ddc,
            $request->id_kategori,
            strtolower(str_replace(' ', '-', $request->penerbit_buku ?? auth()->user()->nama)),
            strtoupper(str_replace(' ', '', $request->singkatan_buku ?? $request->judul_buku)),
        );
        $data['ebook_tersedia'] = $request->has('ebook_tersedia') ? true : false;
        $data['view_count'] = 0;

        if ($request->hasFile('cover_buku')) {
            $file = $request->file('cover_buku');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('cover_buku'), $filename);
            $fotoPath = 'cover_buku/' . $filename;
            $data['cover_buku'] = $fotoPath;
        }
        else {
            $data['cover_buku'] = null;
        }

        if ($request->hasFile('ebook_file')) {
            $file = $request->file('ebook_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('ebook_file'), $filename);
            $filePath = 'ebook_file/' . $filename;
            $data['ebook_file'] = $filePath;
        } else {
            $data['ebook_file'] = null;
        }
        

        $buku = Buku::create($data);

        $currentQrs = QrBuku::where('id_buku', $buku->id)->get();

        if ($request->stok_buku > count($currentQrs)) {
            for ($i = count($currentQrs) + 1; $i <= $request->stok_buku; $i++) {
                $code = Buku::generateKodeBuku(
                    $request->id_ddc,
                    $request->id_kategori,
                    strtolower(str_replace(' ', '-', $request->penerbit_buku ?? auth()->user()->nama)),
                    strtoupper(str_replace(' ', '', $request->singkatan_buku ?? $request->judul_buku)),
                    $i
                );
                QrBuku::create([
                    'id_buku' => $buku->id,
                    'no_urut' => $i,
                    'kode' => $code,
                    'path_qr' => null,  
                ]);
            }
        } elseif ($request->stok_buku < count($currentQrs)) {
            $extraQrs = $currentQrs->slice($request->stok_buku);
            foreach ($extraQrs as $qr) {
                $qr->delete();
            }
        }


        return redirect('/admin/data-buku')->with('message', 'Buku berhasil ditambahkan!');
    }

   public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_ddc' => 'required|exists:ddc_buku,id',
            'id_kategori' => 'required|exists:kategori_buku,id',
            'id_jenis' => 'required|exists:jenis_buku,id',
            'id_kondisi' => 'required|exists:kondisi_buku,id',
            'judul_buku' => 'required|string|max:255',
            'singkatan_buku' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'penulis_buku' => 'nullable|string|max:255',
            'penerbit_buku' => 'nullable|string|max:255',
            'tempat_terbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer',
            'asal_buku' => 'nullable|string|max:255',
            'sinopsis' => 'nullable|string',
            'harga_buku' => 'nullable|numeric|min:0',
            'stok_buku' => 'required|integer|min:0',
            'lokasi_lemari' => 'nullable|string|max:255',
            'lokasi_rak' => 'nullable|string|max:255',
            'cover_buku' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            'ebook_tersedia' => 'boolean',
            'ebook_file' => 'nullable|file|mimes:pdf|max:10000',
        ]);

        $buku = Buku::findOrFail($id);
        if(!$buku) {
            abort(404, 'Buku tidak ditemukan');
        }

        $data = $validated;

        $data['created_by'] = auth()->user()->nip_nik_nisn;

        $data['kode_buku'] = Buku::generateKodeBuku(
            $request->id_ddc,
            $request->id_kategori,
            strtolower(str_replace(' ', '-', $request->penerbit_buku ?? auth()->user()->nama)),
            strtoupper(str_replace(' ', '', $request->singkatan_buku ?? $request->judul_buku)),
        );

        $data['ebook_tersedia'] = $request->has('ebook_tersedia') ? true : false;
        $data['view_count'] = 0;

        
        if ($request->hasFile('cover_buku')) {
            if ($buku->cover_buku && file_exists(public_path($buku->cover_buku))) {
                unlink(public_path($buku->cover_buku));
            }

            $file = $request->file('cover_buku');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('cover_buku'), $filename);
            $fotoPath = 'cover_buku/' . $filename;
            $data['cover_buku'] = $fotoPath;
        }

       
        if ($request->hasFile('ebook_file')) {
            try {
                if ($buku->ebook_file && file_exists(public_path($buku->ebook_file))) {
                    unlink(public_path($buku->ebook_file));
                }

                $file = $request->file('ebook_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('ebook_file'), $filename);
                $filePath = 'ebook_file/' . $filename;
                $data['ebook_file'] = $filePath;

                $outputDir = public_path('ebook_images/');
                $imagePaths = PdfToImageHelper::convertPdfToImages(public_path($filePath), $outputDir);

                // Save each image to the lembar_buku table
                foreach ($imagePaths as $index => $imagePath) {
                    LembarBuku::create([
                        'id_buku' => $buku->id,
                        'no_urut' => $index + 1,
                        'image' => str_replace(public_path(), '', $imagePath),
                    ]);
                }

            } catch (Exception $e) {
                Log::error('Failed to process e-book: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to process e-book. Please try again.');
            }
        }


        $currentQrs = QrBuku::where('id_buku', $buku->id)->get();

        if ($request->stok_buku > count($currentQrs)) {
            for ($i = count($currentQrs) + 1; $i <= $request->stok_buku; $i++) {
                $code = Buku::generateKodeBuku(
                    $request->id_ddc,
                    $request->id_kategori,
                    strtolower(str_replace(' ', '-', $request->penerbit_buku ?? auth()->user()->nama)),
                    strtoupper(str_replace(' ', '', $request->singkatan_buku ?? $request->judul_buku)),
                    $i
                );
                QrBuku::create([
                    'id_buku' => $buku->id,
                    'no_urut' => $i,
                    'kode' => $code,
                    'path_qr' => null,  
                ]);
            }
        } elseif ($request->stok_buku < count($currentQrs)) {
            $extraQrs = $currentQrs->slice($request->stok_buku);
            foreach ($extraQrs as $qr) {
                $qr->delete();
            }
        }

        $buku->update($data);


        return redirect('/admin/data-buku')->with('message', 'Buku berhasil diperbarui!');
    }

    public function delete(Request $request)
    {
        Buku::where('id', $request->id)->delete();
        return response()->json([
            'message' => "Data Buku berhasil dihapus"
        ], 200);
    }

   public function export()
    {
        $ddcs = DdcBuku::orderBy('no_klasifikasi', 'ASC')->get();
        $kategoris = KategoriBuku::orderBy('no_urut', 'ASC')->get();
        $kondisis = KondisiBuku::orderBy('nama_kondisi', 'ASC')->get();
        $jeniss = JenisBuku::orderBy('nama_jenis', 'ASC')->get();

        return Excel::download(new BooksExport($ddcs, $kategoris, $kondisis, $jeniss), 'template_buku.xlsx', \Maatwebsite\Excel\Excel::XLSX, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            DB::beginTransaction();
            Excel::import(new BukuImport, $request->file('file'));
            DB::commit();

            return response()->json([
                'message' => 'Data Buku berhasil diimpor',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal : ' . $e->getMessage(),
            ], 500);
        }
    }

    
}
