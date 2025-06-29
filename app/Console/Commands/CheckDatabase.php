<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDatabase extends Command
{
    protected $signature = 'app:check-database';
    protected $description = 'Check database connectivity and status';

    public function handle()
    {
        $this->info('Checking database connection...');
        
        try {
            DB::connection()->getPdo();
            $this->info('✅ Database connection successful');
            
            // Check if migrations table exists
            if (DB::getSchemaBuilder()->hasTable('migrations')) {
                $this->info('✅ Migrations table exists');
                
                $migrationCount = DB::table('migrations')->count();
                $this->info("📊 Total migrations: {$migrationCount}");
            } else {
                $this->warn('⚠️ Migrations table does not exist');
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Database connection failed: ' . $e->getMessage());
            return 1;
        }
    }
} 