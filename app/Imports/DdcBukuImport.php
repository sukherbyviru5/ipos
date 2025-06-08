<?php

namespace App\Imports;

use App\Models\DdcBuku;
use App\Models\KategoriBuku;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DdcBukuImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $validator = Validator::make($row, [
            'no_klasifikasi' => ['required', 'min:1'],
            'nama_klasifikasi' => ['required', 'string', 'max:255'],
        ], [
            'no_klasifikasi.required' => 'Nomor urut harus diisi',
            'no_klasifikasi.unique' => 'Nomor urut sudah digunakan',
            'no_klasifikasi.min' => 'Nomor urut minimal 1',
            'nama_klasifikasi.required' => 'Nama kategori harus diisi',
            'nama_klasifikasi.string' => 'Nama kategori harus berupa teks',
            'nama_klasifikasi.max' => 'Nama kategori maksimal 255 karakter'
        ]);

        if(DdcBuku::where('no_klasifikasi', $row['no_klasifikasi'] )->first()) {
            throw new \Exception('Duplicate entry: Nomor klasifikasi ' . $row['no_klasifikasi'] . ' sudah ada.');
        }
        
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        return new DdcBuku([
            'no_klasifikasi' => $row['no_klasifikasi'],
            'nama_klasifikasi' => $row['nama_klasifikasi'],
        ]);
    }
}