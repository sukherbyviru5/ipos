<?php

namespace App\Imports;

use App\Models\Guru;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;

class GuruImport implements ToModel
{
    private $sekolah_id;

    public function __construct($sekolah_id)
    {
        $this->sekolah_id = $sekolah_id;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (!isset($row[0])) {
            return null;
        }

        if (!isset($row[1]) || !isset($row[2]) || !isset($row[3])) {
            return null;
        }

        if (Guru::where('nip_nik_nisn', $row[1])->first() != null) {
            return null;
        }

        return new Guru([
            'nip_nik_nisn' => $row[1],
            'nama' => $row[2],
            'password' => Hash::make($row[3]),
            'password_view' => $row[3],
            'sekolah_id' => $this->sekolah_id,
        ]);
    }
}
