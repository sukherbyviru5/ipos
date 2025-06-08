<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Helpers\QrCodeHelper;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    private $kelas_id;

    public function __construct($kelas_id)
    {
        $this->kelas_id = $kelas_id;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip if required fields are missing
        if (!isset($row['nik']) || !isset($row['nisn']) || !isset($row['nama']) || !isset($row['lp'])) {
            return null;
        }

        // Check for duplicate NIK or NISN
        if (Siswa::where('nik', $row['nik'])->orWhere('nisn', $row['nisn'])->exists()) {
            return null;
        }

        $qrCodePath = QrCodeHelper::generateQrCode($row['nisn'], $row['nisn']);
        return new Siswa([
            'nik' => $row['nik'],
            'nisn' => $row['nisn'],
            'password' => Hash::make($row['nisn']),
            'nama_siswa' => $row['nama'],
            'jenis_kelamin' => $row['lp'] === 'L' ? 'Laki-laki' : 'Perempuan',
            'tempat_lahir' => $row['tempat_lahir'] ?? null,
            'tanggal_lahir' => $this->parseDate($row['tanggal_lahir'] ?? null),
            'alamat' => $row['alamat'] ?? null,
            'no_hp' => $row['no_hp'] ?? null,
            'id_kelas' => $this->kelas_id,
            'qr_code' => $qrCodePath,
            'status' => 'aktif',
            'is_alumni' => false,
        ]);
    }

    /**
     * Parse date from Excel to ensure valid format
     *
     * @param mixed $date
     * @return string|null
     */
    private function parseDate($date)
    {
        if (!$date) {
            return null;
        }

        try {
            if (is_numeric($date)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
            }
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}