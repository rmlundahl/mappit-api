<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ItemImport\ItemImport;



class ImportItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:items {file} {--cleartables}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import an excel file with items and properties';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $file = $this->argument('file');
       
        if ( !file_exists($file) ) {
            $this->error('The specified file does not exist.');
            return 1;
        }
        
        $itemImport = new ItemImport($file, $this->option('cleartables'));

        if ( !$itemImport->import() ) {
            $this->error('The import failed.');
            return 1;
        };

        return 0;
    }
}
