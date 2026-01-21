<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        $databases = [
            ['name' => env('PLATFORM_DB_DATABASE', 'platform'), 'description' => 'Platform management database'],
            ['name' => env('CMS_DB_DATABASE', 'cms'), 'description' => 'CMS service database'],
        ];

        $this->info('Creating platform databases...');

        foreach ($databases as $db) {
            try {
                DB::statement("CREATE DATABASE IF NOT EXISTS `{$db['name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $this->info("✓ {$db['description']}: {$db['name']}");
            } catch (\Exception $e) {
                $this->error("✗ Failed to create {$db['name']}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info('Database setup complete!');
        $this->info('Next steps:');
        $this->line('  1. Run: php artisan migrate:fresh');
        $this->line('  2. Run: php artisan migrate --database=platform_db --path=database/migrations/platform');
        $this->line('  3. Run: php artisan db:seed');

        return 0;
    }
}
