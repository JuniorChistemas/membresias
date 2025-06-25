<?php

namespace App\Imports;

use App\Models\Coach;
use Maatwebsite\Excel\Concerns\ToModel;

class CoachesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Coach([
            //
        ]);
    }
}
