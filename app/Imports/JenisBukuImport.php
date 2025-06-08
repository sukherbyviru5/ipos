<?php

namespace App\Imports;

use App\Models\JenisBuku;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JenisBukuImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $validator = Validator::make($row, [
            'nama_jenis' => ['required', 'string', 'max:255'],
        ], [
            'nama_jenis.required' => 'Nama kategori harus diisi',
            'nama_jenis.string' => 'Nama kategori harus berupa teks',
            'nama_jenis.max' => 'Nama kategori maksimal 255 karakter'
        ]);

        $namaJenisLower = strtolower($row['nama_jenis']);
        if (JenisBuku::whereRaw('LOWER(nama_jenis) = ?', [$namaJenisLower])->first()) {
            throw new \Exception('Duplicate entry: Nama Jenis ' . $row['nama_jenis'] . ' sudah ada.');
        }
        
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        return new JenisBuku([
            'nama_jenis' => $row['nama_jenis'],
        ]);
    }
}