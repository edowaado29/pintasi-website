<?php

namespace App\Imports;

use App\Models\DaftarBahan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DaftarBahanImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (empty($row['nama'])) {
        return null; // skip baris kosong
    }
        return new DaftarBahan([
        'nama_bahan'  => $row['nama'],
        'kalori'      => $row['kalori'],
        'protein'     => $row['protein'],
        'lemak'       => $row['lemak'],
        'karbohidrat' => $row['karbohidrat'],
        'serat'       => $row['serat'],
        ]);
    }
} 
