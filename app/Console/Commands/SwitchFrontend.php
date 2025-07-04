<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SwitchFrontend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'frontend:switch {frontend : The frontend number (1, 2, or 3)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Switch between different frontend environments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $frontendNumber = $this->argument('frontend');
        
        // Define available environments
        $env1 = 'hvaindestad';
        $env2 = 'lerenmetdestadleiden';
        $env3 = 'sharemystory';
        
        // Convert number to environment name
        switch ($frontendNumber) {
            case '1':
                $frontend = $env1;
                break;
            case '2':
                $frontend = $env2;
                break;
            case '3':
                $frontend = $env3;
                break;
            default:
                $this->error('Error: Invalid option. Please choose 1, 2, or 3.');
                $this->info('');
                $this->info('Available environments:');
                $this->info("  1) $env1");
                $this->info("  2) $env2");
                $this->info("  3) $env3");
                return 1;
        }
        
        // Check if environment file exists
        $envFile = base_path(".env.$frontend");
        if (!File::exists($envFile)) {
            $this->error("Error: Environment file .env.$frontend does not exist.");
            return 1;
        }
        
        // Switch environment
        $this->info("Switching to $frontend environment...");
        File::copy($envFile, base_path('.env'));
        $this->call('config:clear');
        
        $this->info("Environment switched to $frontend successfully!");
        $dbDatabase = $this->getDatabaseName();
        $this->info("Now using database: $dbDatabase");
        
        return 0;
    }
    
    /**
     * Get the current database name from .env file
     */
    private function getDatabaseName()
    {
        $envContent = File::get(base_path('.env'));
        preg_match('/DB_DATABASE=(.*)/', $envContent, $matches);
        return $matches[1] ?? 'unknown';
    }
}
