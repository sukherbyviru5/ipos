<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Guru;
use App\Models\Link;
use App\Models\Admin;
use App\Models\Siswa;
use App\Models\Banner;
use App\Models\Artikel;
use App\Models\Foto;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\LogAktivitasGuru;
use App\Models\LogAktivitasSiswa;
use App\Models\SettingApp;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{
    public function home(Request $request)
    {
        $top_kontributor = Artikel::whereNotNull('created_by')
            ->groupBy('created_by')
            ->selectRaw('created_by, count(*) as total_articles')
            ->orderByDesc('total_articles')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $siswa = Siswa::where('nik', $item->created_by)->first();
                if ($siswa) {
                    return (object) [
                        'nik' => $siswa->nik,
                        'nama' => $siswa->nama_siswa,
                        'foto' => $siswa->foto ?? 'assets/img/avatar.png',
                        'total_articles' => $item->total_articles,
                    ];
                }

                $guru = Guru::where('nik', $item->created_by)->first();
                if ($guru) {
                    return (object) [
                        'nik' => $guru->nik,
                        'nama' => $guru->nama_guru,
                        'foto' => $guru->foto ?? 'assets/img/avatar.png',
                        'total_articles' => $item->total_articles,
                    ];
                }

                $admin = Admin::where('nip_nik_nisn', $item->created_by)->first();
                if ($admin) {
                    return (object) [
                        'nik' => $admin->nip_nik_nisn,
                        'nama' => $admin->nama,
                        'foto' => $admin->foto ?? 'assets/img/avatar.png',
                        'total_articles' => $item->total_articles,
                    ];
                }

                return (object) [
                    'nik' => $item->created_by,
                    'nama' => 'Unknown',
                    'foto' => 'assets/img/avatar.png',
                    'total_articles' => $item->total_articles,
                ];
            });

        $data = [
            'banner' => Banner::orderBy('id', 'desc')->get(),
            'kategori' => KategoriBuku::orderBy('no_urut', 'asc')->get(),
            'buku_terbaru' => Buku::orderBy('created_at', 'desc')->take(10)->get(),
            'top_view' => Buku::orderBy('view_count', 'desc')->take(6)->get(),
            'artikel' => Artikel::orderBy('created_at', 'desc')->where('status', 'setuju')->take(6)->get(),
            'top_membaca' => LogAktivitasSiswa::whereNotNull('id_buku')->groupBy('nik_siswa')->selectRaw('nik_siswa, count(*) as total_books')->orderByDesc('total_books')->take(5)->with('siswa')->get(),
            'top_kontributor' => $top_kontributor,
            'link' => Link::inRandomOrder()->get(),
        ];

        return view('guest.home', $data);
    }

    public function profil() {
        $profil = SettingApp::first();
        return view('guest.profil', compact('profil'));
    }
    
    public function foto(Request $request) {
        $search = $request->query('q');
        $foto = Foto::where('keterangan', 'like', "%$search%")->get();
        return view('guest.foto', compact('foto'));
    }

    public function buku(Request $request)
    {
        $query = Buku::query();

        if ($request->has('q')) {
            $query->where('judul_buku', 'like', '%' . $request->q . '%');
        }

        if ($request->has('kategori')) {
            LogAktivitasSiswa::add(session('nip_nik_nisn'), "Mencari artikel $request->q pada " . Carbon::now()->translatedFormat('l, d F Y H:i'));
            LogAktivitasGuru::add(session('nip_nik_nisn'), "Mencari artikel $request->q pada " . Carbon::now()->translatedFormat('l, d F Y H:i'));
            if ($request->kategori === 'terbaru') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->kategori === 'top_view') {
                $query->orderBy('view_count', 'desc');
            }
        }

        $buku = $query->paginate(12);
        $kategori = KategoriBuku::orderBy('no_urut', 'asc')->get();
        LogAktivitasSiswa::add(session('nip_nik_nisn'), 'Melihat daftar buku pada ' . Carbon::now()->translatedFormat('l, d F Y H:i'));
        LogAktivitasGuru::add(session('nip_nik_nisn'), 'Melihat daftar buku pada ' . Carbon::now()->translatedFormat('l, d F Y H:i'));
        return view('guest.buku', [
            'buku' => $buku,
            'kategori' => $kategori,
            'query' => $request->q,
            'filter' => $request->kategori,
        ]);
    }

    public function bukuDetail($singkatan_buku)
    {
        $buku = Buku::where('singkatan_buku', $singkatan_buku)->firstOrFail();

        $buku->increment('view_count');
        LogAktivitasSiswa::add(session('nip_nik_nisn'), "Membaca buku $buku->judul_buku pada " . Carbon::now()->translatedFormat('l, d F Y H:i'), $buku->id);
        LogAktivitasGuru::add(session('nip_nik_nisn'), "Membaca buku $buku->judul_buku pada " . Carbon::now()->translatedFormat('l, d F Y H:i'), $buku->id);
        return view('guest.buku_detail', [
            'buku' => $buku,
            'bukus' => Buku::where('id_kategori', $buku->id_kategori)->orWhere('penulis_buku', $buku->penulis_buku)->orWhere('penerbit_buku', $buku->penerbit_buku)->take(5)->inRandomOrder()->get(),
            'kategori' => KategoriBuku::orderBy('no_urut', 'asc')->get(),
        ]);
    }

    public function kategori(Request $request)
    {
        $query = $request->input('q');
        $kategori = KategoriBuku::query()
            ->when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where('nama_kategori', 'like', '%' . $query . '%');
            })
            ->orderBy('no_urut', 'asc')
            ->get();

        LogAktivitasSiswa::add(session('nip_nik_nisn'), 'Melihat daftar kategori pada ' . Carbon::now()->translatedFormat('l, d F Y H:i'));
        LogAktivitasGuru::add(session('nip_nik_nisn'), 'Melihat daftar kategori pada ' . Carbon::now()->translatedFormat('l, d F Y H:i'));

        return view('guest.kategori', [
            'kategori' => $kategori,
        ]);
    }

    public function kategoriDetail($id, Request $request)
    {
        $kategori = KategoriBuku::findOrFail($id);
        $query = $request->input('q');
        $buku = Buku::where('id_kategori', $id)
            ->when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where('judul_buku', 'like', '%' . $query . '%');
            })
            ->paginate(12)
            ->appends(['q' => $query]);
        LogAktivitasSiswa::add(session('nip_nik_nisn'), "Melihat kategori $kategori->nama_kategori pada " . Carbon::now()->translatedFormat('l, d F Y H:i'));
        LogAktivitasGuru::add(session('nip_nik_nisn'), "Melihat kategori $kategori->nama_kategori pada " . Carbon::now()->translatedFormat('l, d F Y H:i'));

        return view('guest.kategori_detail', [
            'kategori' => $kategori,
            'buku' => $buku,
            'all_kategori' => KategoriBuku::orderBy('no_urut', 'asc')->get(),
        ]);
    }

    public function artikel(Request $request)
    {
        $query = Artikel::where('status', 'setuju');
        if ($request->has('q')) {
            LogAktivitasSiswa::add(session('nip_nik_nisn'), "Mencari artikel $request->q pada " . Carbon::now()->translatedFormat('l, d F Y H:i'));
            LogAktivitasGuru::add(session('nip_nik_nisn'), "Mencari artikel $request->q pada " . Carbon::now()->translatedFormat('l, d F Y H:i'));

            if ($request->q === 'terbaru') {
                $query->orderBy('created_at', 'desc');
            } else {
                $query->where('judul', 'like', '%' . $request->q . '%');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $artikel = $query->paginate(10);
        LogAktivitasSiswa::add(session('nip_nik_nisn'), 'Melihat lihat artikel pada ' . Carbon::now()->translatedFormat('l, d F Y H:i'));
        LogAktivitasGuru::add(session('nip_nik_nisn'), 'Melihat lihat artikel pada ' . Carbon::now()->translatedFormat('l, d F Y H:i'));

        return view('guest.artikel', [
            'artikel' => $artikel,
            'query' => $request->q,
        ]);
    }

    public function artikelDetail($slug)
    {
        $artikel = Artikel::where('slug', $slug)->where('status', 'setuju')->firstOrFail();

        $siswa = Siswa::where('nik', $artikel->created_by)->first();
        if ($siswa) {
            $creator = (object) [
                'nik' => $siswa->nik,
                'nama' => $siswa->nama_siswa,
                'foto' => $siswa->foto ?? 'assets/img/avatar.png',
                'type' => 'Siswa',
            ];
        } else {
            $guru = Guru::where('nik', $artikel->created_by)->first();
            if ($guru) {
                $creator = (object) [
                    'nik' => $guru->nik,
                    'nama' => $guru->nama_guru,
                    'foto' => $guru->foto ?? 'assets/img/avatar.png',
                    'type' => 'Guru',
                ];
            } else {
                $admin = Admin::where('nip_nik_nisn', $artikel->created_by)->first();
                if ($admin) {
                    $creator = (object) [
                        'nik' => $admin->nip_nik_nisn,
                        'nama' => $admin->nama,
                        'foto' => $admin->foto ?? 'assets/img/avatar.png',
                        'type' => 'Admin',
                    ];
                } else {
                    $creator = (object) [
                        'nik' => $artikel->created_by,
                        'nama' => 'Unknown',
                        'foto' => 'assets/img/avatar.png',
                        'type' => 'Unknown',
                    ];
                }
            }
        }
        LogAktivitasSiswa::add(session('nip_nik_nisn'), "Membaca artikel dengan judul $artikel->judul pada " . Carbon::now()->translatedFormat('l, d F Y H:i'));
        LogAktivitasGuru::add(session('nip_nik_nisn'), "Membaca artikel dengan judul $artikel->judul pada " . Carbon::now()->translatedFormat('l, d F Y H:i'));
        return view('guest.artikel_detail', [
            'artikel' => $artikel,
            'artikels' => Artikel::inRandomOrder()->where('status', 'setuju')->take(5)->get(),
            'creator' => $creator,
        ]);
    }

    public function kontributor($nik)
    {
        $siswa = Siswa::where('nik', $nik)->first();
        if ($siswa) {
            $kontributor = (object) [
                'nik' => $siswa->nik,
                'nama' => $siswa->nama_siswa,
                'foto' => $siswa->foto ?? 'assets/img/avatar.png',
                'type' => 'Siswa',
            ];
            $artikel = Artikel::where('created_by', $nik)->where('status', 'setuju')->paginate(10);
        } else {
            $guru = Guru::where('nik', $nik)->first();
            if ($guru) {
                $kontributor = (object) [
                    'nik' => $guru->nik,
                    'nama' => $guru->nama_guru,
                    'foto' => $guru->foto ?? 'assets/img/avatar.png',
                    'type' => 'Guru',
                ];
                $artikel = Artikel::where('created_by', $nik)->where('status', 'setuju')->paginate(10);
            } else {
                $admin = Admin::where('nip_nik_nisn', $nik)->first();
                if ($admin) {
                    $kontributor = (object) [
                        'nik' => $admin->nip_nik_nisn,
                        'nama' => $admin->nama,
                        'foto' => $admin->foto ?? 'assets/img/avatar.png',
                        'type' => 'Admin',
                    ];
                    $artikel = Artikel::where('created_by', $nik)->where('status', 'setuju')->paginate(10);
                } else {
                    abort(404, 'Kontributor tidak ditemukan');
                }
            }
        }
        LogAktivitasSiswa::add(session('nip_nik_nisn'), "Melihat artikel yang ditulis oleh $kontributor->nama pada " . Carbon::now()->translatedFormat('l, d F Y H:i'));
        LogAktivitasGuru::add(session('nip_nik_nisn'), "Melihat artikel yang ditulis oleh $kontributor->nama pada " . Carbon::now()->translatedFormat('l, d F Y H:i'));

        return view('guest.kontributor', [
            'kontributor' => $kontributor,
            'artikel' => $artikel,
        ]);
    }
}
