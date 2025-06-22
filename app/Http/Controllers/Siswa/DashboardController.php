<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Siswa;
use App\Models\Artikel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PeminjamanSiswa;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = Siswa::getall();
       
        $artikelCounts = Artikel::select('status', DB::raw('count(*) as count'))
            ->where('created_by', $siswa->nik)
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Default counts
        $artikelSetuju = $artikelCounts['setuju'] ?? 0;
        $artikelTolak = $artikelCounts['tolak'] ?? 0;
        $artikelDraft = $artikelCounts['draft'] ?? 0;

        $peminjamanBelumKembali = PeminjamanSiswa::where('nik_siswa', $siswa->nik)
            ->whereIn('status_peminjaman', ['dipinjam', 'telat', 'bermasalah'])
            ->count();

        $data = [
            'siswa' => $siswa,
            'artikel_setuju' => $artikelSetuju,
            'artikel_tolak' => $artikelTolak,
            'artikel_draft' => $artikelDraft,
            'peminjaman_belum_kembali' => $peminjamanBelumKembali,
        ];

        return view('siswa.dashboard.index', $data)->with('sb', 'Dashboard');
    }

    public function profil() {
        $data = [
            'siswa' => Siswa::getall()
        ];

        return view('siswa.profil.index', $data);
    }
    public function edit() {
        $data = [
            'siswa' => Siswa::getall()
        ];

        return view('siswa.profil.edit', $data);
    }
    public function update(Request $request)
    {
        $siswa = Siswa::getall();
       
        $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'no_hp' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15', 
            'alamat' => 'nullable|string|max:255',
        ], [
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpg, jpeg, png, gif, atau webp.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
            'no_hp.regex' => 'Nomor HP tidak valid.',
            'no_hp.min' => 'Nomor HP minimal 10 karakter.',
            'no_hp.max' => 'Nomor HP maksimal 15 karakter.',
            'alamat.max' => 'Alamat maksimal 255 karakter.',
        ]);

        $fotoPath = $siswa->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath && file_exists(public_path($fotoPath))) {
                unlink(public_path($fotoPath));
            }
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('siswa_fotos'), $filename);
            $fotoPath = 'siswa_fotos/' . $filename;
        }

        $siswa->foto = $fotoPath;
        $siswa->no_hp = $request->input('no_hp') ?: $siswa->no_hp;
        $siswa->alamat = $request->input('alamat') ?: $siswa->alamat;
        $siswa->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }


    public function publikasi(Request $request)
    {
        $siswa = Siswa::getall();
        $query = Artikel::where('created_by', $siswa->nik);

        if ($request->has('q') && $request->q !== null) {
            $q = $request->q;
            $query->where('judul', 'like', '%' . $q . '%');
        }

        $artikels = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('siswa.publikasi.index', compact('artikels', 'siswa'));
    }


    public function publikasiStore(Request $request)
    {
        $siswa = Siswa::getall();
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
            'created_by' => $siswa->nik,
        ]);

        return redirect()->route('siswa.publikasi')->with('success', 'Artikel berhasil dibuat!');
    }

    public function publikasiUpdate(Request $request, $id)
    {
        $siswa = Siswa::getall();
        $artikel = Artikel::where('id', $id)
            ->where('created_by', $siswa->nik)
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

        return redirect()->route('siswa.publikasi')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function publikasiDelete($id)
    {
        $siswa = Siswa::getall();
        $artikel = Artikel::where('id', $id)
            ->where('created_by', $siswa->nik)
            ->firstOrFail();

        if (file_exists(public_path('artikel/' . $artikel->file))) {
            unlink(public_path('artikel/' . $artikel->file));
        }
        $artikel->delete();

        return redirect()->route('siswa.publikasi')->with('success', 'Artikel berhasil dihapus!');
    }

    public function peminjaman(Request $request)
    {
        $siswa = Siswa::getall();
        $query = PeminjamanSiswa::where('nik_siswa', $siswa->nik);

         $query = PeminjamanSiswa::where('nik_siswa', $siswa->nik)
            ->with(['qrBuku.buku']);

        if ($request->has('q') && $request->q !== null) {
            $q = $request->q;
            $query->whereHas('qrBuku.buku', function ($bukuQuery) use ($q) {
                $bukuQuery->where('judul_buku', 'like', '%' . $q . '%');
            });
        }

        $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('siswa.peminjaman.index', compact('peminjaman', 'siswa'));
    }
}