<?php

namespace App\Imports;

use App\Models\DdcBuku;
use App\Models\KondisiBuku;
use App\Models\KategoriBuku;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KondisiBukuImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $validator = Validator::make($row, [
            'nama_kondisi' => ['required', 'string', 'max:255'],
        ], [
            'nama_kondisi.required' => 'Nama kategori harus diisi',
            'nama_kondisi.string' => 'Nama kategori harus berupa teks',
            'nama_kondisi.max' => 'Nama kategori maksimal 255 karakter'
        ]);

        if (KondisiBuku::where('nama_kondisi', $row['nama_kondisi'])->first()) {
            throw new \Exception('Duplicate entry: Nama kondisi ' . $row['nama_kondisi'] . ' sudah ada.');
        }
        
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        return new KondisiBuku([
            'nama_kondisi' => $row['nama_kondisi'],
        ]);
    }
}