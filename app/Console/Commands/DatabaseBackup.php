<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {table?} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database tables to JSON files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $table = $this->argument('table');
        $all = $this->option('all');

        $tables = ['harga_barang_pokok', 'satuan', 'nota', 'nota_items', 'users'];

        if ($all) {
            $this->info("=== BACKUP ALL TABLES ===\n");
            foreach ($tables as $tableName) {
                $this->backupTable($tableName);
            }
            $this->info("\n=== BACKUP COMPLETED ===");
        } elseif ($table) {
            if (!in_array($table, $tables)) {
                $this->error("Table $table not found. Available tables: " . implode(', ', $tables));
                return 1;
            }
            $this->backupTable($table);
        } else {
            $this->info("Usage:");
            $this->info("  php artisan db:backup --all          # Backup all tables");
            $this->info("  php artisan db:backup <table>        # Backup specific table");
            $this->info("\nAvailable tables: " . implode(', ', $tables));
        }

        return 0;
    }

    /**
     * Backup specific table to JSON
     */
    protected function backupTable($tableName)
    {
        try {
            // Check if table exists
            if (!DB::getSchemaBuilder()->hasTable($tableName)) {
                $this->warn("✗ Table $tableName not found, skipping.");
                return false;
            }

            $timestamp = date('Y-m-d_H-i-s');
            $filename = "{$tableName}_{$timestamp}.json";
            $backupDir = storage_path('app/backups');

            // Create backup directory if not exists
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            // Get all data from table
            $data = DB::table($tableName)->get()->toArray();

            // Convert to JSON
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            // Save to file
            file_put_contents("$backupDir/$filename", $json);

            $this->info("✓ Backup $tableName: $filename (" . count($data) . " records)");
            return true;
        } catch (\Exception $e) {
            $this->error("✗ Error backup $tableName: " . $e->getMessage());
            return false;
        }
    }
}
