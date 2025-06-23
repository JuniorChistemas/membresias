<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row){
            Customer::create([
                'first_name' => $row['nombre'],
                'last_name' => $row['apellido'],
                'code' => $row['codigo'],
                'email' => $row['correo'],
                'phone' => $row['telefono'],
                'address' => $row['direccion'],
                'status' => strtolower($row['estado']) === 'activo' ? true : false,
            ]);
        }
    }
}
