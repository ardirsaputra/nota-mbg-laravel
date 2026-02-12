<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class DatabaseRestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:restore {table?} {--file=} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore database tables from JSON backup files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $table = $this->argument('table');
        $file = $this->option('file');
        $all = $this->option('all');

        $tables = ['harga_barang_pokok', 'satuan', 'nota', 'nota_items', 'users'];

        if ($all) {
            $this->info("=== RESTORE ALL TABLES ===\n");
            foreach ($tables as $tableName) {
                $this->restoreTable($tableName);
            }
            $this->info("\n=== RESTORE COMPLETED ===");
        } elseif ($table) {
            if (!in_array($table, $tables)) {
                $this->error("Table $table not found. Available tables: " . implode(', ', $tables));
                return 1;
            }
            $this->restoreTable($table, $file);
        } else {
            $this->info("Usage:");
            $this->info("  php artisan db:restore --all                    # Restore all tables from latest backup");
            $this->info("  php artisan db:restore <table>                  # Restore specific table from latest backup");
            $this->info("  php artisan db:restore <table> --file=<file>    # Restore from specific backup file");
            $this->info("\nAvailable tables: " . implode(', ', $tables));
        }

        return 0;
    }

    /**
     * Restore specific table from JSON backup
     */
    protected function restoreTable($tableName, $specificFile = null)
    {
        try {
            $backupDir = storage_path('app/backups');

            if (!is_dir($backupDir)) {
                $this->error("Backup directory not found.");
                return false;
            }

            // Find backup file
            if ($specificFile) {
                $backupFile = "$backupDir/$specificFile";
                if (!file_exists($backupFile)) {
                    $this->error("Backup file not found: $specificFile");
                    return false;
                }
            } else {
                // Find latest backup
                $files = glob("$backupDir/{$tableName}_*.json");
                if (empty($files)) {
                    $this->warn("No backup found for table $tableName");
                    return false;
                }
                usort($files, function ($a, $b) {
                    return filemtime($b) - filemtime($a);
                });
                $backupFile = $files[0];
            }

            // Read backup file
            $json = file_get_contents($backupFile);
            $data = json_decode($json, true);

            if (!is_array($data)) {
                $this->error("Backup file corrupt or invalid format: " . basename($backupFile));
                return false;
            }

            // Restore data
            DB::beginTransaction();
            try {
                // Truncate table
                DB::table($tableName)->truncate();

                // Insert data
                if (count($data) > 0) {
                    // Convert stdClass objects to arrays if needed
                    $insertData = array_map(function ($row) {
                        return (array) $row;
                    }, $data);

                    // Insert in chunks to avoid memory issues
                    $chunks = array_chunk($insertData, 100);
                    foreach ($chunks as $chunk) {
                        DB::table($tableName)->insert($chunk);
                    }
                }

                DB::commit();
                $this->info("✓ Restore table $tableName from " . basename($backupFile) . " (" . count($data) . " records)");
                return true;
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            $this->error("✗ Failed to restore $tableName: " . $e->getMessage());
            return false;
        }
    }
}
