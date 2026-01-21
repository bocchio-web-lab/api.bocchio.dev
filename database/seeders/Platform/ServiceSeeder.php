<?php

namespace Database\Seeders\Platform;

use App\Services\Platform\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Content Management System',
                'slug' => 'cms',
                'description' => 'A flexible CMS for managing posts, pages, and media content.',
                'is_active' => true,
            ],
            [
                'name' => 'Game Backend',
                'slug' => 'game',
                'description' => 'Multiplayer game backend service with real-time capabilities.',
                'is_active' => true,
            ],
            [
                'name' => 'Rocket Launch Tracker',
                'slug' => 'rockets',
                'description' => 'Track and monitor rocket launches worldwide.',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['slug' => $service['slug']],
                $service
            );
        }

        $this->command->info('Services seeded successfully!');
    }
}
