<?php

namespace App\Imports;

use App\Models\Coach;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CoachesImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row){
            Coach::create([
                'name' => $row['nombre'],
                'dni' => $row['dni'],
                'specialty' => $row['especialidad'],
                'phone' => $row['telefono'],
                'email' => $row['correo'],
                'address' => $row['direccion'],
                'status' => strtolower($row['estado']) === 'activo' ? true : false,
            ]);
        }
    }
}
