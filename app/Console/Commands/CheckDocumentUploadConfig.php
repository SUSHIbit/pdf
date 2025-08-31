<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckDocumentUploadConfig extends Command
{
    protected $signature = 'document:check-config';
    protected $description = 'Check document upload configuration';

    public function handle()
    {
        $this->info('Checking document upload configuration...');

        // Check PHP file upload settings
        $this->info("\n=== PHP Configuration ===");
        $this->line('upload_max_filesize: ' . ini_get('upload_max_filesize'));
        $this->line('post_max_size: ' . ini_get('post_max_size'));
        $this->line('max_file_uploads: ' . ini_get('max_file_uploads'));
        $this->line('max_execution_time: ' . ini_get('max_execution_time'));
        $this->line('memory_limit: ' . ini_get('memory_limit'));

        // Check Laravel storage configuration
        $this->info("\n=== Laravel Storage ===");
        $this->line('Default disk: ' . config('filesystems.default'));
        
        // Check if storage directories exist
        $storagePath = storage_path('app');
        $documentsPath = storage_path('app/documents');
        
        $this->line('Storage path exists: ' . ($this->checkPath($storagePath) ? 'YES' : 'NO'));
        $this->line('Storage path writable: ' . ($this->checkWritable($storagePath) ? 'YES' : 'NO'));
        
        if (!file_exists($documentsPath)) {
            $this->warn('Documents directory does not exist. Creating...');
            mkdir($documentsPath, 0755, true);
            $this->info('Documents directory created.');
        } else {
            $this->line('Documents directory exists: YES');
        }
        
        $this->line('Documents path writable: ' . ($this->checkWritable($documentsPath) ? 'YES' : 'NO'));

        // Check database connection
        $this->info("\n=== Database ===");
        try {
            DB::connection()->getPdo();
            $this->info('Database connection: OK');
            
            // Check if tables exist
            $tables = ['users', 'documents', 'credit_transactions', 'question_sets'];
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    $this->line("Table '$table': EXISTS");
                } else {
                    $this->error("Table '$table': MISSING - Run migrations!");
                }
            }
        } catch (\Exception $e) {
            $this->error('Database connection failed: ' . $e->getMessage());
        }

        // Check queue configuration
        $this->info("\n=== Queue Configuration ===");
        $this->line('Queue driver: ' . config('queue.default'));
        
        if (config('queue.default') === 'database') {
            if (Schema::hasTable('jobs')) {
                $this->info('Jobs table: EXISTS');
            } else {
                $this->error('Jobs table: MISSING - Run queue:table and migrate!');
            }
        }

        $this->info("\n=== Recommendations ===");
        
        // Parse sizes for comparison
        $uploadMax = $this->parseSize(ini_get('upload_max_filesize'));
        $postMax = $this->parseSize(ini_get('post_max_size'));
        
        if ($uploadMax < 10485760) { // 10MB
            $this->warn('upload_max_filesize is less than 10MB. Increase it in php.ini');
        }
        
        if ($postMax < 10485760) { // 10MB
            $this->warn('post_max_size is less than 10MB. Increase it in php.ini');
        }
        
        if (config('queue.default') === 'sync') {
            $this->warn('Queue driver is sync. Consider using database or redis for production.');
        }

        $this->info("\nConfiguration check completed!");
        
        return 0;
    }

    private function checkPath($path)
    {
        return file_exists($path) && is_dir($path);
    }

    private function checkWritable($path)
    {
        return is_writable($path);
    }

    private function parseSize($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }
}