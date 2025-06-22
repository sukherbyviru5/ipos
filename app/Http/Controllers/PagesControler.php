<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Models\PeminjamanGuru;
use Illuminate\Support\Carbon;
use App\Models\PeminjamanSiswa;
use App\Models\LogAktivitasGuru;
use App\Models\AbsensiPengunjung;
use App\Models\LogAktivitasSiswa;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PagesControler extends Controller
{
    public function getVisitorCount()
    {
        $currentDate = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $visitorCount = AbsensiPengunjung::whereDate('created_at', $currentDate)->count();
        return response()->json(['count' => $visitorCount]);
    }

    public function bukuTamu(Request $request)
    {
        $currentDate = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $currentTime = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i');
        $visitors = AbsensiPengunjung::whereDate('created_at', $currentDate)->get();
        $loggedInUser = auth()->user();
        $gurus = Guru::all();
        $kelas = Kelas::all();

        $data = [
            'visitors' => $visitors,
            'currentDate' => $currentDate,
            'currentTime' => $currentTime,
            'loggedInUser' => $loggedInUser,
            'gurus' => $gurus,
            'kelas' => $kelas,
        ];

        return view('pages.pengunjung', $data);
    }

    public function checkMember(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
        ]);

        $code = $request->code;
        $is_check_absensi = $request->is_check_absensi;
        if ($is_check_absensi == 'Y') {
            $currentDate = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');

            $existingVisit = AbsensiPengunjung::whereDate('created_at', $currentDate)->where('nik_siswa', $code)->orWhere('nik_guru', $code)->first();

            if ($existingVisit) {
                return response()->json(['message' => 'Anda sudah melakukan absensi hari ini!'], 400);
            }
        }

        $guru = Guru::where('nik', $code)->first();
        if ($guru) {
            $data = [
                'id' => $guru->id,
                'nik' => $guru->nik,
                'nama_guru' => $guru->nama_guru,
                'nama_mata_pelajaran' => $guru->nama_mata_pelajaran ?? '-',
                'role' => 'guru',
            ];
            return response()->json(['type' => 'guru', 'data' => $data, 'message' => 'Data guru berhasil.']);
        }

        $siswa = Siswa::where('nik', $code)->first();
        if ($siswa) {
            $kelas = $siswa->kelas ? "{$siswa->kelas->tingkat_kelas} {$siswa->kelas->kelompok} ({$siswa->kelas->urusan_kelas}) (Jurusan {$siswa->kelas->jurusan})" : '-';
            $data = [
                'id' => $siswa->id,
                'foto' => asset($siswa->foto),
                'nik' => $siswa->nik,
                'nama_siswa' => $siswa->nama_siswa,
                'kelas' => $kelas,
                'role' => 'siswa',
            ];
            return response()->json(['type' => 'siswa', 'data' => $data, 'message' => 'Data siswa berhasil.']);
        }

        return response()->json(['message' => 'Kode tidak ditemukan'], 404);
    }

    public function saveVisit(Request $request)
    {
        $currentDate = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');

        if ($request->has('guru_id') && $request->has('kelas_id') && $request->has('materi')) {
            // Class visit
            $request->validate([
                'guru_id' => 'required|exists:guru,id',
                'kelas_id' => 'required|exists:kelas,id',
                'materi' => 'required|string|max:255',
                'nik' => 'nullable|string|max:255',
            ]);

            $nik = $request->input('nik');
            $existingVisit = AbsensiPengunjung::whereDate('created_at', $currentDate)
                ->where(function ($query) use ($nik) {
                    $query->where('nik_siswa', $nik)->orWhere('nik_guru', $nik);
                })
                ->first();

            if ($existingVisit) {
                return response()->json(['message' => 'Anda sudah melakukan absensi hari ini!'], 400);
            }

            $data = [
                'guru_id' => $request->guru_id,
                'kelas_id' => $request->kelas_id,
                'materi' => $request->materi,
                'is_kunjungan_kelas' => true,
            ];

            if ($nik) {
                $siswa = Siswa::where('nik', $nik)->first();
                $guru = Guru::where('nik', $nik)->first();
                if ($siswa) {
                    $data['nik_siswa'] = $nik;
                } elseif ($guru) {
                    $data['nik_guru'] = $nik;
                }
            }

            AbsensiPengunjung::create($data);

            return response()->json(['success' => true, 'message' => 'Kunjungan Kelas berhasil disimpan.']);
        } else {
            // Individual visit
            $request->validate([
                'nik' => 'required|string|max:255',
            ]);

            $nik = $request->input('nik');
            $existingVisit = AbsensiPengunjung::whereDate('created_at', $currentDate)
                ->where(function ($query) use ($nik) {
                    $query->where('nik_siswa', $nik)->orWhere('nik_guru', $nik);
                })
                ->first();

            if ($existingVisit) {
                return response()->json(['message' => 'Anda sudah melakukan absensi hari ini!'], 400);
            }

            $data = ['is_kunjungan_kelas' => false];
            $siswa = Siswa::where('nik', $nik)->first();
            $guru = Guru::where('nik', $nik)->first();

            if ($siswa) {
                $data['nik_siswa'] = $nik;
            } elseif ($guru) {
                $data['nik_guru'] = $nik;
            } else {
                return response()->json(['message' => 'Kode NIK tidak valid'], 404);
            }

            AbsensiPengunjung::create($data);

            return response()->json(['success' => true, 'message' => 'Absensi individu berhasil disimpan.']);
        }
    }

    public function bebasPerpus(Request $request)
    {
        $currentDate = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $currentTime = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i');

        $data = [
            'currentDate' => $currentDate,
            'currentTime' => $currentTime,
        ];

        return view('pages.bebas-perpus', $data);
    }

    public function checkPinjaman(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:255',
        ]);

        $nik = $request->input('nik');

        $siswaLoans = PeminjamanSiswa::with(['qrBuku.buku'])
            ->where('nik_siswa', $nik)
            ->whereIn('status_peminjaman', ['dipinjam', 'telat', 'bermasalah'])
            ->orderBy('tanggal_pinjam', 'DESC')
            ->get()
            ->map(function ($loan) {
                return [
                    'buku_judul' => $loan->qrBuku->buku->judul_buku ?? 'Unknown',
                    'kode_buku' => $loan->qrBuku->kode ?? 'Unknown',
                ];
            });

        $guruLoans = PeminjamanGuru::with(['qrBuku.buku'])
            ->where('nik_guru', $nik)
            ->whereIn('status_peminjaman', ['dipinjam', 'telat'])
            ->orderBy('tanggal_pinjam', 'DESC')
            ->get()
            ->map(function ($loan) {
                return [
                    'buku_judul' => $loan->qrBuku->buku->judul_buku ?? 'Unknown',
                    'kode_buku' => $loan->qrBuku->kode ?? 'Unknown',
                ];
            });

        $loans = $siswaLoans->merge($guruLoans)->toArray();
        $hasLoans = count($loans) > 0;

        return response()->json([
            'hasLoans' => $hasLoans,
            'loans' => $loans,
            'message' => $hasLoans ? 'Ada peminjaman yang belum dikembalikan.' : 'Tidak ada peminjaman aktif.',
        ]);
    }

    public function printReceipt(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:255',
        ]);

        $nik = $request->input('nik');

        $guru = Guru::where('nik', $nik)->first();
        if ($guru) {
            $userData = [
                'nik' => $guru->nik,
                'nama_guru' => $guru->nama_guru,
                'nama_mata_pelajaran' => $guru->nama_mata_pelajaran ?? '-',
                'role' => 'guru',
            ];
        } else {
            $siswa = Siswa::where('nik', $nik)->first();
            if ($siswa) {
                $kelas = $siswa->kelas ? "{$siswa->kelas->tingkat_kelas} {$siswa->kelas->kelompok} ({$siswa->kelas->urusan_kelas}) (Jurusan {$siswa->kelas->jurusan})" : '-';
                $userData = [
                    'nik' => $siswa->nik,
                    'nama_siswa' => $siswa->nama_siswa,
                    'kelas' => $kelas,
                    'role' => 'siswa',
                ];
            } else {
                return response()->json(['message' => 'Kode tidak ditemukan'], 404);
            }
        }

        $siswaLoans = PeminjamanSiswa::with(['qrBuku.buku'])
            ->where('nik_siswa', $nik)
            ->whereIn('status_peminjaman', ['dipinjam', 'telat', 'bermasalah'])
            ->orderBy('tanggal_pinjam', 'DESC')
            ->get();

        $guruLoans = PeminjamanGuru::with(['qrBuku.buku'])
            ->where('nik_guru', $nik)
            ->whereIn('status_peminjaman', ['dipinjam', 'telat'])
            ->orderBy('tanggal_pinjam', 'DESC')
            ->get();

        $loans = $siswaLoans->merge($guruLoans);
        $hasLoans = $loans->isNotEmpty();

        return view('pages.print-receipt', compact('userData', 'loans', 'hasLoans'));
    }

    public function informasi()
    {
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $topVisitor = AbsensiPengunjung::select('nik_siswa', 'nik_guru')
            ->selectRaw('COUNT(*) as visit_count')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->groupBy('nik_siswa', 'nik_guru')
            ->orderByDesc('visit_count')
            ->first();

        $topVisitorDetails = null;
        if ($topVisitor) {
            if ($topVisitor->nik_siswa) {
                $topVisitorDetails = Siswa::where('nik', $topVisitor->nik_siswa)->select('nik', 'nama_siswa as nama', 'foto')->first();
            } elseif ($topVisitor->nik_guru) {
                $topVisitorDetails = Guru::where('nik', $topVisitor->nik_guru)->select('nik', 'nama_guru as nama', 'foto')->first();
            }
            if ($topVisitorDetails) {
                $topVisitorDetails->visit_count = $topVisitor->visit_count;
            }
        }

        $latestBooks = Buku::select('judul_buku', 'cover_buku')->orderBy('created_at', 'DESC')->take(10)->get();

        $videos = Video::select('judul', 'video_url', 'no_urut')->orderBy('no_urut')->get();

        $todayVisitors = AbsensiPengunjung::whereDate('created_at', $today)->count();

        $todayBorrowers =
            PeminjamanSiswa::whereDate('created_at', $today)
                ->whereIn('status_peminjaman', ['dipinjam', 'telat', 'bermasalah'])
                ->count() +
            PeminjamanGuru::whereDate('created_at', $today)
                ->whereIn('status_peminjaman', ['dipinjam', 'telat'])
                ->count();

        $todayReaders = LogAktivitasSiswa::whereDate('created_at', $today)->whereNotNull('id_buku')->count() + 
                        LogAktivitasGuru::whereDate('created_at', $today)->whereNotNull('id_buku')->count();

        $mostReadBooks = Buku::select('buku.judul_buku','buku.sinopsis', 'buku.cover_buku')
            ->leftJoin('log_aktivitas_siswas', 'buku.id', '=', 'log_aktivitas_siswas.id_buku')
            ->leftJoin('log_aktivitas_gurus', 'buku.id', '=', 'log_aktivitas_gurus.id_buku')
            ->where(function ($query) use ($monthStart, $monthEnd) {
                $query->whereBetween('log_aktivitas_siswas.created_at', [$monthStart, $monthEnd])
                    ->orWhereBetween('log_aktivitas_gurus.created_at', [$monthStart, $monthEnd]);
            })
            ->groupBy('buku.id', 'buku.judul_buku', 'buku.cover_buku')
            ->selectRaw('COUNT(*) as read_count')
            ->orderByDesc('read_count')
            ->take(10)
            ->get();

        $data = [
            'topVisitor' => $topVisitorDetails,
            'latestBooks' => $latestBooks,
            'videos' => $videos,
            'todayVisitors' => $todayVisitors,
            'todayBorrowers' => $todayBorrowers,
            'todayReaders' => $todayReaders,
            'mostReadBooks' => $mostReadBooks,
        ];

        return view('pages.informasi', $data);
    }
}
