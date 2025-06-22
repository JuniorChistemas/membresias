<?php

namespace App\Imports;

use App\Models\Local;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LocalImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            Local::create([
                'name' => $row['name'],
                'address' => $row['address'],
                'status' => strtolower($row['estado']) === 'activo' ? true : false,
            ]);
        }
    }
}
