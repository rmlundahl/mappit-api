<?php

namespace Mappit\ExtLerenMetDeStad\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DatabaseImport implements ToArray, WithHeadingRow
{
    use Importable;

    public function array(array $array)
    {
        //
    }
}
