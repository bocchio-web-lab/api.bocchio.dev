<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateDatabasesSeeder extends Seeder
{
    /**
     * Create required databases if they don't exist.
     */
    public function run(): void
    {
        $databases = [
            env('PLATFORM_DB_DATABASE', 'platform'),
            env('CMS_DB_DATABASE', 'cms'),
        ];

        foreach ($databases as $database) {
            try {
                DB::statement("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $this->command->info("Database '{$database}' created or already exists.");
            } catch (\Exception $e) {
                $this->command->warn("Could not create database '{$database}': " . $e->getMessage());
            }
        }
    }
}
