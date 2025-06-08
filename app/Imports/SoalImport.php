<?php

namespace App\Imports;

use App\Models\BankSoal;
use App\Models\Mapel;
use Maatwebsite\Excel\Concerns\ToModel;

class SoalImport implements ToModel
{
    private $mapel_id;

    public function __construct($mapel_id)
    {
        $this->mapel_id = $mapel_id;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        //dd($row);
        if (!isset($row[0]) || !isset($row[1])) {
            return null;
        }

        if (!isset($row[1]) || !isset($row[2]) || !isset($row[3]) || !isset($row[4]) || !isset($row[5]) || !isset($row[7])) {
            return null;
        }

        if (count(BankSoal::where('mapel_id', $this->mapel_id)->get()) >= Mapel::where('id', $this->mapel_id)->first()?->jumlah_soal) {
            return null;
        }

        if (!in_array($row[7], array(1, 2, 3, 4, 5))) {
            return null;
        }

        return new BankSoal([
            'soal' => $row[1],
            'pil_1' => $row[2],
            'pil_2' => $row[3],
            'pil_3' => $row[4],
            'pil_4' => $row[5],
            'pil_5' => $row[6],
            'mapel_id' => $this->mapel_id,
            'jenis_soal' => 1,
            'kunci' => $row[7]
        ]);
    }
}
