<?php

namespace Database\Seeders\Cms;

use App\Services\Cms\Models\Tag;
use App\Services\Platform\Models\Tenant;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Seed the CMS database with tags.
     */
    public function run(): void
    {
        $this->command->info('Seeding tags...');

        // Get tenants
        $publicBlog = Tenant::where('public_slug', 'bocchio-tech-blog')->first();
        $portfolio = Tenant::where('public_slug', 'bocchio-portfolio')->first();
        $docs = Tenant::where('public_slug', 'api-documentation')->first();

        if (!$publicBlog || !$portfolio || !$docs) {
            $this->command->error('Tenants not found. Please run TenantSeeder first.');
            return;
        }

        $tagCount = 0;

        // Tags for public blog
        $blogTags = [
            ['name' => 'Laravel', 'slug' => 'laravel'],
            ['name' => 'PHP', 'slug' => 'php'],
            ['name' => 'JavaScript', 'slug' => 'javascript'],
            ['name' => 'Vue.js', 'slug' => 'vuejs'],
            ['name' => 'React', 'slug' => 'react'],
            ['name' => 'Node.js', 'slug' => 'nodejs'],
            ['name' => 'Tutorial', 'slug' => 'tutorial'],
            ['name' => 'Best Practices', 'slug' => 'best-practices'],
            ['name' => 'DevOps', 'slug' => 'devops'],
            ['name' => 'Testing', 'slug' => 'testing'],
            ['name' => 'API Development', 'slug' => 'api-development'],
            ['name' => 'Database', 'slug' => 'database'],
        ];

        foreach ($blogTags as $tagData) {
            Tag::updateOrCreate(
                [
                    'tenant_id' => $publicBlog->id,
                    'slug' => $tagData['slug'],
                ],
                [
                    'name' => $tagData['name'],
                ]
            );
            $tagCount++;
        }

        // Tags for portfolio
        $portfolioTags = [
            ['name' => 'Web Development', 'slug' => 'web-development'],
            ['name' => 'Mobile Apps', 'slug' => 'mobile-apps'],
            ['name' => 'SaaS', 'slug' => 'saas'],
            ['name' => 'E-Commerce', 'slug' => 'ecommerce'],
            ['name' => 'CMS', 'slug' => 'cms'],
        ];

        foreach ($portfolioTags as $tagData) {
            Tag::updateOrCreate(
                [
                    'tenant_id' => $portfolio->id,
                    'slug' => $tagData['slug'],
                ],
                [
                    'name' => $tagData['name'],
                ]
            );
            $tagCount++;
        }

        // Tags for documentation
        $docsTags = [
            ['name' => 'Authentication', 'slug' => 'authentication'],
            ['name' => 'REST API', 'slug' => 'rest-api'],
            ['name' => 'GraphQL', 'slug' => 'graphql'],
            ['name' => 'Webhooks', 'slug' => 'webhooks'],
            ['name' => 'Rate Limiting', 'slug' => 'rate-limiting'],
            ['name' => 'Security', 'slug' => 'security'],
        ];

        foreach ($docsTags as $tagData) {
            Tag::updateOrCreate(
                [
                    'tenant_id' => $docs->id,
                    'slug' => $tagData['slug'],
                ],
                [
                    'name' => $tagData['name'],
                ]
            );
            $tagCount++;
        }

        $this->command->info("✓ Created {$tagCount} tags across 3 tenants");
    }
}
