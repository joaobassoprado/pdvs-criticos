<?php

namespace App\Imports;

use App\Models\PDV;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PdvImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $nome = trim($row['nome_fantasia']);

        return new PDV([
            'codigo'        => rand(100000, 999999),
            'nome'          => $nome,
            'nome_fantasia' => $nome,
        ]);
    }
}
