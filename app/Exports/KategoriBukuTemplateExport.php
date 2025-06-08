<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KategoriBukuTemplateExport implements FromArray, WithHeadings
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'no_urut',
            'nama_kategori',
        ];
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return [
            [1, 'Agama'],
            [2, 'Bahasa'],
            [3, 'Filsafat'],
        ];
    }
}