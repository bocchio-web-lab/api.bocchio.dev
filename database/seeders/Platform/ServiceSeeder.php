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
        $this->command->info('Seeding services...');

        $services = [
            [
                'name' => 'Content Management System',
                'slug' => 'cms',
                'description' => 'A flexible content management system for structuring, publishing, and maintaining websites and blogs.',
                'is_active' => true,
            ],
            [
                'name' => 'Global Direction Explorer',
                'slug' => 'global-direction-explorer',
                'description' => 'A world exploration tool that reveals directions, surface distances, and through-Earth paths between geographic locations.',
                'is_active' => true,
            ],
            [
                'name' => 'Tutoring Management System',
                'slug' => 'tutoring-management-system',
                'description' => 'A centralized system for managing tutoring lessons, students, instructors, and payment records.',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['slug' => $service['slug']],
                $service
            );
        }

        $this->command->info("✓ Created " . count($services) . " services");
    }
}
