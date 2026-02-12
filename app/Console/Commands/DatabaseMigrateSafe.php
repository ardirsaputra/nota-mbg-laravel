<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DatabaseMigrateSafe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:migrate-safe {--force} {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations with automatic backup before migration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        $seed = $this->option('seed');

        if (!$force) {
            if (!$this->confirm('This will backup all tables and run migrations. Continue?', true)) {
                $this->info('Migration cancelled.');
                return 0;
            }
        }

        // Step 1: Backup all tables
        $this->info("\n=== STEP 1: Backing up all tables ===");
        Artisan::call('db:backup', ['--all' => true]);
        $this->info(Artisan::output());

        // Step 2: Run migrations
        $this->info("\n=== STEP 2: Running migrations ===");
        Artisan::call('migrate', ['--force' => true]);
        $this->info(Artisan::output());

        // Step 3: Run seeders if requested
        if ($seed) {
            $this->info("\n=== STEP 3: Running seeders ===");
            Artisan::call('db:seed', ['--force' => true]);
            $this->info(Artisan::output());
        }

        $this->info("\n✓ Migration completed successfully!");
        $this->info("Backups are stored in: storage/app/backups");

        return 0;
    }
}
