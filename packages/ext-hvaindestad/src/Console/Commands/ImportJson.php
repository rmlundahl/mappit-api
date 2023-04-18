<?php

namespace Mappit\ExtHvaindestad\Console\Commands;

use Illuminate\Console\Command;
use Mappit\ExtHvaindestad\Services\Import\ImportJsonData;

class ImportJson extends Command
{

    // The name and signature of the console command.
    protected $signature = 'exthvaindestad:import_json';

    // The console command description.
    protected $description = 'Imports a json file from HvA Salesforce into the database';

    protected $importJsonData;

    public function __construct(ImportJsonData $importJsonData)
    {
        parent::__construct();
        $this->importJsonData = $importJsonData;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->importJsonData->import_json_data();
    }
}
