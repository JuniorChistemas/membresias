<?php

namespace App\Imports;

use App\Models\PaymentMethod;
use Maatwebsite\Excel\Concerns\ToModel;

class PaymentMethodsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            PaymentMethod::create([
                'name' => $row['nombre'],
                'description' => $row['descripcion'],
                'status' => strtolower($row['estado']) === 'activo' ? true : false,
            ]);
        }
    }
}
