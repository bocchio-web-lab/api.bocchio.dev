<?php

namespace Database\Seeders\Platform;

use App\Models\User;
use App\Services\Platform\Enums\TenantAccessLevel;
use App\Services\Platform\Models\Service;
use App\Services\Platform\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Seed the platform database with tenants.
     */
    public function run(): void
    {
        $this->command->info('Seeding tenants...');

        // Get CMS service
        $cmsService = Service::where('slug', 'cms')->first();
        
        if (!$cmsService) {
            $this->command->error('CMS service not found. Please run ServiceSeeder first.');
            return;
        }

        // Get users
        $owner = User::where('email', 'owner@bocchio.dev')->first();
        $admin = User::where('email', 'admin@bocchio.dev')->first();
        $editor = User::where('email', 'editor@bocchio.dev')->first();

        if (!$owner || !$admin) {
            $this->command->error('Required users not found. Please run UserSeeder first.');
            return;
        }

        // Create tenants
        $publicBlog = Tenant::updateOrCreate(
            ['public_slug' => 'bocchio-tech-blog'],
            [
                'service_id' => $cmsService->id,
                'owner_id' => $owner->id,
                'name' => 'Bocchio Tech Blog',
                'access_level' => TenantAccessLevel::PUBLIC,
                'settings' => [
                    'theme' => 'light',
                    'language' => 'en',
                    'timezone' => 'UTC',
                    'comments_enabled' => true,
                    'moderation_required' => true,
                ],
            ]
        );

        $privatePortfolio = Tenant::updateOrCreate(
            ['public_slug' => 'bocchio-portfolio'],
            [
                'service_id' => $cmsService->id,
                'owner_id' => $owner->id,
                'name' => 'Bocchio Portfolio',
                'access_level' => TenantAccessLevel::PRIVATE,
                'settings' => [
                    'theme' => 'dark',
                    'language' => 'en',
                    'timezone' => 'UTC',
                    'comments_enabled' => false,
                ],
            ]
        );

        $tokenProtectedDocs = Tenant::updateOrCreate(
            ['public_slug' => 'api-documentation'],
            [
                'service_id' => $cmsService->id,
                'owner_id' => $admin->id,
                'name' => 'API Documentation',
                'access_level' => TenantAccessLevel::TOKEN_PROTECTED,
                'settings' => [
                    'theme' => 'auto',
                    'language' => 'en',
                    'timezone' => 'UTC',
                    'comments_enabled' => true,
                    'moderation_required' => false,
                ],
            ]
        );

        // Attach team members to tenants
        if ($editor) {
            $publicBlog->users()->syncWithoutDetaching([
                $editor->id => ['role' => 'editor'],
            ]);

            $tokenProtectedDocs->users()->syncWithoutDetaching([
                $editor->id => ['role' => 'member'],
            ]);
        }

        $this->command->info("✓ Created 3 tenants");
        $this->command->info("  - {$publicBlog->public_slug} (public)");
        $this->command->info("  - {$privatePortfolio->public_slug} (private)");
        $this->command->info("  - {$tokenProtectedDocs->public_slug} (token_protected)");
        $this->command->info("    API Key: {$tokenProtectedDocs->public_api_key}");
    }
}
