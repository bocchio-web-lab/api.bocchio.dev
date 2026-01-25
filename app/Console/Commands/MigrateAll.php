<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MigrateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'platform:migrate-all {--fresh : Drop all tables and re-run migrations} {--seed : Seed the database after migrating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations across all databases (identity, platform, cms)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Starting multi-database migrations...');
        $this->newLine();

        $migrations = [
            [
                'name' => 'Identity Database',
                'connection' => 'identity_db',
                'path' => 'database/migrations/2025_06_28_00_identity',
            ],
            [
                'name' => 'Platform Database',
                'connection' => 'platform_db',
                'path' => 'database/migrations/2026_01_22_00_platform',
            ],
            [
                'name' => 'CMS Database',
                'connection' => 'cms_db',
                'path' => 'database/migrations/2026_01_22_01_cms',
            ],
        ];

        foreach ($migrations as $migration) {
            $fullPath = base_path($migration['path']);

            if (!is_dir($fullPath)) {
                $this->warn("⚠️  Skipping missing path: {$migration['path']}");
                continue;
            }

            $this->info("📊 Migrating {$migration['name']} ({$migration['connection']})...");

            $command = $this->option('fresh') ? 'migrate:fresh' : 'migrate';

            Artisan::call($command, [
                '--database' => $migration['connection'],
                '--path' => $migration['path'],
                '--force' => true,
            ]);

            $this->output->write(Artisan::output());
        }

        $this->newLine();
        $this->info('✅ All database migrations completed successfully!');

        // Run seeders if requested
        if ($this->option('seed')) {
            $this->newLine();
            $this->info('🌱 Running database seeders...');
            Artisan::call('db:seed', ['--force' => true]);
            $this->output->write(Artisan::output());
        }

        return 0;
    }
}
