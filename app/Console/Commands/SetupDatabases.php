<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class SetupDatabases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'platform:setup-databases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all required databases for the platform';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database creation process...');

        $connections = Config::get('database.connections');

        $targetConnections = ['identity_db', 'platform_db', 'cms_db'];

        foreach ($connections as $key => $config) {
            if (!\in_array($key, $targetConnections)) {
                continue;
            }
            $dbName = $config['database'];
            $charset = $config['charset'] ?? 'utf8mb4';
            $collation = $config['collation'] ?? 'utf8mb4_unicode_ci';

            if (!$dbName) {
                $this->warn("Skipping connection [{$key}]: No database name defined.");
                continue;
            }

            try {
                DB::statement("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET {$charset} COLLATE {$collation}");
                $this->info("✓ Created or verified existence of database: {$dbName}");
            } catch (\Exception $e) {
                $this->error("✗ Failed to create {$dbName}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info('Database setup complete!');
        return 0;
    }
}
