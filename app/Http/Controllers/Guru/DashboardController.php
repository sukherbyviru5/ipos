<?php

namespace App\Http\Controllers\Guru;

use App\Models\Guru;
use App\Models\Artikel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PeminjamanGuru;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = Guru::getall();
       
        $artikelCounts = Artikel::select('status', DB::raw('count(*) as count'))
            ->where('created_by', $guru->nik)
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $artikelSetuju = $artikelCounts['setuju'] ?? 0;
        $artikelTolak = $artikelCounts['tolak'] ?? 0;
        $artikelDraft = $artikelCounts['draft'] ?? 0;

        $peminjamanBelumKembali = PeminjamanGuru::where('nik_guru', $guru->nik)
            ->whereIn('status_peminjaman', ['dipinjam', 'telat'])
            ->count();

        $data = [
            'guru' => $guru,
            'artikel_setuju' => $artikelSetuju,
            'artikel_tolak' => $artikelTolak,
            'artikel_draft' => $artikelDraft,
            'peminjaman_belum_kembali' => $peminjamanBelumKembali,
        ];

        return view('guru.dashboard.index', $data)->with('sb', 'Dashboard');
    }

    public function profil() {
        $data = [
            'guru' => Guru::getall()
        ];

        return view('guru.profil.index', $data);
    }
    


    public function publikasi(Request $request)
    {
        $guru = Guru::getall();
        $query = Artikel::where('created_by', $guru->nik);

        if ($request->has('q') && $request->q !== null) {
            $q = $request->q;
            $query->where('judul', 'like', '%' . $q . '%');
        }

        $artikels = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('guru.publikasi.index', compact('artikels', 'guru'));
    }


    public function publikasiStore(Request $request)
    {
        $guru = Guru::getall();
        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf|max:2048', // 2MB max
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul maksimal 255 karakter.',
            'file.required' => 'File PDF wajib diunggah.',
            'file.mimes' => 'File harus berupa PDF.',
            'file.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $baseSlug = Str::slug($request->judul);
        $count = Artikel::where('slug', 'like', $baseSlug . '%')->count();
        $slug = $count ? $baseSlug . '-' . ($count + 1) : $baseSlug;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $slug . '_' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('artikel'), $filename);
            $fileName = 'artikel/' . $filename;
        } else {
            return redirect()->back()->withErrors(['file' => 'File is required'])->withInput();
        }
        Artikel::create([
            'judul' => $request->judul,
            'slug' => $slug,
            'file' => $fileName,
            'status' => 'draft',
            'created_by' => $guru->nik,
        ]);

        return redirect()->route('guru.publikasi')->with('success', 'Artikel berhasil dibuat!');
    }

    public function publikasiUpdate(Request $request, $id)
    {
        $guru = Guru::getall();
        $artikel = Artikel::where('id', $id)
            ->where('created_by', $guru->nik)
            ->firstOrFail();

        $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul maksimal 255 karakter.',
            'file.mimes' => 'File harus berupa PDF.',
            'file.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $data = [
            'judul' => $request->judul,
            'status' => $artikel->status,
        ];

       if ($request->hasFile('file')) {
            if (file_exists(public_path($artikel->file))) {
                unlink(public_path($artikel->file));
            }
            $file = $request->file('file');
            $filename = $artikel->slug . '_' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('artikel'), $filename);
            $fileName = 'artikel/' . $filename;
            $data['file'] = $fileName;
        }

        $artikel->update($data);

        return redirect()->route('guru.publikasi')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function publikasiDelete($id)
    {
        $guru = Guru::getall();
        $artikel = Artikel::where('id', $id)
            ->where('created_by', $guru->nik)
            ->firstOrFail();

        if (file_exists(public_path('artikel/' . $artikel->file))) {
            unlink(public_path('artikel/' . $artikel->file));
        }
        $artikel->delete();

        return redirect()->route('guru.publikasi')->with('success', 'Artikel berhasil dihapus!');
    }

    public function peminjaman(Request $request)
    {
        $guru = Guru::getall();
        $query = PeminjamanGuru::where('nik_guru', $guru->nik);

         $query = PeminjamanGuru::where('nik_guru', $guru->nik)
            ->with(['qrBuku.buku']);

        if ($request->has('q') && $request->q !== null) {
            $q = $request->q;
            $query->whereHas('qrBuku.buku', function ($bukuQuery) use ($q) {
                $bukuQuery->where('judul_buku', 'like', '%' . $q . '%');
            });
        }

        $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('guru.peminjaman.index', compact('peminjaman', 'guru'));
    }
}