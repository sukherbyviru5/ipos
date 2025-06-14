<?php

namespace App\Imports;

use Exception;
use App\Models\Buku;
use App\Models\QrBuku;
use App\Models\DdcBuku;
use App\Models\JenisBuku;
use App\Models\KondisiBuku;
use App\Models\KategoriBuku;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BukuImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $mappedRow = array_change_key_case($row);

        $isEmpty = empty(array_filter($mappedRow, function ($value) {
            return $value !== null && $value !== '';
        }));

        if ($isEmpty) {
            return null; 
        }


        $validator = Validator::make($mappedRow, [
            'ddc' => 'required',
            'kategori_buku' => 'required',
            'jenis_buku' => 'required',
            'kondisi_buku' => 'required',
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
        ], [
            'ddc.required' => 'DDC harus diisi',
            'kategori_buku.required' => 'ID Kategori harus diisi',
            'jenis_buku.required' => 'ID Jenis harus diisi',
            'kondisi_buku.required' => 'ID Kondisi harus diisi',
            'judul_buku.required' => 'Judul Buku harus diisi',
            'judul_buku.string' => 'Judul Buku harus berupa teks',
            'judul_buku.max' => 'Judul Buku maksimal 255 karakter',
            'singkatan_buku.string' => 'Singkatan Buku harus berupa teks',
            'singkatan_buku.max' => 'Singkatan Buku maksimal 255 karakter',
            'isbn.string' => 'ISBN harus berupa teks',
            'isbn.max' => 'ISBN maksimal 255 karakter',
            'penulis_buku.string' => 'Penulis Buku harus berupa teks',
            'penulis_buku.max' => 'Penulis Buku maksimal 255 karakter',
            'penerbit_buku.string' => 'Penerbit Buku harus berupa teks',
            'penerbit_buku.max' => 'Penerbit Buku maksimal 255 karakter',
            'tempat_terbit.string' => 'Tempat Terbit harus berupa teks',
            'tempat_terbit.max' => 'Tempat Terbit maksimal 255 karakter',
            'tahun_terbit.integer' => 'Tahun Terbit harus berupa angka',
            'asal_buku.string' => 'Asal Buku harus berupa teks',
            'asal_buku.max' => 'Asal Buku maksimal 255 karakter',
            'sinopsis.string' => 'Sinopsis harus berupa teks',
            'harga_buku.numeric' => 'Harga Buku harus berupa angka',
            'harga_buku.min' => 'Harga Buku tidak boleh kurang dari 0',
            'stok_buku.required' => 'Stok Buku harus diisi',
            'stok_buku.integer' => 'Stok Buku harus berupa angka',
            'stok_buku.min' => 'Stok Buku tidak boleh kurang dari 0',
            'lokasi_lemari.string' => 'Lokasi Lemari harus berupa teks',
            'lokasi_lemari.max' => 'Lokasi Lemari maksimal 255 karakter',
            'lokasi_rak.string' => 'Lokasi Rak harus berupa teks',
            'lokasi_rak.max' => 'Lokasi Rak maksimal 255 karakter',
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }

        $ddc = DdcBuku::where('nama_klasifikasi', $mappedRow['ddc'])->first();
        if (!$ddc) {
            throw new Exception('DDC tidak ditemukan: ' . $mappedRow['ddc']);
        }
        $jenis = JenisBuku::where('nama_jenis', $mappedRow['jenis_buku'])->first();
        if (!$jenis) {
            throw new Exception('Jenis Buku tidak ditemukan: ' . $mappedRow['jenis_buku']);
        }
        $kondisi = KondisiBuku::where('nama_kondisi', $mappedRow['kondisi_buku'])->first();
        if (!$kondisi) {
            throw new Exception('Kondisi Buku tidak ditemukan: ' . $mappedRow['kondisi_buku']);
        }
        $kategori = KategoriBuku::where('nama_kategori', $mappedRow['kategori_buku'])->first();
        if (!$kategori) {
            throw new Exception('Kategori Buku tidak ditemukan: ' . $mappedRow['kategori_buku']);
        }

        // Prepare data for Buku model
        $data = [
            'id_ddc' => $ddc->id,
            'id_kategori' => $kategori->id,
            'id_jenis' => $jenis->id,
            'id_kondisi' => $kondisi->id,
            'judul_buku' => $mappedRow['judul_buku'],
            'singkatan_buku' => $mappedRow['singkatan_buku'] ?? null,
            'isbn' => $mappedRow['isbn'] ?? null,
            'penulis_buku' => $mappedRow['penulis_buku'] ?? null,
            'penerbit_buku' => $mappedRow['penerbit_buku'] ?? null,
            'tempat_terbit' => $mappedRow['tempat_terbit'] ?? null,
            'tahun_terbit' => $mappedRow['tahun_terbit'] ?? null,
            'asal_buku' => $mappedRow['asal_buku'] ?? null,
            'sinopsis' => $mappedRow['sinopsis'] ?? null,
            'harga_buku' => $mappedRow['harga_buku'] ?? null,
            'stok_buku' => $mappedRow['stok_buku'],
            'lokasi_lemari' => $mappedRow['lokasi_lemari'] ?? null,
            'lokasi_rak' => $mappedRow['lokasi_rak'] ?? null,
            'created_by' => auth()->check() ? auth()->user()->nip_nik_nisn : null,
            'kode_buku' => Buku::generateKodeBuku(
                $ddc->id,
                $kategori->id,
                strtolower(str_replace(' ', '-', $mappedRow['penerbit_buku'] ?? auth()->user()->nama ?? '')),
                strtoupper(str_replace(' ', '', $mappedRow['singkatan_buku'] ?? $mappedRow['judul_buku'])),
            ),
            'ebook_tersedia' => false,
            'view_count' => 0,
            'cover_buku' => null,
            'ebook_file' => null,
        ];

        $buku = Buku::create($data);
        if (!$buku) {
            throw new Exception('Gagal menyimpan data buku');
        }

        $currentQrs = QrBuku::where('id_buku', $buku->id)->get();

        if ($mappedRow['stok_buku'] > count($currentQrs)) {
            for ($i = count($currentQrs) + 1; $i <= $mappedRow['stok_buku']; $i++) {
                $code = Buku::generateKodeBuku(
                    $ddc->id,
                    $kategori->id,
                    strtolower(str_replace(' ', '-', $mappedRow['penerbit_buku'] ?? auth()->user()->nama ?? '')),
                    strtoupper(str_replace(' ', '', $mappedRow['singkatan_buku'] ?? $mappedRow['judul_buku'])),
                    $i
                );
                QrBuku::create([
                    'id_buku' => $buku->id,
                    'no_urut' => $i,
                    'kode' => $code,
                    'path_qr' => null,  
                ]);
            }
        } elseif ($mappedRow['stok_buku'] < count($currentQrs)) {
            $extraQrs = $currentQrs->slice($mappedRow['stok_buku']);
            foreach ($extraQrs as $qr) {
                $qr->delete();
            }
        }
        
        return $buku;
    }
}