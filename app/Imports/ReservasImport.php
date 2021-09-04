<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ReservasImport implements ToCollection, WithHeadingRow
{

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

          dd($row);
          //valida
          //si es valido agrega a un array
          //si no es valido agrega los errores a aun array
        }


    }
}

// 011-988771-20
