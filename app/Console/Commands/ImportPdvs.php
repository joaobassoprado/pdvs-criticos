<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\PdvImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportPdvs extends Command
{
    protected $signature = 'pdv:import';
    protected $description = 'Importa PDVs do Excel';

    public function handle()
    {
        Excel::import(
            new PdvImport,
            storage_path('app/BasePDVs.xlsx')
        );

        $this->info('PDVs importados com sucesso!');
    }
}
