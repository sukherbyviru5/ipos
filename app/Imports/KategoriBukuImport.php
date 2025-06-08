<?php

namespace App\Imports;

use App\Models\KategoriBuku;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KategoriBukuImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $validator = Validator::make($row, [
            'no_urut' => ['required', Rule::unique('kategori_buku', 'no_urut'), 'numeric', 'min:1'],
            'nama_kategori' => ['required', 'string', 'max:255'],
        ], [
            'no_urut.required' => 'Nomor urut harus diisi',
            'no_urut.unique' => 'Nomor urut sudah digunakan',
            'no_urut.numeric' => 'Nomor urut harus berupa angka',
            'no_urut.min' => 'Nomor urut minimal 1',
            'nama_kategori.required' => 'Nama kategori harus diisi',
            'nama_kategori.string' => 'Nama kategori harus berupa teks',
            'nama_kategori.max' => 'Nama kategori maksimal 255 karakter'
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        return new KategoriBuku([
            'no_urut' => $row['no_urut'],
            'nama_kategori' => $row['nama_kategori'],
        ]);
    }
}