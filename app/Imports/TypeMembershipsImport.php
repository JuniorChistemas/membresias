<?php

namespace App\Imports;

use App\Models\TypeMembership;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TypeMembershipsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            TypeMembership::create([
                'name' => $row['nombre'],
                'description' => $row['descripcion'],
                'price' => $row['precio'],
                'status' => strtolower($row['estado']) === 'activo' ? true : false,
            ]);
        }
    }
}
