<?php

namespace App\Imports;

use App\Models\Guru;
use Illuminate\Support\Facades\Hash;
use App\Helpers\QrCodeHelper;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class GuruImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $row = array_map('trim', $row); 

        $validator = Validator::make($row, [
            'nik' => 'required|string|unique:guru,nik',
            'nip' => 'required|string|unique:guru,nip',
            'nama_guru' => 'required|string|max:255',
            'nama_mata_pelajaran' => 'nullable|string|max:255',
        ], [
            'nik.required' => 'NIK is required in row: ' . json_encode($row),
            'nip.required' => 'NIP is required in row: ' . json_encode($row),
            'nama_guru.required' => 'Nama Guru is required in row: ' . json_encode($row),
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $qrCodePath = QrCodeHelper::generateQrCode($row['nik'], $row['nik']);

        return new Guru([
            'nik' => $row['nik'],
            'nip' => $row['nip'],
            'password' => Hash::make($row['nip']),
            'nama_guru' => $row['nama_guru'],
            'nama_mata_pelajaran' => $row['nama_mata_pelajaran'],
            'qr_code' => $qrCodePath,
            'status' =>'aktif',
        ]);
    }
}