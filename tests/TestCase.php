<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    /**
     * Setup the test case.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Seed platform services once after refresh
        $this->seed('Database\\Seeders\\Platform\\ServiceSeeder');
    }

    /**
     * Define database migrations that should be run.
     */
    protected function defineDatabaseMigrations(): void
    {
        // Run migrations for all databases
        $this->artisan('migrate', ['--database' => 'identity_db']);
        $this->artisan('migrate', ['--database' => 'platform_db', '--path' => 'database/migrations/platform']);
        $this->artisan('migrate', ['--database' => 'cms_db', '--path' => 'database/migrations/cms']);
    }
}
